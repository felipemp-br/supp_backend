<?php

declare(strict_types=1);
/**
 * src/Api/V1/Triggers/ComponenteDigital/MessageHandler/VerificacaoVirusMessageHandler.php
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital\MessageHandler;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital\Message\VerificacaoVirusMessage;
use SuppCore\AdministrativoBackend\EARQ\EARQEventoPreservacaoLoggerInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Process\Process;
use Throwable;
use function dirname;

/**
 * Class VerificacaoVirusMessageHandler
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class VerificacaoVirusMessageHandler
{

    protected const VIRUS_SCAN_TIMEOUT = 600;

    public function __construct(private ComponenteDigitalResource $componenteDigitalResource,
                                private TransactionManager $transactionManager,
                                private LoggerInterface $logger,
                                private EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger)
    {
    }

    /**
     * @param VerificacaoVirusMessage $message
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(VerificacaoVirusMessage $message)
    {
        try {
            $transactionId = $this->transactionManager->begin();
            $componenteDigitalEntity = $this->componenteDigitalResource
                ->getRepository()
                ->findOneBy(['uuid' => $message->getUuid()]);

            $componenteDigitalEntity = $this->componenteDigitalResource
                ->download($componenteDigitalEntity->getId(), $transactionId);

            /** @var ComponenteDigitalDTO $componenteDigitalDTO */
            $componenteDigitalDTO = $this->componenteDigitalResource->getDtoForEntity(
                $componenteDigitalEntity->getId(),
                ComponenteDigitalDTO::class,
                null,
                $componenteDigitalEntity
            );

            $componenteDigitalDTO->setStatusVerificacaoVirus(ComponenteDigitalEntity::SVV_EXECUTANDO);

            $this->componenteDigitalResource->update(
                $componenteDigitalEntity->getId(),
                $componenteDigitalDTO,
                $transactionId
            );

            $this->transactionManager->commit($transactionId);
            $transactionId = $this->transactionManager->begin();

            $componenteDigitalEntity = $this->componenteDigitalResource
                ->download($componenteDigitalEntity->getId(), $transactionId);

            /** @var ComponenteDigitalDTO $componenteDigitalDTO */
            $componenteDigitalDTO = $this->componenteDigitalResource->getDtoForEntity(
                $componenteDigitalEntity->getId(),
                ComponenteDigitalDTO::class,
                null,
                $componenteDigitalEntity
            );

            $filePath = sys_get_temp_dir() .
                '/virus_check_'.rand(1, 999999)
                . '/' . $componenteDigitalEntity->getUuid() . '.' . $componenteDigitalEntity->getExtensao();

            $conteudo = $componenteDigitalEntity->getConteudo();

            $filesystem = new Filesystem();
            $filesystem->dumpFile($filePath, $conteudo);

            $process = Process::fromShellCommandline('clamscan -o -i --no-summary $filepath');
            $process->setEnv(['filepath' => $filePath]);
            $process->setTimeout(self::VIRUS_SCAN_TIMEOUT);
            $resultCode = $process->run();
            $process->wait();
            $output = $process->getOutput();
            $filesystem->remove([$filePath, dirname($filePath)]);

            if (0 !== $resultCode && !$output) {
                throw new \RuntimeException(sprintf(
                    'Falha ao escanear vírus no componente digital %s. [Process Error Output]: %s',
                    $componenteDigitalEntity->getUuid(),
                    $process->getErrorOutput()
                ));
            }

            if (!$output) {
                $componenteDigitalDTO->setStatusVerificacaoVirus(ComponenteDigitalEntity::SVV_SEGURO);
                $this->eventoPreservacaoLogger->logEPRES8VerificacaoVirus($componenteDigitalEntity, true);
            } else {
                $this->logger->info(sprintf(
                    'Vírus detectado no componente digital %s. Output: %s.',
                    $componenteDigitalEntity->getUuid(),
                    $output
                ));

                $componenteDigitalDTO->setStatusVerificacaoVirus(ComponenteDigitalEntity::SVV_INSEGURO);
                $this->eventoPreservacaoLogger->logEPRES8VerificacaoVirus($componenteDigitalEntity, false, $output);
            }

            $this->componenteDigitalResource->update(
                $componenteDigitalEntity->getId(),
                $componenteDigitalDTO,
                $transactionId
            );

            $this->transactionManager->commit($transactionId);
        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage().' - '.$e->getTraceAsString());
            if (isset($componenteDigitalEntity) && isset($componenteDigitalDTO)) {
                $componenteDigitalDTO->setStatusVerificacaoVirus(ComponenteDigitalEntity::SVV_ERRO);

                $this->componenteDigitalResource->update(
                    $componenteDigitalEntity->getId(),
                    $componenteDigitalDTO,
                    $transactionId
                );

                $this->transactionManager->commit($transactionId);
            }
        }
    }
}
