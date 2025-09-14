<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Desentranhamento/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Desentranhamento;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoNotificacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Desentranhamento as DesentranhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\TipoNotificacao as TipoNotificacaoEntity;
use SuppCore\AdministrativoBackend\Repository\ModalidadeNotificacaoRepository;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0003.
 *
 * @descSwagger=Gera uma notificação para quem possuir uma tarefa aberta quando o documento é desentranhado!
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    private TarefaRepository $tarefaRepository;

    private ModalidadeNotificacaoRepository $modalidadeNotificacaoRepository;

    private NotificacaoResource $notificacaoResource;

    private TipoNotificacaoResource $tipoNotificacaoResource;

    /**
     * Trigger0003 constructor.
     */
    public function __construct(
        NotificacaoResource $notificacaoResource,
        TarefaRepository $tarefaRepository,
        ModalidadeNotificacaoRepository $modalidadeNotificacaoRepository,
        TipoNotificacaoResource $tipoNotificacaoResource,
        private ParameterBagInterface $parameterBag
    ) {
        $this->notificacaoResource = $notificacaoResource;
        $this->modalidadeNotificacaoRepository = $modalidadeNotificacaoRepository;
        $this->tarefaRepository = $tarefaRepository;
        $this->tipoNotificacaoResource = $tipoNotificacaoResource;
    }

    public function supports(): array
    {
        return [
            Desentranhamento::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Desentranhamento|RestDtoInterface|null $restDto
     * @param DesentranhamentoEntity|EntityInterface $entity
     *
     * @throws ORMException            11 988782728
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // Usuários que já foram notificados
        $alreadySent = [];

        //Apesar de ser uma notificação disparada pela Tarefa, o usuário quer visualizar o processo em que foi desentranhado
        $tipoNotificacao = $this->tipoNotificacaoResource
        ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_1')]);

        $modalidadeNotificacao = $this->modalidadeNotificacaoRepository
        ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]);

        /** @var Tarefa $tarefaAberta */
        foreach ($this->tarefaRepository->findAbertaByProcessoId(
            $restDto->getJuntada()->getVolume()->getProcesso()->getId()
        ) as $tarefaAberta) {           

            // Pula a notificação para o usuário que já foi notificado
            if(in_array($tarefaAberta->getUsuarioResponsavel()->getId(), $alreadySent) ) {
                continue;
            }
            
            $notificacaoDTO = new Notificacao();
            $contexto = json_encode([
                'id' => $tarefaAberta->getProcesso()->getId(),
                'id_tarefa' => $tarefaAberta->getId(),
            ]);
            $notificacaoDTO->setDestinatario($tarefaAberta->getUsuarioResponsavel());
            $notificacaoDTO->setModalidadeNotificacao($modalidadeNotificacao);
            $notificacaoDTO->setTipoNotificacao($tipoNotificacao);
            $notificacaoDTO->setContexto($contexto);
            $notificacaoDTO->setConteudo(
                'HOUVE UM DESENTRANHAMENTO NO NUP '.
                $restDto->getJuntada()->getVolume()->getProcesso()->getNUPFormatado().
                ', NO QUAL VOCÊ POSSUI UMA TAREFA ABERTA!'
            );

            $this->notificacaoResource->create($notificacaoDTO, $transactionId);

            // Adiciona o usuário que foi notificado em um processo
            $alreadySent[] = $tarefaAberta->getUsuarioResponsavel()->getId();
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
