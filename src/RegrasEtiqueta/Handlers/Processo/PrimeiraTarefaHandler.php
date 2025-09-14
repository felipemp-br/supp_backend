<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Handlers\Processo;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\RegraEtiqueta;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Enums\SiglaMomentoDisparoRegraEtiqueta;
use SuppCore\AdministrativoBackend\QueryBuilder\ArrayQueryBuilder;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Criterias\Parsers\FieldParserInterface;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Handlers\AbstractRegrasEtiquetaHandler;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Handlers\RegrasEtiquetaHandlerInterface;
use SuppCore\AdministrativoBackend\RegrasEtiqueta\Message\RegrasEtiquetaMessage;
use SuppCore\AdministrativoBackend\Repository\RegraEtiquetaRepository;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Traversable;

/**
 * PrimeiraTarefaHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PrimeiraTarefaHandler extends AbstractRegrasEtiquetaHandler implements RegrasEtiquetaHandlerInterface
{
    private Tarefa $tarefa;

    /**
     * Constructor.
     *
     * @param ArrayQueryBuilder                 $arrayQueryBuilder
     * @param RegraEtiquetaRepository           $regraEtiquetaRepository
     * @param LoggerInterface                   $logger
     * @param Traversable<FieldParserInterface> $parsers
     * @param TarefaResource                    $tarefaResource
     * @param VinculacaoEtiquetaResource        $vinculacaoEtiquetaResource
     */
    public function __construct(
        ArrayQueryBuilder $arrayQueryBuilder,
        RegraEtiquetaRepository $regraEtiquetaRepository,
        LoggerInterface $logger,
        #[TaggedIterator('supp_core.administrativo_backend.regras_etiqueta.criterias.parser')]
        Traversable $parsers,
        private readonly TarefaResource $tarefaResource,
        private readonly VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
    ) {
        parent::__construct(
            $arrayQueryBuilder,
            $regraEtiquetaRepository,
            $logger,
            $parsers
        );
    }

    /**
     * @param RegraEtiqueta         $regraEtiquetaEntity
     * @param RegrasEtiquetaMessage $message
     * @param string                $transactionId
     *
     * @return void
     */
    protected function createVinculacaoEtiqueta(
        RegraEtiqueta $regraEtiquetaEntity,
        RegrasEtiquetaMessage $message,
        string $transactionId
    ): void {
        $vinculacaoEtiqueta = (new VinculacaoEtiqueta())
            ->setEtiqueta($regraEtiquetaEntity->getEtiqueta())
            ->setRegraEtiquetaOrigem($regraEtiquetaEntity)
            ->setProcesso($this->tarefa->getProcesso())
            ->setSugestao(true);

        $this->vinculacaoEtiquetaResource->create(
            $vinculacaoEtiqueta,
            $transactionId
        );
    }

    /**
     * @param RegrasEtiquetaMessage $message
     * @param int                   $limit
     * @param int                   $offset
     *
     * @return Paginator
     */
    protected function findRegrasEtiqueta(RegrasEtiquetaMessage $message, int $limit, int $offset): Paginator
    {
        return $this->regraEtiquetaRepository->findRegrasAplicaveisMomentoDisparoRegraEtiqueta(
            $message->getSiglaMomentoDisparoRegraEtiqueta(),
            null,
            $this->tarefa->getSetorResponsavel()->getId(),
            $this->tarefa->getSetorResponsavel()->getUnidade()->getId(),
            $this->tarefa->getSetorResponsavel()->getUnidade()->getModalidadeOrgaoCentral()->getId(),
        );
    }

    /**
     * @param RegrasEtiquetaMessage $message
     *
     * @return bool
     */
    public function support(RegrasEtiquetaMessage $message): bool
    {
        return Tarefa::class === $message->getEntityOrigemName()
            && SiglaMomentoDisparoRegraEtiqueta::PROCESSO_PRIMEIRA_TAREFA->isEqual(
                $message->getSiglaMomentoDisparoRegraEtiqueta()
            );
    }

    /**
     * @param RegrasEtiquetaMessage $message
     * @param string                $transactionId
     *
     * @return void
     */
    public function handle(RegrasEtiquetaMessage $message, string $transactionId): void
    {
        $this->tarefa = $this->tarefaResource->findOneBy(
            ['uuid' => $message->getEntityOrigemUuid()]
        );

        parent::handle($message, $transactionId);
    }

    /**
     * Retorna o procesos a ser utilizado pelo driver de IA para regras de etiqueta.
     *
     * @return Processo|null
     */
    public function getProcesso(): ?Processo
    {
        return $this->tarefa->getProcesso();
    }

    /**
     * Retorna a ordem de execução.
     *
     * @return int
     */
    public function order(): int
    {
        return 10;
    }
}
