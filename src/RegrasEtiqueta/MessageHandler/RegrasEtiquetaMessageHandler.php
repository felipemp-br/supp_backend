<?php

declare(strict_types=1);
/**
 * /src/RegrasEtiqueta/RegrasEtiquetaMessageHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\MessageHandler;

use Gedmo\Blameable\BlameableListener;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Handlers\RegrasEtiquetaHandlerInterface;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Message\RegrasEtiquetaMessage;
use SuppCore\AdministrativoBackend\Security\RolesService;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Throwable;
use Traversable;

/**
 * Class RegrasEtiquetaMessageHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class RegrasEtiquetaMessageHandler
{
    /**
     * @var RegrasEtiquetaHandlerInterface[]
     */
    private array $handlers;

    /**
     * Constructor.
     *
     * @param BlameableListener     $blameableListener
     * @param TransactionManager    $transactionManager
     * @param LoggerInterface       $logger
     * @param UsuarioResource       $usuarioResource
     * @param TokenStorageInterface $tokenStorage
     * @param RolesService          $rolesService
     * @param Traversable           $handlers
     */
    public function __construct(
        private readonly BlameableListener $blameableListener,
        private readonly TransactionManager $transactionManager,
        private readonly LoggerInterface $logger,
        private readonly UsuarioResource $usuarioResource,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly RolesService $rolesService,
        #[TaggedIterator('supp_core.administrativo_backend.regras_etiqueta.handler')]
        Traversable $handlers
    ) {
        $this->handlers = iterator_to_array($handlers);
    }

    /**
     * @param RegrasEtiquetaMessage $message
     * @return void
     * @throws Throwable
     */
    public function __invoke(RegrasEtiquetaMessage $message): void
    {
        $this->logger->info(
            sprintf(
                'Consumindo mensagem %s',
                self::class
            ),
            [
                'messageUuid' => $message->getMessageUuid(),
                'message' => $message
            ]
        );
        $transactionId = $this->transactionManager->begin();
        $this->autenticaUsuario($message, $transactionId);
        array_map(
            fn (RegrasEtiquetaHandlerInterface $handler) => $handler->handle($message, $transactionId),
            array_filter(
                $this->handlers,
                fn (RegrasEtiquetaHandlerInterface $handler) => $handler->support($message)
            )
        );
        $this->transactionManager->commit($transactionId);
    }

    /**
     * @param RegrasEtiquetaMessage $message
     * @param string                $transactionId
     * @return void
     * @throws Throwable
     */
    private function autenticaUsuario(RegrasEtiquetaMessage $message, string $transactionId): void
    {
        if ($message->getUsuarioLogadoId()) {
            try {

                $usuario = $this->usuarioResource->findOne($message->getUsuarioLogadoId());
                $this->logger->info(
                    'Autenticando usuário para execução do handler de regra de etiqueta.',
                    [
                        'usuarioId' => $message->getUsuarioLogadoId(),
                        'messageUuid' => $message->getMessageUuid(),
                        'message' => $message
                    ]
                );

                // Define o usuário para contexto do blameable
                $this->blameableListener->setUserValue($usuario);

                $token = new PostAuthenticationToken(
                    $usuario,
                    'user_provider',
                    $this->rolesService->getContextualRoles($usuario)
                );
                $token->setAttribute('username', $usuario->getUsername());
                $this->tokenStorage->setToken($token);
            } catch (Throwable $e) {
                $this->logger->error(
                    'Erro ao autenticar usuário para execução do handler de regra de etiqueta.',
                    [
                        'usuarioId' => $message->getUsuarioLogadoId(),
                        'messageUuid' => $message->getMessageUuid(),
                        'message' => $message,
                        'error' => $e,
                    ]
                );
                throw $e;
            }
        }
    }
}
