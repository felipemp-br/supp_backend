<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0025.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Message\DownloadProcessoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\JuntadaRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0025.
 *
 * @descSwagger=Dispara processo de geração de download em segundo plano.
 *
 * @classeSwagger=Trigger0025
 */
class Trigger0025 implements TriggerInterface
{
    public function __construct(
        private readonly TransactionManager $transactionManager,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly JuntadaRepository $juntadaRepository,
        private readonly ProcessoResource $processoResource,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeDownload',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|Processo|null $restDto
     * @param EntityInterface|ProcessoEntity $entity
     * @param string                         $transactionId
     *
     * @return void
     *
     * @throws \Exception
     */
    public function execute(
        RestDtoInterface|Processo|null $restDto,
        EntityInterface|ProcessoEntity $entity,
        string $transactionId
    ): void {
        $sizeLimit = $this->parameterBag->get('supp_core.administrativo_backend.processo_download_part_size');
        $sequencial = $this->transactionManager->getContext('sequencial', $transactionId)->getValue();

        if ($sizeLimit) {
            $sequencialJuntadasIds = [];

            if ('all' !== $sequencial) {
                $sequencialJuntadasIds = $this->processoResource->processaDigitosExpressaoDownload($sequencial);
            }

            $juntadas = $this->juntadaRepository->getJuntadasProcessoSize($restDto->getId(), $sequencialJuntadasIds);

            $parte = 0;
            $blocoJuntadas[$parte] = [];
            $tamanhoAtual = 0;

            foreach ($juntadas as $juntada) {
                $juntadaSize = $juntada['tamanho_juntada'] / 1048576;

                if ($juntadaSize > $sizeLimit) {
                    $erro = sprintf(
                        'A juntada Id: %s excede o tamanho limite de %s MB para a parte %s do download',
                        $juntada['id'],
                        $sizeLimit,
                        $parte + 1
                    );
                    throw new \Exception($erro);
                }

                if (($tamanhoAtual + $juntadaSize) > $sizeLimit) {
                    ++$parte;
                    $tamanhoAtual = $juntadaSize;
                } else {
                    $tamanhoAtual += $juntadaSize;
                }
                $blocoJuntadas[$parte][] = $juntada['numeracaoSequencial'];
            }

            foreach ($blocoJuntadas as $index => $bloco) {
                $message = new DownloadProcessoMessage(
                    $entity->getUuid(),
                    $this->transactionManager->getContext('tipoDownload', $transactionId)->getValue(),
                    $this->tokenStorage->getToken()->getUserIdentifier(),
                    join(',', $bloco),
                    $parte > 0 ? ($index + 1) : 0,
                    $parte + 1
                );

                $this->transactionManager->addAsyncDispatch($message, $transactionId);
            }
        } else {
            $this->transactionManager->addAsyncDispatch(
                new DownloadProcessoMessage(
                    $entity->getUuid(),
                    $this->transactionManager->getContext('tipoDownload', $transactionId)->getValue(),
                    $this->tokenStorage->getToken()->getUserIdentifier(),
                    $sequencial
                ),
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
