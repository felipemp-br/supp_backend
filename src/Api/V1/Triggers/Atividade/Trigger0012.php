<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Atividade/Trigger0012.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Atividade;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SolicitacaoAutomatizadaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SuppParameterBag;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0012.
 *
 * @descSwagger=Caso seja atividade de tarefa ligada a Solicitação Automatizada, executamos ações específicas.
 *
 * @classeSwagger=Trigger0012
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0012 implements TriggerInterface
{
    /**
     * @param SuppParameterBag                $parameterBag
     * @param SolicitacaoAutomatizadaResource $solicitacaoAutomatizadaResource
     */
    public function __construct(
        private readonly SuppParameterBag $parameterBag,
        private readonly SolicitacaoAutomatizadaResource $solicitacaoAutomatizadaResource
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
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     * @param string                $transactionId
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $solicitacaoAutomatizada = $this->solicitacaoAutomatizadaResource->findOneBy(
            ['processo' => $restDto->getTarefa()->getProcesso()]
        );

        if (!$solicitacaoAutomatizada) {
            return;
        }
        if (!$this->parameterBag->has('supp_core.administrativo_backend.solicitacao_automatizada')) {
            throw new Exception('Configuração para Solicitação Automatizada não encontrada.');
        }
        $config = $this->parameterBag->get('supp_core.administrativo_backend.solicitacao_automatizada');

        /** @var SolicitacaoAutomatizada $solicitacaoAutomatizadaDTO */
        $solicitacaoAutomatizadaDTO = $this->solicitacaoAutomatizadaResource->getDtoForEntity(
            $solicitacaoAutomatizada->getId(),
            $this->solicitacaoAutomatizadaResource->getDtoClass()
        );
        $nomeEspecieAtividade = $restDto->getEspecieAtividade()->getNome();
        $tarefaId = $restDto->getTarefa()->getId();
        $solicitacaoAtualizada = false;
        switch (true) {
            case $nomeEspecieAtividade === $config['especie_atividade_deferimento']
                && $tarefaId === $solicitacaoAutomatizada->getTarefaAnalise()?->getId():
                $solicitacaoAtualizada = true;
                $solicitacaoAutomatizadaDTO
                    ->setStatus(StatusSolicitacaoAutomatizada::DEFERIDO);
                if (!$solicitacaoAutomatizadaDTO->getSugestaoDeferimento()) {
                    $solicitacaoAutomatizadaDTO->setErroAnaliseSumaria(true);
                }
                break;
            case $nomeEspecieAtividade === $config['especie_atividade_indeferimento']
                && $tarefaId === $solicitacaoAutomatizada->getTarefaAnalise()?->getId():
                $solicitacaoAtualizada = true;
                $solicitacaoAutomatizadaDTO
                    ->setStatus(StatusSolicitacaoAutomatizada::INDEFERIDO);
                if ($solicitacaoAutomatizadaDTO->getSugestaoDeferimento()) {
                    $solicitacaoAutomatizadaDTO->setErroAnaliseSumaria(true);
                }
                break;
            case $solicitacaoAutomatizada->getStatus() === StatusSolicitacaoAutomatizada::ERRO_SOLICITACAO
                && $restDto->getTarefa()->getEspecieTarefa()->getNome() === $config['especie_tarefa_erro_solicitacao']
                && $tarefaId !== $solicitacaoAutomatizada->getTarefaDadosCumprimento()?->getId()
                && $tarefaId !== $solicitacaoAutomatizada->getTarefaAnalise()?->getId()
                && $nomeEspecieAtividade === $config['especie_atividade_erro_solicitacao']:
                $solicitacaoAtualizada = true;
                $solicitacaoAutomatizadaDTO->setStatus(StatusSolicitacaoAutomatizada::ANALISE_PROCURADOR);
                break;
        }

        $tipoDocumentoAutorizado = $config['tipo_documento'];
        $documentoSelecionado = null;
        if (!$restDto->getDocumentos()->isEmpty()) {
            /** @var Documento $documento */
            foreach ($restDto->getDocumentos() as $documento) {
                if ($documento->getTipoDocumento()->getSigla() === $tipoDocumentoAutorizado) {
                    $documentoSelecionado = $documento;
                }
            }
            if (!$documentoSelecionado) {
                throw new Exception('Tipo de Documento não permitido para Solicitação Automatizada.');
            }
            // Isto não faz sentido, reavaliar...
//            $solicitacaoAutomatizadaDTO->setResultadoSolicitacao($documentoSelecionado);
        }
        if ($solicitacaoAutomatizada) {
            $this->solicitacaoAutomatizadaResource->update(
                $solicitacaoAutomatizada->getId(),
                $solicitacaoAutomatizadaDTO,
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
