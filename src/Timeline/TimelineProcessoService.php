<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Timeline;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping\MappingException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade as AtividadeDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso as DocumentoAvulsoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Entity\Distribuicao;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Mapper\MapperManager;
use SuppCore\AdministrativoBackend\QueryBuilder\ArrayQueryBuilder;

/**
 * TimelineProcessoService.
 */
class TimelineProcessoService
{
    protected const POPULATE_TAREFA = [
        'especieTarefa',
        'usuarioResponsavel',
        'setorResponsavel',
        'setorResponsavel.unidade',
        'setorOrigem',
        'setorOrigem.unidade',
        'especieTarefa.generoTarefa',
        'vinculacaoWorkflow',
        'vinculacaoWorkflow.workflow',
        'vinculacoesEtiquetas',
        'vinculacoesEtiquetas.etiqueta',
        'criadoPor',
    ];

    protected const POPULATE_USUARIO = ['imgPerfil'];

    public function __construct(
        private readonly MapperManager $mapperManager,
        private readonly ArrayQueryBuilder $arrayQueryBuilder
    ) {
    }

    /**
     * @return TimelineEvent[]
     *
     * @throws ReflectionException
     * @throws MappingException
     */
    public function getTimelineProcesso(Processo $processo, array $criteria): array
    {
        $events = [];

        if (!isset($criteria['processo.id'])) {
            $criteria['processo.id'] = 'eq:'.$processo->getId();
        }
        $qb = $this->arrayQueryBuilder
            ->buildQueryBuilder(
                [
                    'object' => Tarefa::class,
                    'filter' => $criteria,
                    'sort' => [
                        'dataHoraConclusaoPrazo' => 'ASC',
                        'id' => 'ASC',
                    ],
                ]
            )
            ->setMaxResults(null);

        $tarefas = $qb->getQuery()->getResult();

        foreach ($tarefas as $tarefa) {
            $eventsTarefa = [];
            $this->processTarefaDistribuicoesEvents($tarefa, $eventsTarefa);
            $this->processTarefaAtividadesEvents($tarefa, $eventsTarefa);
            $this->processTarefaDocumentosAvulsoEvents($tarefa, $eventsTarefa);
            $this->processTarefaEvents($tarefa, $eventsTarefa);

            uasort(
                $eventsTarefa,
                fn ($eventA, $eventB) => $eventA->getEventDate()->getTimestamp() - $eventB->getEventDate()->getTimestamp()
            );

            // Setting first and last events
            $eventsTarefa[array_key_first($eventsTarefa)]->setFirstEvent(true);
            $eventsTarefa[array_key_last($eventsTarefa)]->setLastEvent(true);

            $events = array_merge($events, $eventsTarefa);
        }

        uasort(
            $events,
            fn ($eventA, $eventB) => $eventA->getEventDate()->getTimestamp() - $eventB->getEventDate()->getTimestamp()
        );

        return $events;
    }

