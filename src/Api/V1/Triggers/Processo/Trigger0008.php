<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0008.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use DateInterval;
use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0008.
 *
 * @descSwagger=A tarefa do arquivamento do processo é aberta automaticamente!
 * @classeSwagger=Trigger0008
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0008 implements TriggerInterface
{
    private TarefaResource $tarefaResource;

    private EspecieTarefaResource $especieTarefaResource;

    private SetorRepository $setorRepository;

    private ProcessoResource $processoResource;

    private TokenStorageInterface $tokenStorage;

    private LotacaoRepository $lotacaoRepository;

    /**
     * Trigger0008 constructor.
     */
    public function __construct(
        TarefaResource $tarefaResource,
        ProcessoResource $processoResource,
        EspecieTarefaResource $especieTarefaResource,
        SetorRepository $setorRepository,
        TokenStorageInterface $tokenStorage,
        LotacaoRepository $lotacaoRepository,
        private ParameterBagInterface $parameterBag
    ) {
        $this->tarefaResource = $tarefaResource;
        $this->especieTarefaResource = $especieTarefaResource;
        $this->setorRepository = $setorRepository;
        $this->processoResource = $processoResource;
        $this->tokenStorage = $tokenStorage;
        $this->lotacaoRepository = $lotacaoRepository;
    }

    public function supports(): array
    {
        return [
            ProcessoEntity::class => [
                'afterArquivar',
            ],
        ];
    }

    /**
     * @param Processo|RestDtoInterface|null $restDto
     * @param ProcessoEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $arquivo = $this->setorRepository->findArquivoInUnidade($entity->getSetorAtual()->getUnidade()->getId());
        $inicioPrazo = new DateTime();
        $finalPrazo = new DateTime();
        $finalPrazo->add(new DateInterval('P5D'));
        $especieTarefa = $this->especieTarefaResource->findOneBy(
            [
                'nome' => $this->parameterBag->get('constantes.entidades.especie_tarefa.const_4'),
            ]
        );

        $tarefaDTO = new Tarefa();
        $tarefaDTO->setProcesso($entity);
        $tarefaDTO->setEspecieTarefa($especieTarefa);
        $tarefaDTO->setSetorResponsavel($arquivo);
        $tarefaDTO->setDataHoraInicioPrazo($inicioPrazo);
        $tarefaDTO->setDataHoraFinalPrazo($finalPrazo);

        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser() &&
            $this->tokenStorage->getToken()->getUser()->getColaborador()) {
            $lotacaoPrincipal = $this->lotacaoRepository->findLotacaoPrincipal(
                $this->tokenStorage->getToken()->getUser()->getColaborador()->getId()
            );

            if ($lotacaoPrincipal) {
                $tarefaDTO->setSetorOrigem($lotacaoPrincipal->getSetor());
            }
        }

        $this->tarefaResource->create($tarefaDTO, $transactionId);

        if ($this->parameterBag->get('constantes.entidades.modalidade_meio.const_2')
            !== $entity->getModalidadeMeio()->getValor()) {
            $processoDTO = $this->processoResource->getDtoForEntity(
                $entity->getId(),
                Processo::class
            );
            $processoDTO->setSetorAtual($arquivo);
            $this->processoResource->update($entity->getId(), $processoDTO, $transactionId, true);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
