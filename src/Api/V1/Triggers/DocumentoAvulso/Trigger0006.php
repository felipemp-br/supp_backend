<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DocumentoAvulso/Trigger0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoAvulso;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoNotificacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\TipoNotificacao as TipoNotificacaoEntity;
use SuppCore\AdministrativoBackend\Repository\ModalidadeNotificacaoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0006.
 *
 * @descSwagger=Gera uma notificação quando o documento avulso é respondido!
 * @classeSwagger=Trigger0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0006 implements TriggerInterface
{
    private ModalidadeNotificacaoRepository $modalidadeNotificacaoRepository;
    private NotificacaoResource $notificacaoResource;
    private TipoNotificacaoResource $tipoNotificacaoResource;

    /**
     * Trigger0006 constructor.
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
            DocumentoAvulso::class => [
                'afterResponder',
            ],
        ];
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface|null $restDto
     * @param DocumentoAvulsoEntity|EntityInterface $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $notificacaoDTO = new Notificacao();

        $modalidadeNotificacao = $this->modalidadeNotificacaoRepository
            ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]);
        $tipoNotificacao = $this->tipoNotificacaoResource
            ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_1')]);
        $contexto = json_encode(['id' => $restDto->getProcesso()->getId()]);

        $notificacaoDTO->setDestinatario($restDto->getUsuarioResponsavel());
        $notificacaoDTO->setModalidadeNotificacao($modalidadeNotificacao);
        $notificacaoDTO->setTipoNotificacao($tipoNotificacao);
        $notificacaoDTO->setContexto($contexto);
        $notificacaoDTO->setConteudo(
            'RESPOSTA DE COMUNICAÇÃO JUNTADA (ID '.
            $entity->getId().') NO NUP '.
            $entity->getProcesso()->getNUPFormatado().'!'
        );

        $this->notificacaoResource->create($notificacaoDTO, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
