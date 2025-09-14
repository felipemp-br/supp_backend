<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0008.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Documento;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssinaturaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Crypto\CryptoManager;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\EARQ\EARQEventoPreservacaoLoggerInterface;
use SuppCore\AdministrativoBackend\Entity\Assinatura;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Filesystem\FilesystemManager;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Utils\CompressServiceInterface;

/**
 * Class Trigger0008.
 *
 * @descSwagger=Antes de restaurar o Documento restaura os Componentes Digitais respectivos e suas assinaturas!
 * @classeSwagger=Trigger0008
 */
class Trigger0008 implements TriggerInterface
{
    /**
     * @param ComponenteDigitalRepository          $componenteDigitalRepository
     * @param ComponenteDigitalResource            $componenteDigitalResource
     * @param AssinaturaResource                   $assinaturaResource
     * @param EntityManagerInterface               $entityManager
     * @param CryptoManager                        $cryptoManager
     * @param FilesystemManager                    $filesystemManager
     * @param CompressServiceInterface             $compresser
     * @param EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger
     */
    public function __construct(
        private ComponenteDigitalRepository $componenteDigitalRepository,
        private ComponenteDigitalResource $componenteDigitalResource,
        private AssinaturaResource $assinaturaResource,
        private EntityManagerInterface $entityManager,
        private CryptoManager $cryptoManager,
        private FilesystemManager $filesystemManager,
        private CompressServiceInterface $compresser,
        private EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger
    ) {
    }

    public function supports(): array
    {
        return [
            DocumentoEntity::class => [
                'beforeUndelete',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|DocumentoDTO|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (array_key_exists('softdeleteable', $this->entityManager->getFilters()->getEnabledFilters())) {
            $this->entityManager->getFilters()->disable('softdeleteable');
        }
        $componentesDigitais = $this->componenteDigitalRepository->findBy(['documento' => $entity->getId()]);
        if ($componentesDigitais) {
            foreach ($componentesDigitais as $componenteDigital) {
                $componenteDigitalDto = $this->componenteDigitalResource->getDtoForEntity(
                    $componenteDigital->getId(),
                    ComponenteDigitalDTO::class,
                );
                $filesystem = $this->filesystemManager
                    ->getFilesystemService($componenteDigital, $componenteDigitalDto)
                    ->get();

                // Popula o conteudo para undelete do componentedigital
                if ($componenteDigital->getHash() && !$componenteDigital->getConteudo() &&
                    $filesystem->has($componenteDigital->getHash())) {
                    $encrypter = $this->cryptoManager->getCryptoService($componenteDigital, $restDto);
                    $componenteDigital->setConteudo(
                        $this->compresser->uncompress(
                            $encrypter->decrypt($filesystem->read($componenteDigital->getHash()))
                        )
                    );

                    $this->eventoPreservacaoLogger
                        ->logEPRES1Descompressao($componenteDigital)
                        ->logEPRES2Decifracao($componenteDigital);

                    // Chama o metodo para setar nulo apagadoEm e apagadoPor
                    $this->componenteDigitalResource
                        ->undelete($componenteDigital->getId(), $transactionId);
                }

                /** @var Assinatura $assinatura */
                foreach ($componenteDigital->getAssinaturas() as $assinatura) {
                    // Chama o metodo para setar nulo apagadoEm e apagadoPor
                    $this->assinaturaResource
                        ->undelete($assinatura->getId(), $transactionId);
                }
            }
        }
        if (!array_key_exists('softdeleteable', $this->entityManager->getFilters()->getEnabledFilters())) {
            $this->entityManager->getFilters()->enable('softdeleteable');
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
