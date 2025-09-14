<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Transicao/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Transicao;

use DateInterval;
use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Transicao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Na transição de transferencia/recolhimento, encaminha o processo para o arquivo intermediário/definitivo da instituição!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private ProcessoResource $processoResource;

    private TarefaResource $tarefaResource;

    private SetorResource $setorResource;

    private ParameterBagInterface $parameterBag;

    private EspecieTarefaResource $especieTarefaResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        ProcessoResource $processoResource,
        TarefaResource $tarefaResource,
        SetorResource $setorResource,
        EspecieTarefaResource $especieTarefaResource,
        ParameterBagInterface $parameterBag
    ) {
        $this->processoResource = $processoResource;
        $this->tarefaResource = $tarefaResource;
        $this->especieTarefaResource = $especieTarefaResource;
        $this->setorResource = $setorResource;
        $this->parameterBag = $parameterBag;
    }

    public function supports(): array
    {
        return [
            Transicao::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Transicao|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // trigger desabilitada para estudos
        return;

        $siglaUnidade = false;

        if ('TRANSFERÊNCIA' === $restDto->getModalidadeTransicao()->getValor()) {
            $siglaUnidade = $this->parameterBag->get(
                'supp_core.administrativo_backend.sigla_unidade_arquivo_intermediario_instituicao'
            );
            $especieTarefa = $this->especieTarefaResource->findOneBy(
                [
                    'nome' => $this->parameterBag->get('constantes.entidades.especie_tarefa.const_1'),
                ]
            );
        }

        if ('RECOLHIMENTO' === $restDto->getModalidadeTransicao()->getValor()) {
            $siglaUnidade = $this->parameterBag->get(
                'supp_core.administrativo_backend.sigla_unidade_arquivo_definitivo_instituicao'
            );
            $especieTarefa = $this->especieTarefaResource->findOneBy(
                [
                    'nome' => $this->parameterBag->get('constantes.entidades.especie_tarefa.const_7'),
                ]
            );
        }

        if ('ELIMINAÇÃO' === $restDto->getModalidadeTransicao()->getValor()) {
            $siglaUnidade = $this->parameterBag->get(
                'supp_core.administrativo_backend.sigla_unidade_arquivo_definitivo_instituicao'
            );
            $especieTarefa = $this->especieTarefaResource->findOneBy(
                [
                    'nome' => $this->parameterBag->get('constantes.entidades.especie_tarefa.const_6'),
                ]
            );
        }

        if (!$siglaUnidade) {
            return;
        }

        $arquivo = $this->setorResource->getRepository()->findArquivoInUnidadeBySigla($siglaUnidade);

        if ($arquivo) {
            $processoDTO = new Processo();
            $processoDTO->setSetorAtual($arquivo);
            $this->processoResource->update($restDto->getProcesso()->getId(), $processoDTO, $transactionId);

            $inicioPrazo = new DateTime();
            $finalPrazo = new DateTime();
            $finalPrazo->add(new DateInterval('P5D'));
            $tarefaDTO = new Tarefa();
            $tarefaDTO->setProcesso($restDto->getProcesso());
            $tarefaDTO->setEspecieTarefa($especieTarefa);
            $tarefaDTO->setSetorResponsavel($arquivo);
            $tarefaDTO->setDataHoraInicioPrazo($inicioPrazo);
            $tarefaDTO->setDataHoraFinalPrazo($finalPrazo);

            $this->tarefaResource->create($tarefaDTO, $transactionId);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
