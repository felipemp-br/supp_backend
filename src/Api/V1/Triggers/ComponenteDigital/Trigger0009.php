<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/ComponenteDigital/Trigger0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\ComponenteDigital;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Crypto\CryptoManager;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\EARQ\EARQEventoPreservacaoLoggerInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Filesystem\FilesystemManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Utils\CompressServiceInterface;

/**
 * Class Trigger0009.
 *
 * Popula o conteudo para reversão do hash
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0009 implements TriggerInterface
{
    /**
     * Trigger0009 constructor.
     *
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param CryptoManager             $cryptoManager
     * @param FilesystemManager         $filesystemManager
     * @param CompressServiceInterface  $compresser
     * @param EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger
     */
    public function __construct(private ComponenteDigitalResource $componenteDigitalResource,
                                private CryptoManager $cryptoManager,
                                private FilesystemManager $filesystemManager,
                                private CompressServiceInterface $compresser,
                                private EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger)
    {
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeReverter',
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getHash() && !$restDto->getConteudo()) {
            $filesystem = $this->filesystemManager
                ->getFilesystemService($entity, $restDto)
                ->get();

            if ($filesystem->has($restDto->getHash())) {
                $encrypter = $this->cryptoManager->getCryptoService($entity, $restDto);
                $restDto->setConteudo(
                    $this->compresser->uncompress(
                        $encrypter->decrypt($filesystem->read($restDto->getHash()))
                    )
                );

                $this->eventoPreservacaoLogger
                    ->logEPRES1Descompressao($restDto)
                    ->logEPRES2Decifracao($restDto);
            }
        }
    }

    public function getOrder(): int
    {
        return 10;
    }
}