    /**
     * @throws ReflectionException
     */
    protected function processTarefaDistribuicoesEvents(EntityInterface $tarefa, array &$events): void
    {
        $tarefaDTO = $this->mapperManager->getMapper(TarefaDTO::class)
            ->convertEntityToDto($tarefa, TarefaDTO::class, self::POPULATE_TAREFA);

        foreach ($tarefa->getDistribuicoes() as $distribuicao) {
            $usuarioDTO = $this->mapperManager->getMapper(UsuarioDTO::class)
                ->convertEntityToDto(
                    $distribuicao->getUsuarioPosterior(),
                    UsuarioDTO::class,
                    self::POPULATE_USUARIO
                );

            /**
             * @var Distribuicao $distribuicao
             */
            if (null === $distribuicao->getUsuarioAnterior()) {
                $events[] = (new TimelineEvent())
                    ->setMessage(
                        sprintf(
                            'Tarefa %s criada por %s para %s',
                            $tarefa->getId(),
                            $this->formataNomeUsuario($tarefa->getCriadoPor(), $distribuicao->getSetorPosterior()),
                            $this->formataNomeUsuario(
                                $distribuicao->getUsuarioPosterior(),
                                $distribuicao->getSetorPosterior()
                            )
                        )
                    )
                    ->setEventDate($distribuicao->getDataHoraDistribuicao())
                    ->setUsuario($usuarioDTO)
                    ->setTypeEvent(
                        (new EventType())
                            ->setObjectClass(TarefaDTO::class)
                            ->setObjectId($tarefa->getId())
                            ->setName('Criação de Tarefa')
                            ->setAction('tarefa_criada')
                    )
                    ->setTarefa($tarefaDTO);
            }

            if (null !== $distribuicao->getUsuarioAnterior()) {
                $events[] = (new TimelineEvent())
                    ->setMessage(
                        sprintf(
                            'Tarefa %s redistribuída de %s para %s',
                            $tarefa->getId(),
                            $this->formataNomeUsuario(
                                $distribuicao->getUsuarioAnterior(),
                                $distribuicao->getSetorAnterior()
                            ),
                            $this->formataNomeUsuario(
                                $distribuicao->getUsuarioPosterior(),
                                $distribuicao->getSetorPosterior()
                            )
                        )
                    )
                    ->setEventDate($distribuicao->getDataHoraDistribuicao())
                    ->setUsuario($usuarioDTO)
                    ->setTypeEvent(
                        (new EventType())
                            ->setObjectClass(TarefaDTO::class)
                            ->setObjectId($tarefa->getId())
                            ->setName('Redistribuição de Tarefa')
                            ->setAction('tarefa_redistribuida')
                    )
                    ->setObjectContext(
                        json_encode(
                            [
                                'usuarioAnterior' => $this->formataNomeUsuario(
                                    $distribuicao->getUsuarioAnterior(),
                                    $distribuicao->getSetorAnterior()
                                ),
                                'usuarioPosterior' => $this->formataNomeUsuario(
                                    $distribuicao->getUsuarioPosterior(),
                                    $distribuicao->getSetorPosterior()
                                ),
                            ]
                        )
                    )
                    ->setTarefa($tarefaDTO);
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    protected function processTarefaAtividadesEvents(EntityInterface $tarefa, array &$events): void
    {
        $tarefaDTO = $this->mapperManager->getMapper(TarefaDTO::class)
            ->convertEntityToDto($tarefa, TarefaDTO::class, self::POPULATE_TAREFA);

        foreach ($tarefa->getAtividades() as $atividade) {
            $temJuntada = !$atividade->getJuntadas()->isEmpty();
            $context = [
                'encerraTarefa' => $atividade->getEncerraTarefa(),
            ];

            if ($temJuntada) {
                $context['juntadas'] = $atividade->getJuntadas()
                    ->map(
                        fn (Juntada $juntada) => [
                            'numeroUnicoDocumento' => $juntada->getDocumento()->getNumeroUnicoDocumento()?->geraNumeroUnico(),
                            'nomeTipoDocumentoVinculado' => $juntada->getDocumento()->getTipoDocumento()->getNome(),
                            'documentoId' => $juntada->getDocumento()->getId(),
                            'siglaSetorOrigem' => $juntada->getDocumento()?->getSetorOrigem()?->getSigla(),
                            'unidadeSetorOrigem' => $juntada->getDocumento()?->getSetorOrigem()?->getUnidade()?->getSigla(),
                        ]
                    )->toArray();
            }
            $events[] = (new TimelineEvent())
                ->setMessage(
                    sprintf(
                        'Atividade %s lançada%s por %s',
                        $atividade->getEspecieAtividade()->getNome(),
                        $temJuntada ? ' com juntada' : '',
                        $this->formataNomeUsuario($atividade->getUsuario(), $atividade->getSetor())
                    )
                )
                ->setEventDate($atividade->getCriadoEm())
                ->setUsuario(
                    $this->mapperManager->getMapper(UsuarioDTO::class)
                        ->convertEntityToDto($atividade->getUsuario(), UsuarioDTO::class, self::POPULATE_USUARIO)
                )
                ->setTypeEvent(
                    (new EventType())
                        ->setObjectClass(AtividadeDTO::class)
                        ->setObjectId($atividade->getId())
                        ->setName('Lançamento de Atividade')
                        ->setAction('atividade_lancamento')
                )
                ->setObjectContext(json_encode($context))
                ->setTarefa($tarefaDTO);
        }
    }

    /**
     * @throws ReflectionException
     */
    protected function processTarefaDocumentosAvulsoEvents(EntityInterface $tarefa, array &$events): void
    {
        $tarefaDTO = $this->mapperManager->getMapper(TarefaDTO::class)
            ->convertEntityToDto($tarefa, TarefaDTO::class, self::POPULATE_TAREFA);

        foreach ($tarefa->getDocumentosAvulsos() as $documento) {
            $usuarioDTO = $this->mapperManager->getMapper(UsuarioDTO::class)
                ->convertEntityToDto($documento->getCriadoPor(), UsuarioDTO::class, self::POPULATE_USUARIO);

            if ($documento->getDataHoraRemessa()) {
                $events[] = (new TimelineEvent())
                    ->setMessage(sprintf('Ofício %s remetido', $documento->getEspecieDocumentoAvulso()->getNome()))
                    ->setEventDate($documento->getDataHoraRemessa())
                    ->setUsuario($usuarioDTO)
                    ->setTypeEvent(
                        (new EventType())
                            ->setObjectClass(DocumentoAvulsoDTO::class)
                            ->setObjectId($documento->getId())
                            ->setName('Ofício Remetido')
                            ->setAction('oficio_remetido')
                    )
                    ->setTarefa($tarefaDTO);
            }

            if ($documento->getDataHoraResposta()) {
                $events[] = (new TimelineEvent())
                    ->setMessage(
                        sprintf(
                            'Ofício %s respondido por %s',
                            $documento->getEspecieDocumentoAvulso()->getNome(),
                            $this->formataNomeUsuario(
                                $documento->getUsuarioResposta(),
                                $documento->getSetorResponsavel()
                            )
                        )
                    )
                    ->setEventDate($documento->getDataHoraResposta())
                    ->setUsuario($usuarioDTO)
                    ->setTypeEvent(
                        (new EventType())
                            ->setObjectClass(DocumentoAvulsoDTO::class)
                            ->setObjectId($documento->getId())
                            ->setName('Ofício Respondido')
                            ->setAction('oficio_respondido')
                    )
                    ->setTarefa($tarefaDTO);
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    protected function processTarefaEvents(EntityInterface $tarefa, array &$events): void
    {
        $tarefaDTO = $this->mapperManager->getMapper(TarefaDTO::class)
            ->convertEntityToDto($tarefa, TarefaDTO::class, self::POPULATE_TAREFA);

        $usuarioDTO = $this->mapperManager->getMapper(UsuarioDTO::class)
            ->convertEntityToDto($tarefa->getUsuarioResponsavel(), UsuarioDTO::class, self::POPULATE_USUARIO);

        $dateNow = new Carbon();
        $dateCriado = new Carbon($tarefa->getCriadoEm());
        if (!$tarefa->getDataHoraConclusaoPrazo()
            && !($dateCriado->isSameWeek($dateNow)
            && $dateCriado->isSameMonth($dateNow)
            && $dateCriado->isSameYear($dateNow))
        ) {
            $today = new DateTime();

            $events[] = (new TimelineEvent())
                ->setMessage(
                    sprintf(
                        'Tarefa %s em andamento com o usuário %s',
                        $tarefa->getId(),
                        $this->formataNomeUsuario($tarefa->getUsuarioResponsavel(), $tarefa->getSetorResponsavel())
                    )
                )
                ->setEventDate($today)
                ->setUsuario($usuarioDTO)
                ->setTypeEvent(
                    (new EventType())
                        ->setObjectClass(TarefaDTO::class)
                        ->setObjectId($tarefa->getId())
                        ->setName('Tarefa em Andamento')
                        ->setAction('tarefa_em_andamento')
                )
                ->setTarefa($tarefaDTO);
        }

        if ($tarefa->getDataHoraConclusaoPrazo()) {
            $events[] = (new TimelineEvent())
                ->setMessage(sprintf('Tarefa %s encerrada', $tarefa->getId()))
                ->setEventDate($tarefa->getDataHoraConclusaoPrazo())
                ->setUsuario($usuarioDTO)
                ->setTypeEvent(
                    (new EventType())
                        ->setObjectClass(TarefaDTO::class)
                        ->setObjectId($tarefa->getId())
                        ->setName('Encerramento de Tarefa')
                        ->setAction('tarefa_encerrada')
                )
                ->setTarefa($tarefaDTO);
        }
    }

    protected function formataNomeUsuario(?EntityInterface $usuario, ?EntityInterface $setor): string
    {
        $nome = 'SISTEMA';
        $sufixo = '';

        if (null !== $usuario) {
            $nome = $usuario->getNome();
        }

        if (null !== $setor) {
            $sufixo = ' ('.$setor->getSigla().'/'.$setor->getUnidade()->getSigla().')';
        }

        return $nome.$sufixo;
    }
}
