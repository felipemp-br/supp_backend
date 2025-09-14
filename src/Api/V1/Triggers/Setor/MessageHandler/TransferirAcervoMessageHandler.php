<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Setor/MessageHandler/TransferirAcervoMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Setor\MessageHandler;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao as NotificacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Setor\Message\TransferirAcervoMessage;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class TransferirAcervoMessageHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class TransferirAcervoMessageHandler
{
    // DEFININDO O MÁXIMO DE PROCESSOS A SEREM PEGOS DE UMA VEZ SÓ, PARA IMPEDIR POSSÍVEIS ESTOUROS DE MEMÓRIA
    public const MAX_LIMIT = 40000;

    public function __construct(
        private SetorResource $setorResource,
        private ProcessoResource $processoResource,
        private UsuarioResource $usuarioResource,
        private ModalidadeNotificacaoResource $modalidadeNotificacaoResource,
        private TipoNotificacaoResource $tipoNotificacaoResource,
        private TransactionManager $transactionManager,
        private LoggerInterface $logger,
        private ParameterBagInterface $parameterBag,
        private NotificacaoResource $notificacaoResource
    ) {
    }

    /**
     * @param TransferirAcervoMessage $message
     */
    public function __invoke(TransferirAcervoMessage $message)
    {
        $transactionId = $this->transactionManager->begin();

        $processosSetor = $this->processoResource->getRepository()->findBy(
            ['setorAtual' => $message->getSetorOrigemId()],
            ['id' => 'DESC'],
            self::MAX_LIMIT
        );
        $setorDestino = $this->setorResource->getRepository()->findOneBy(['id' => $message->getSetorDestinoId()]);

        $count = 0;

        foreach ($processosSetor as $processo) {
            try {
                /** @var Processo $processoDTO */
                $processoDTO = $this->processoResource->getDtoForEntity(
                    $processo->getId(),
                    Processo::class,
                    null,
                    $processo
                );
                $processoDTO->setSetorAtual($setorDestino);

                $this->transactionManager->addContext(
                    new Context(
                        'transferirAcervo',
                        true
                    ),
                    $transactionId
                );

                $this->processoResource->update(
                    $processo->getId(),
                    $processoDTO,
                    $transactionId
                );

                $this->transactionManager->removeContext(
                    'transferirAcervo',
                    $transactionId
                );

                ++$count;
                if ($count > 1000) {
                    $this->transactionManager->commit($transactionId);

                    $transactionId = $this->transactionManager->begin();
                    $count = 0;
                }
            } catch (Throwable $e) {
                $this->logger->critical($e->getMessage().' - '.$e->getTraceAsString());
                continue;
            }
        }

        $msg = 'ACERVO DO SETOR ID '.$message->getSetorOrigemId().' TRANSFERIDO COM SUCESSO.';

        $modalidadeNotificacao = $this->modalidadeNotificacaoResource
            ->findOneBy(
                ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]
            );
        $tipoNotificacao = $this->tipoNotificacaoResource->findOneBy(
            ['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_5')]
        );

        $notificacaoDTO = (new NotificacaoDTO())
            ->setDestinatario($this->usuarioResource->findOne($message->getUserId()))
            ->setModalidadeNotificacao($modalidadeNotificacao)
            ->setConteudo($msg)
            ->setTipoNotificacao($tipoNotificacao);

        $this->notificacaoResource->create($notificacaoDTO, $transactionId);
        $this->transactionManager->commit($transactionId);
    }
}
