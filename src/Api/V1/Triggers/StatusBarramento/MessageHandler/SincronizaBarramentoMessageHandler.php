<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Processo/MessageHandler/SincronizaBarramentoMessageHandler.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\StatusBarramento\MessageHandler;

use Exception;
use Gedmo\Blameable\BlameableListener;
use Psr\Log\LoggerInterface as Logger;
use RuntimeException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao as NotificacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\StatusBarramentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\StatusBarramento\Message\SincronizaBarramentoMessage;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoRecebeComponenteDigital;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoSolicitacao;
use SuppCore\AdministrativoBackend\Repository\StatusBarramentoRepository;
use SuppCore\AdministrativoBackend\Security\RolesService;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use SuppCore\AdministrativoBackend\Entity\StatusBarramento;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Throwable;

/**
 * Class SincronizaBarramentoMessageHandler.
 */
#[AsMessageHandler]
class SincronizaBarramentoMessageHandler
{
    /**
     * @param BlameableListener $blameableListener
     * @param ProcessoResource $processoResource
     * @param UsuarioResource $usuarioResource
     * @param ModalidadeNotificacaoResource $modalidadeNotificacaoResource
     * @param TipoNotificacaoResource $tipoNotificacaoResource
     * @param ParameterBagInterface $parameterBag
     * @param Logger $logger
     * @param TransactionManager $transactionManager
     * @param TokenStorageInterface $tokenStorage
     * @param RolesService $rolesService
     * @param NotificacaoResource $notificacaoResource
     * @param StatusBarramentoRepository $statusBarramentoRepository
     * @param StatusBarramentoResource $statusBarramentoResource
     * @param BarramentoSolicitacao $barramentoSolicitacao
     */
    public function __construct(
        private readonly BlameableListener $blameableListener,
        private ProcessoResource $processoResource,
        private UsuarioResource $usuarioResource,
        private ModalidadeNotificacaoResource $modalidadeNotificacaoResource,
        private TipoNotificacaoResource $tipoNotificacaoResource,
        private ParameterBagInterface $parameterBag,
        private Logger $logger,
        private TransactionManager $transactionManager,
        private TokenStorageInterface $tokenStorage,
        private RolesService $rolesService,
        private NotificacaoResource $notificacaoResource,
        private StatusBarramentoRepository $statusBarramentoRepository,
        private BarramentoRecebeComponenteDigital $barramentoRecebeComponenteDigital
    ) {
    }

    public function __invoke(SincronizaBarramentoMessage $message)
    {
        try {
            $processo = $this->processoResource->findOneBy(['id' => $message->getIdProcesso()]);
            $usuario = $this->usuarioResource->findOneBy(['username' => $message->getUsername()]);

            $this->blameableListener->setUserValue($usuario);

            $token = new PostAuthenticationToken(
                $usuario,
                'user_provider',
                $this->rolesService->getContextualRoles($usuario)
            );

            $token->setAttribute('username', $usuario->getUsername());
            $this->tokenStorage->setToken($token);

            $transactionId = $this->transactionManager->begin();
            /* @var $statusBarramentoEntity StatusBarramento */
            $statusBarramentoEntity = $this->statusBarramentoRepository->findUltimoTramite($processo);

            $modalidadeNotificacao = $this->modalidadeNotificacaoResource
                ->findOneBy(
                    ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]
                );
            $tipoNotificacao = $this->tipoNotificacaoResource->findOneBy(
                ['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_4')]
            );

            $contexto = json_encode(
                [
                    'id' => $processo->getId()
                ]
            );

            if (!$statusBarramentoEntity) {
                throw new Exception('Aguarde a finalização da sincronização anterior.');
            }

            $recebeComponenteDigital = $this->barramentoRecebeComponenteDigital->receberComponentesDigitais(
                $statusBarramentoEntity->getIdt(),
                $transactionId
            );

            if ($recebeComponenteDigital) {
                $this->logger->info('Processo NUP: '.$processo->getNUP().' SINCRONIZADO COM SUCESSO');
                $notificacaoDTO = (new NotificacaoDTO())
                    ->setDestinatario($usuario)
                    ->setModalidadeNotificacao($modalidadeNotificacao)
                    ->setConteudo('NUP ['.$processo->getNUP().'] SINCRONIZADO COM SUCESSO. ATUALIZE AS JUNTADAS.')
                    ->setTipoNotificacao($tipoNotificacao)
                    ->setContexto($contexto);
            } else {
                throw new Exception('Não é possível a sincronização do processo: '.$processo->getNUP());
            }

        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage().' - '.$e->getTraceAsString());
            $notificacaoDTO = (new NotificacaoDTO())
                ->setDestinatario($usuario)
                ->setModalidadeNotificacao($modalidadeNotificacao)
                ->setConteudo('ERRO AO SINCRONIZAR O NUP: '.$processo->getNUP().' COM O BARRAMENTO.')
                ->setTipoNotificacao(null);
        }
        $this->notificacaoResource->create($notificacaoDTO, $transactionId);
        $this->transactionManager->commit($transactionId);
    }
}
