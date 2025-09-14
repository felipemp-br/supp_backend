<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0027.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoNotificacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\ModalidadeNotificacaoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0027.
 *
 * @descSwagger=Faz uma notificação para quem criou a tarefa!
 * @classeSwagger=Trigger0027
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0027 implements TriggerInterface
{
    /**
     * Trigger0027 constructor.
     */
    public function __construct(
        private NotificacaoResource $notificacaoResource,
        private ModalidadeNotificacaoRepository $modalidadeNotificacaoRepository,
        private TipoNotificacaoResource $tipoNotificacaoResource,
        private ParameterBagInterface $parameterBag,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function supports(): array
    {
        return [
            TarefaEntity::class => [
                'afterDelete',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param TarefaEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        /** @var Usuario $usuario */
        $usuario = $this->tokenStorage->getToken()->getUser();
        if ($entity->getCriadoPor() &&
            ($entity->getCriadoPor()->getId() !== $usuario->getId())) {
            $notificacaoDTO = new Notificacao();

            $modalidadeNotificacao = $this->modalidadeNotificacaoRepository
                ->findOneBy([
                    'valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1'),
                ]);
            $tipoNotificacao = $this->tipoNotificacaoResource
                ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_1')]);
            $contexto = json_encode(['id' => $entity->getProcesso()->getId()]);

            $notificacaoDTO->setDestinatario($entity->getCriadoPor());
            $notificacaoDTO->setModalidadeNotificacao($modalidadeNotificacao);
            $notificacaoDTO->setTipoNotificacao($tipoNotificacao);
            $notificacaoDTO->setContexto($contexto);
            $notificacaoDTO->setConteudo(
                'TAREFA (ID '.
                $entity->getId().') NO NUP '.
                $entity->getProcesso()->getNUPFormatado().' EXCLUÍDA POR OUTRO USUÁRIO!'
            );

            $this->notificacaoResource->create($notificacaoDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1000;
    }
}
