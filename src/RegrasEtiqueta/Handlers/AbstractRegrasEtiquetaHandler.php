<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Handlers;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\RegraEtiqueta;
use SuppCore\AdministrativoBackend\QueryBuilder\ArrayQueryBuilder;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Models\CriteriaAggregationTree;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Parsers\FieldParserInterface;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Message\RegrasEtiquetaMessage;
use SuppCore\AdministrativoBackend\Repository\RegraEtiquetaRepository;
use Throwable;
use Traversable;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class AbstractRegrasEtiquetaHandler
{
    protected const PAGINATION_LIMIT = 50;
    /**
     * @var FieldParserInterface[]
     */
    protected array $parsers = [];

    /**
     * Constructor.
     *
     * @param ArrayQueryBuilder                 $arrayQueryBuilder
     * @param RegraEtiquetaRepository           $regraEtiquetaRepository
     * @param LoggerInterface                   $logger
     * @param Traversable<FieldParserInterface> $parsers
     */
    public function __construct(
        private readonly ArrayQueryBuilder $arrayQueryBuilder,
        protected readonly RegraEtiquetaRepository $regraEtiquetaRepository,
        protected readonly LoggerInterface $logger,
        Traversable $parsers
    ) {
        $this->parsers = iterator_to_array($parsers);
    }

    /**
     * @param RegrasEtiquetaMessage $message
     * @param int                   $limit
     * @param int                   $offset
     *
     * @return Paginator
     */
    abstract protected function findRegrasEtiqueta(RegrasEtiquetaMessage $message, int $limit, int $offset): Paginator;

    /**
     * @param RegraEtiqueta         $regraEtiquetaEntity
     * @param RegrasEtiquetaMessage $message
     * @param string                $transactionId
     *
     * @return void
     */
    abstract protected function createVinculacaoEtiqueta(
        RegraEtiqueta $regraEtiquetaEntity,
        RegrasEtiquetaMessage $message,
        string $transactionId
    ): void;

    /**
     * @param RegrasEtiquetaMessage $message
     * @param string                $transactionId
     *
     * @return void
     */
    public function handle(RegrasEtiquetaMessage $message, string $transactionId): void
    {
        try {
            $this->logData(
                'info',
                'Iniciando handler de regra de etiqueta.',
                $message
            );
            $idsEtiquetasVinculadas = [];
            $offset = 0;
            $total = null;
            do {
                $paginator = $this->findRegrasEtiqueta($message, self::PAGINATION_LIMIT, $offset);
                if (is_null($total)) {$this->logData(
                    'info',
                    sprintf(
                        'Foram encontradas %s regras para verificação',
                        $paginator->count()
                    ),
                    $message
                );
                    $total = $paginator->count();
                }
                ++$offset;
                $hasNext = ($offset * self::PAGINATION_LIMIT) < $total;
                /* @var RegraEtiqueta $regraEtiquetaEntity */
                foreach ($paginator->getIterator() as $regraEtiquetaEntity) {
                    if (in_array($regraEtiquetaEntity->getEtiqueta()->getId(), $idsEtiquetasVinculadas)) {
                        continue;
                    }
                    $this->logData(
                        'info',
                        'Validando criteria da regra de etiqueta.',
                        $message,
                        [
                            'regraEtiquetaId' => $regraEtiquetaEntity->getId(),
                        ]
                    );
                    $criteriaAggregationTree = CriteriaAggregationTree::build(
                        str_replace("'", '"', $regraEtiquetaEntity->getCriteria())
                    );
                    $criteriaAggregationTree
                        ->parse($this->getProcesso(), $this->parsers)
                        ->simplify();

                    if (
                        $criteriaAggregationTree->isEmpty()
                        && false === $criteriaAggregationTree->getLastParsedValue()
                    ) {
                        $this->logData(
                            'info',
                            'O criteria possuí regras de IA que não passaram na validação.',
                            $message
                        );
                        continue;
                    }

                    if (!$criteriaAggregationTree->isEmpty()) {
                        $filter = [
                            'andX' => [
                                [
                                    'uuid' => sprintf(
                                        'eq:%s',
                                        $message->getEntityOrigemUuid()
                                    ),
                                ],
                                ...[$criteriaAggregationTree->toArray()],
                            ],
                        ];
                        $qb = $this->arrayQueryBuilder->buildQueryBuilder([
                            'object' => $message->getEntityOrigemName(),
                            'filter' => $filter,
                            'limit' => 1,
                            'offset' => '0',
                        ]);
                        if (0 === count($qb->getQuery()->getArrayResult())) {
                            $this->logData(
                                'info',
                                'O criteria não passou nas validações de regras de banco.',
                                $message
                            );
                            continue;
                        }
                    }

                    $this->logData(
                        'info',
                        'Vinculando regra de etiqueta.',
                        $message,
                        [
                            'regraEtiquetaId' => $regraEtiquetaEntity->getId(),
                        ]
                    );

                    $this->createVinculacaoEtiqueta(
                        $regraEtiquetaEntity,
                        $message,
                        $transactionId
                    );

                    $idsEtiquetasVinculadas[] = $regraEtiquetaEntity->getEtiqueta()->getId();
                }
            } while ($hasNext);
        } catch (Throwable $e) {
            $this->logData(
                'error',
                'Falha no handler de regra de etiqueta.',
                $message,
                [
                    'error' => $e,
                ]
            );
        }
    }

    /**
     * Retorna o procesos a ser utilizado pelo driver de IA para regras de etiqueta.
     *
     * @return Processo|null
     */
    protected function getProcesso(): ?Processo
    {
        return null;
    }

    /**
     * @param string                $level
     * @param string                $logMessage
     * @param RegrasEtiquetaMessage $message
     * @param array                 $extraContext
     *
     * @return void
     */
    protected function logData(
        string $level,
        string $logMessage,
        RegrasEtiquetaMessage $message,
        array $extraContext = []
    ): void {
        $this->logger->$level(
            sprintf(
                '[%s] %s',
                get_class($this),
                $logMessage
            ),
            [
                'momento' => $message->getSiglaMomentoDisparoRegraEtiqueta(),
                'messageUuid' => $message->getMessageUuid(),
                ...$extraContext,
            ]
        );
    }
}
