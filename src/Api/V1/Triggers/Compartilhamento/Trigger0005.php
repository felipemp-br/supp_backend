<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Compartilhamento/Trigger0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Compartilhamento;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoNotificacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Compartilhamento as CompartilhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\TipoNotificacao as TipoNotificacaoEntity;
use SuppCore\AdministrativoBackend\Repository\ModalidadeNotificacaoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Gera uma notificação quando o documento avulso é respondido!
 * @classeSwagger=Trigger0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005 implements TriggerInterface
{
    private ModalidadeNotificacaoRepository $modalidadeNotificacaoRepository;
    private NotificacaoResource $notificacaoResource;
    private TipoNotificacaoResource $tipoNotificacaoResource;

    /**
     * Trigger0005 constructor.
     */
    public function __construct(
        NotificacaoResource $notificacaoResource,
        ModalidadeNotificacaoRepository $modalidadeNotificacaoRepository,
        TipoNotificacaoResource $tipoNotificacaoResource,
        private ParameterBagInterface $parameterBag
    ) {
        $this->notificacaoResource = $notificacaoResource;
        $this->modalidadeNotificacaoRepository = $modalidadeNotificacaoRepository;
        $this->tipoNotificacaoResource = $tipoNotificacaoResource;
    }

    public function supports(): array
    {
        return [
            Compartilhamento::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Compartilhamento|RestDtoInterface|null $restDto
     * @param CompartilhamentoEntity|EntityInterface $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (!$restDto->getTarefa()) {
            return;
        }

        $notificacaoDTO = new Notificacao();

        $modalidadeNotificacao = $this->modalidadeNotificacaoRepository
            ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]);
        $tipoNotificacao = $this->tipoNotificacaoResource
            ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_1')]);
        $contexto = json_encode(['id' => $restDto->getTarefa()->getProcesso()->getId()]);

        $notificacaoDTO->setDestinatario($restDto->getUsuario());
        $notificacaoDTO->setModalidadeNotificacao($modalidadeNotificacao);
        $notificacaoDTO->setTipoNotificacao($tipoNotificacao);
        $notificacaoDTO->setContexto($contexto);
        $notificacaoDTO->setConteudo(
            'TAREFA COMPARTILHADA (ID '.
            $restDto->getTarefa()->getId().') NO NUP '.
            $restDto->getTarefa()->getProcesso()->getNUPFormatado().'!'
        );

        $this->notificacaoResource->create($notificacaoDTO, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
