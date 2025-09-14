<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Usuario/Pipe0006.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Usuario;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Crypto\CryptoManager;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\EARQ\EARQEventoPreservacaoLoggerInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Filesystem\FilesystemManager;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Utils\CompressServiceInterface;

/**
 * Class Pipe0006.
 */
class Pipe0006 implements PipeInterface
{
    /**
     * Pipe0006 constructor.
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
            UsuarioDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param UsuarioDTO|RestDtoInterface|null $restDTO
     * @param UsuarioEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(
        UsuarioDTO | RestDtoInterface | null &$restDTO,
        UsuarioEntity | EntityInterface $entity
    ): void {
        // Se veio populada logo a mesma tem que ser retornada
        if ($restDTO->getImgChancela()) {
            $filesystem = $this->filesystemManager->getFilesystemService($restDTO->getImgChancela())->get();
            if ($filesystem->has($restDTO->getImgChancela()->getHash())) {
                $encrypter = $this->cryptoManager->getCryptoService($restDTO->getImgChancela());
                $restDTO
                    ->getImgChancela()
                    ->setConteudo(
                        $this->compresser->uncompress(
                            $encrypter->decrypt(
                                $filesystem->read($restDTO->getImgChancela()->getHash())
                            )
                        )
                    );

                $this->eventoPreservacaoLogger
                    ->logEPRES1Descompressao($restDTO->getImgChancela())
                    ->logEPRES2Decifracao($restDTO->getImgChancela());
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
