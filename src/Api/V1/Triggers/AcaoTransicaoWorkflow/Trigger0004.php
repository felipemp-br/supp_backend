<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\AcaoTransicaoWorkflow;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\VarExporter\LazyObjectInterface;
use function json_decode;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\AcaoTransicaoWorkflowRepository;
use SuppCore\AdministrativoBackend\Repository\EspecieDocumentoAvulsoRepository;
use SuppCore\AdministrativoBackend\Repository\ModeloRepository;
use SuppCore\AdministrativoBackend\Repository\PessoaRepository;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Gera automaticamente oficio (documento avulso) de acordo com a tarefa.
 * @classeSwagger=Trigger0004
 */
class Trigger0004 implements TriggerInterface
{

    /**
     * Trigger0004 constructor.
     */
    public function __construct(private AcaoTransicaoWorkflowRepository $acaoTransicaoWorkflowRepository,
                                private DocumentoAvulsoResource $documentoAvulsoResource,
                                private EspecieDocumentoAvulsoRepository $especieDocumentoAvulsoRepository,
                                private ModeloRepository $modeloRepository,
                                private SetorRepository $setorRepository,
                                private PessoaRepository $pessoaRepository
    ) {
    }

    public function supports(): array
    {
        return [
            TarefaDTO::class => [
                'afterCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param TarefaDTO|RestDtoInterface|null $tarefaDTO
     * @param EntityInterface|TarefaEntity $tarefaEntity
     * @param string $transactionId
     * @return void
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(
        TarefaDTO|RestDtoInterface|null $tarefaDTO,
        EntityInterface|TarefaEntity $tarefaEntity,
        string $transactionId
    ): void {
        if (!$tarefaDTO->getVinculacaoWorkflow()?->getId()) {
            return;
        }

        $result = $this->acaoTransicaoWorkflowRepository
            ->getAcaoTransicaoEspecieTarefaTo(
                $tarefaDTO->getVinculacaoWorkflow()->getTarefaAtual(),
                $tarefaDTO->getEspecieTarefa(),
                $this instanceof LazyObjectInterface ? get_parent_class($this) : get_class($this)
            );

        foreach ($result as $acaoTransicaoWorkflow) {
            $contexto = json_decode($acaoTransicaoWorkflow->getContexto(), true);

            $documentoAvulsoDTO = (new DocumentoAvulso())
                ->setProcesso($tarefaDTO->getProcesso())
                ->setEspecieDocumentoAvulso(
                    $this->especieDocumentoAvulsoRepository->find($contexto['especieDocumentoAvulsoId'])
                )
                ->setTarefaOrigem($tarefaEntity)
                ->setModelo($this->modeloRepository->find($contexto['modeloId']));

            if (isset($contexto['pessoaDestinoId'])) {
                $documentoAvulsoDTO->setPessoaDestino($this->pessoaRepository->find($contexto['pessoaDestinoId']));
            }

            if (isset($contexto['setorDestinoId'])) {
                $documentoAvulsoDTO->setSetorDestino($this->setorRepository->find($contexto['setorDestinoId']));
            }

            if (isset($contexto['observacao'])) {
                $documentoAvulsoDTO->setObservacao($contexto['observacao']);
            }

            $dataAtual = new DateTime();
            $documentoAvulsoDTO->setDataHoraInicioPrazo($dataAtual);

            if ($contexto['prazoFinal']) {
                $dias = $contexto['prazoFinal'];
                $documentoAvulsoDTO->setDataHoraFinalPrazo($dataAtual->modify("+$dias days"));
            }

            if (isset($contexto['blocoResponsaveis'])) {
                foreach ($contexto['blocoResponsaveis'] as $responsavel) {
                    if ('setor' == $responsavel['tipo']) {
                        $documentoAvulsoDTO->setSetorDestino($this->setorRepository->find($responsavel['id']));
                    }

                    if ('pessoa' == $responsavel['tipo']) {
                        $documentoAvulsoDTO->setPessoaDestino($this->pessoaRepository->find($responsavel['id']));
                    }

                    $this->documentoAvulsoResource->create($documentoAvulsoDTO, $transactionId);
                }
            } else {
                $this->documentoAvulsoResource->create($documentoAvulsoDTO, $transactionId);
            }
        }
    }

    public function getOrder(): int
    {
        return 4;
    }
}
