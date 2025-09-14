<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Assinatura/MessageHandler/AssinaDocumentoMessageHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Assinatura\MessageHandler;

use JsonException;
use RuntimeException;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Assinatura\Message\AssinaDocumentoMessage;
use SuppCore\AdministrativoBackend\Helpers\AssinaturaLogHelper;
use SuppCore\AdministrativoBackend\Security\InternalLogInService;
use SuppCore\AdministrativoBackend\Utils\AssinaturaService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Throwable;

/**
 * Class AssinaDocumentoMessageHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class AssinaDocumentoMessageHandler
{
    /**
     * @param InternalLogInService          $internalLogInService
     * @param AssinaturaService             $assinaturaService
     * @param UsuarioResource               $usuarioResource
     * @param DocumentoResource             $documentoResource
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param AssinaturaLogHelper           $logger
     */
    public function __construct(
        private readonly InternalLogInService $internalLogInService,
        private readonly AssinaturaService $assinaturaService,
        private readonly UsuarioResource $usuarioResource,
        private readonly DocumentoResource $documentoResource,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly AssinaturaLogHelper $logger,
    ) {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
    }

    /**
     * @param AssinaDocumentoMessage $message
     *
     * @return void
     *
     * @throws JsonException
     */
    public function __invoke(AssinaDocumentoMessage $message): void
    {
        $protocol = null;
        $componentesDigitais = null;

        $usuario = $this->usuarioResource->getRepository()->findOneBy(
            ['id' => $message->getUsuarioId()]
        );

        try {
            // a1,  a3, neoid
            [$protocol] = array_map(
                static fn ($p) => urldecode($p),
                explode(':', $message->getCredential())
            );

            // Se não retornar um usuário, não é possível avisá-lo pelo Mercure, nem pela Notificação via BD
            if (null === $usuario) {
                throw new RuntimeException('Usuario ID '.$message->getUsuarioId().' não encontrado no BD!');
            }

            $documento = $this->documentoResource->getRepository()->findOneBy(['id' => $message->getDocumentoId()]);

            if (null === $documento) {
                throw new RuntimeException(
                    'Documento ID '.$message->getDocumentoId().' não encontrado no BD!'
                );
            }

            // Login
            $this->internalLogInService->logUserIn($usuario);

            if (false === $this->authorizationChecker->isGranted('EDIT', $documento)) {
                throw new RuntimeException('Usuário não possui poderes para editar o documento!');
            }

            $componentesDigitais = $this->assinaturaService->componentesDigitais(
                [$documento],
                $message->isIncluiAnexos()
            );

            if (empty($componentesDigitais)) {
                throw new RuntimeException('Erro ao recuperar os componentes digitais dos documentos!');
            }

            // marcar como assinando
            $this->assinaturaService->setComponenteDigitalSigning($componentesDigitais);

            if ($message->isPades()) {
                // Flatten
                if ($message->isRemoveAssinaturaInvalida()) {
                    $this->assinaturaService->flattenComponentesDigitais($componentesDigitais);
                }
                // Converter em PDF, caso seja necessário
                $this->assinaturaService->convertToPdf($componentesDigitais);
            }

            // Log de início da assinatura
            $this->logger->info(
                'Iniciando assinatura',
                $usuario,
                $protocol,
                $message->isPades(),
                $message->isIncluiAnexos(),
                false,
                [$documento->getId()],
                $componentesDigitais
            );

            // Assinar
            $assinaturas = $this->assinaturaService->sign(
                $message->getCredential(),
                [$documento],
                $usuario,
                $message->isPades(),
                $message->isIncluiAnexos(),
                $message->getAuthType()
            );

            if ('a3' !== $protocol) {
                // o fluxo do assinador externo já contém esse comando
                $this->assinaturaService->publishOnMercure(
                    $usuario->getUsername(),
                    'SIGN_FINISHED',
                    'Assinatura concluída',
                    null,
                    $documento->getId()
                );

                // Log de sucesso da assinatura
                $this->logger->info(
                    'Sucesso na assinatura',
                    $usuario,
                    $protocol,
                    $message->isPades(),
                    $message->isIncluiAnexos(),
                    false,
                    [$documento->getId()],
                    $componentesDigitais,
                    $assinaturas
                );
            }
        } catch (Throwable $throwable) {
            // RabbitMQ + MessageHandler
            // O Log do Throwable padrão no kibana aparece com o username igual a anonymous
            $this->logger->critical(
                'Erro na assinatura',
                ($usuario ?? $message->getUsername()),
                $protocol,
                $message->isPades(),
                $message->isIncluiAnexos(),
                false,
                [$message->getDocumentoId()],
                null,
                null,
                $throwable->getMessage().' in '.$throwable->getFile().':'.$throwable->getLine(),
                $throwable->getTraceAsString()
            );

            // Notifica via mercure e BD
            $this->assinaturaService->notificaUsuario(
                ($usuario ?? $message->getUsername()),
                $message->getDocumentoId(),
                $throwable->getMessage()
            );
        } finally {
            if (!empty($componentesDigitais)
                && ('a3' !== $protocol)
            ) {
                // desmarcar como assinando
                $this->assinaturaService->delComponenteDigitalSigning($componentesDigitais);
            }
        }
    }
}
