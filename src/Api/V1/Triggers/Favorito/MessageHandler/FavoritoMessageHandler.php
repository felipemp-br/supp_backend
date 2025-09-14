<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/MessageHandler/FavoritoMessage.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Favorito\MessageHandler;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Favorito;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FavoritoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Favorito\Message\FavoritoMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * Class FavoritoMessageHandler.
 */

#[AsMessageHandler]
class FavoritoMessageHandler
{
    /**
     * FavoritoMessageHandler constructor.
     *
     * @param FavoritoResource   $favoritoResource
     * @param UsuarioResource    $usuarioResource
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        private FavoritoResource $favoritoResource,
        private UsuarioResource $usuarioResource,
        private TransactionManager $transactionManager
    ) {
    }

    public function __invoke(FavoritoMessage $message)
    {
        try {
            if ($message->getBlocoFavoritos()) {
                $transactionId = $this->transactionManager->begin();

                //Processa o bloco de favoritos
                foreach (json_decode($message->getBlocoFavoritos()) as $newFavorito) {
                    $usuario = $this->usuarioResource->findOne($newFavorito->usuario);
                    $favorito = $this->favoritoResource
                        ->findOneBy(
                            [
                                'usuario' => $usuario,
                                'objectClass' => $newFavorito->objectClass,
                                'objectId' => $newFavorito->objectId,
                                'context' => $newFavorito->context,
                            ]
                        );

                    if ($favorito) {
                        $favoritoRest = new Favorito();
                        $favoritoRest->setQtdUso($favorito->getQtdUso() + 1);
                        $this->favoritoResource->update($favorito->getId(), $favoritoRest, $transactionId);
                    } else {
                        $label = explode('\\', $newFavorito->objectClass);
                        $favorito = new Favorito();
                        $favorito->setObjectClass($newFavorito->objectClass);
                        $favorito->setObjectId($newFavorito->objectId);
                        $favorito->setContext($newFavorito->context);
                        $favorito->setLabel($label[3]);
                        $favorito->setUsuario($usuario);
                        $favorito->setQtdUso(1);
                        $this->favoritoResource->create($favorito, $transactionId);
                    }
                }

                $this->transactionManager->commit($transactionId);
            }
        } catch (Exception $e) {
        }
    }
}
