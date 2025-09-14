<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Atividade/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Atividade;

use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\CompartilhamentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Atividade as AtividadeEntity;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Security\RolesService;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

/**
 * Class Trigger0004.
 *
 * @descSwagger  =Caso informados documentos no request que sejam para submeter à aprovação,
 * uma tarefa será criada e as minutas redistribuídas e cria compartilhamento para usuário remetente!
 *
 * @classeSwagger=Trigger0004
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        private readonly CompartilhamentoResource $compartilhamentoResource,
        private readonly DocumentoResource $documentoResource,
        private readonly EspecieTarefaResource $especieTarefaResource,
        private readonly EtiquetaRepository $etiquetaRepository,
        private readonly ParameterBagInterface $parameterBag,
        private readonly RolesService $rolesService,
        private readonly TarefaResource $tarefaResource,
        private readonly TransactionManager $transactionManager,
        private readonly VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
    ) {
    }

    public function supports(): array
    {
        return [
            Atividade::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Atividade|RestDtoInterface|null $restDto
     * @param AtividadeEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ('submeter_aprovacao' === $restDto->getDestinacaoMinutas()) {
            $this->transactionManager->addContext(
                new Context(
                    'atividadeAprovacao',
                    [
                        $entity->getUsuario()->getId(),
                        $entity->getTarefa()->getSetorResponsavel(),
                    ]
                ),
                $transactionId
            );

            $inicioPrazo = new DateTime();
            $finalPrazo = new DateTime();
            $finalPrazo->add(new DateInterval('P5D'));

            try {
                $nomeEspecieAtividade = $this->parameterBag->get(
                    'supp_core.administrativo_backend.especie_atividade_submeter_aprovacao_'.mb_strtolower(
                        $restDto->getTarefa()->getEspecieTarefa()->getGeneroTarefa()->getNome()
                    )
                );
            } catch (Throwable) {
                $nomeEspecieAtividade = $this->parameterBag->get(
                    'supp_core.administrativo_backend.especie_atividade_submeter_aprovacao_administrativo'
                );
            }

            $especieTarefa = $this->especieTarefaResource->findOneBy(
                [
                    'nome' => $nomeEspecieAtividade,
                ]
            );

            $tarefaDTO = new Tarefa();
            $tarefaDTO->setProcesso($restDto->getTarefa()->getProcesso());
            $tarefaDTO->setEspecieTarefa($especieTarefa);
            $tarefaDTO->setSetorResponsavel($restDto->getSetorAprovacao());
            $tarefaDTO->setUsuarioResponsavel($restDto->getUsuarioAprovacao());
            $tarefaDTO->setDataHoraInicioPrazo($inicioPrazo);
            $tarefaDTO->setDataHoraFinalPrazo($finalPrazo);
            $tarefaDTO->setObservacao($restDto->getObservacao());

            // Definido para fluxo de desaprovação
            $tarefaDTO->setSetorOrigem($restDto->getTarefa()->getSetorResponsavel());

            $tarefaAprovacao = $this->tarefaResource->create($tarefaDTO, $transactionId);

            $entity->setTarefaAprovacao($tarefaAprovacao);

            /** @var DocumentoEntity $documento */
            foreach ($restDto->getTarefa()->getMinutas() as $documento) {
                $documentosRestDTO = $restDto->getDocumentos() instanceof ArrayCollection ?
                    $restDto->getDocumentos() : new ArrayCollection($restDto->getDocumentos());
                if (!$documento->getJuntadaAtual()
                        && $documentosRestDTO
                            ->filter(fn ($doc) => $doc->getId() === $documento->getId())->count() > 0) {
                    $documentoDTO = new Documento();
                    $documentoDTO->setTarefaOrigem($tarefaAprovacao);

                    $documentoEntity = $this->documentoResource->update(
                        $documento->getId(),
                        $documentoDTO,
                        $transactionId
                    );

                    // remove etiqueta anterior
                    foreach ($restDto->getTarefa()->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                        if ($documento->getUuid() === $vinculacaoEtiqueta->getObjectUuid()
                            && DocumentoEntity::class === $vinculacaoEtiqueta->getObjectClass()) {
                            $this->vinculacaoEtiquetaResource->delete($vinculacaoEtiqueta->getId(), $transactionId);
                            break;
                        }
                    }

                    // coloca nova etiqueta
                    $vinculacaoEtiquetaDTO = new VinculacaoEtiqueta();
                    $vinculacaoEtiquetaDTO->setEtiqueta(
                        $this->etiquetaRepository->findOneBy(
                            [
                                'nome' => $this->parameterBag->get('constantes.entidades.etiqueta.const_1'),
                                'sistema' => true,
                            ]
                        )
                    );
                    $vinculacaoEtiquetaDTO->setTarefa($tarefaAprovacao);
                    $vinculacaoEtiquetaDTO->setObjectClass(get_class($documentoEntity));
                    $vinculacaoEtiquetaDTO->setObjectUuid($documentoEntity->getUuid());
                    $vinculacaoEtiquetaDTO->setLabel($documentoEntity->getTipoDocumento()->getSigla());
                    $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
                }
            }

            // apos a criacao da tarefa cria um compartilhamento com o remetente
            $compartilhamentoDTO = new Compartilhamento();
            $compartilhamentoDTO->setTarefa($tarefaAprovacao);
            $compartilhamentoDTO->setUsuario($restDto->getUsuario());

            $this->compartilhamentoResource->create($compartilhamentoDTO, $transactionId, true);

            // outros compartilhamentos no fluxo de aprovação
            $this->criaCompartilhamentoFluxo($restDto->getTarefa(), $tarefaAprovacao, $transactionId);

            $this->transactionManager->removeContext('atividadeAprovacao', $transactionId);
        }
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    private function criaCompartilhamentoFluxo(
        TarefaEntity $tarefaAnalise,
        TarefaEntity $tarefaAprovacao,
        string $transactionId
    ): void {
        // essa tarefa passou pelo fluxo de aprovação?
        if ($tarefaAnalise->getAtividadeAprovacao()) {
            // verifica se o remetente está ativo e se possui ROLE_COLABORADOR
            if (true === $tarefaAnalise->getAtividadeAprovacao()->getUsuario()->getEnabled()
                && in_array(
                    'ROLE_COLABORADOR',
                    $this->rolesService->getContextualRoles($tarefaAnalise->getAtividadeAprovacao()->getUsuario())
                )) {
                // apos a criacao da tarefa cria um compartilhamento com o remetente
                $compartilhamentoDTO = new Compartilhamento();
                $compartilhamentoDTO->setTarefa($tarefaAprovacao);
                $compartilhamentoDTO->setUsuario($tarefaAnalise->getAtividadeAprovacao()->getUsuario());

                $this->compartilhamentoResource->create($compartilhamentoDTO, $transactionId, true);
            }

            // vamos recursivamente
            $this->criaCompartilhamentoFluxo(
                $tarefaAnalise->getAtividadeAprovacao()->getTarefa(),
                $tarefaAprovacao,
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
