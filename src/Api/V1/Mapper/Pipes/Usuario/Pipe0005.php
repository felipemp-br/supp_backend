<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Usuario/Pipe0005.php.
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
 * Class Pipe0005.
 */
class Pipe0005 implements PipeInterface
{
    /**
     * Pipe0005 constructor.
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
        // Se a imagem de perfil veio populada logo a mesma tem que ser retornada
        if ($restDTO->getImgPerfil()) {
            $filesystem = $this->filesystemManager->getFilesystemService($restDTO->getImgPerfil())->get();
            if ($filesystem->has($restDTO->getImgPerfil()->getHash())) {
                $encrypter = $this->cryptoManager->getCryptoService($restDTO->getImgPerfil());
                $restDTO
                    ->getImgPerfil()
                    ->setConteudo(
                        $this->compresser->uncompress(
                            $encrypter->decrypt(
                                $filesystem->read($restDTO->getImgPerfil()->getHash())
                            )
                        )
                    );

                $this->eventoPreservacaoLogger
                    ->logEPRES1Descompressao($restDTO->getImgPerfil())
                    ->logEPRES2Decifracao($restDTO->getImgPerfil());
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
