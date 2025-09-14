<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Chat/Pipe0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Chat;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDTO;
use SuppCore\AdministrativoBackend\Crypto\CryptoManager;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\EARQ\EARQEventoPreservacaoLoggerInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Chat as ChatEntity;
use SuppCore\AdministrativoBackend\Filesystem\FilesystemManager;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Utils\CompressServiceInterface;

/**
 * Class Pipe0001.
 */
class Pipe0001 implements PipeInterface
{
    /**
     * Pipe0001 constructor.
     *
     * @param CryptoManager            $cryptoManager
     * @param FilesystemManager        $filesystemManager
     * @param CompressServiceInterface $compresser
     * @param EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger
     */
    public function __construct(private CryptoManager $cryptoManager,
                                private FilesystemManager $filesystemManager,
                                private CompressServiceInterface $compresser,
                                private EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger)
    {
    }

    public function supports(): array
    {
        return [
            ChatDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ChatDTO|RestDtoInterface|null $restDTO
     * @param ChatEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(
        ChatDTO | RestDtoInterface | null &$restDTO,
        ChatEntity | EntityInterface $entity
    ): void {
        // Se veio populada logo a mesma tem que ser retornada
        if ($restDTO->getCapa()) {
            $filesystem = $this->filesystemManager->getFilesystemService($restDTO->getCapa())->get();
            if ($filesystem->has($restDTO->getCapa()->getHash())) {
                $encrypter = $this->cryptoManager->getCryptoService($restDTO->getCapa());
                $restDTO
                    ->getCapa()
                    ->setConteudo(
                        $this->compresser->uncompress(
                            $encrypter->decrypt(
                                $filesystem->read($restDTO->getCapa()->getHash())
                            )
                        )
                    );

                $this->eventoPreservacaoLogger
                    ->logEPRES1Descompressao($restDTO->getCapa())
                    ->logEPRES2Decifracao($restDTO->getCapa());
            }
        }
    }

    public function getOrder(): int
    {
        return 99;
    }
}
