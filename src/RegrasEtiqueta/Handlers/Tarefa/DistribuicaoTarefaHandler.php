<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Handlers\Tarefa;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DistribuicaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\Entity\Distribuicao;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Entity\RegraEtiqueta;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Entity\Usuario;
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
 * DistribuicaoTarefaHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DistribuicaoTarefaHandler extends AbstractRegrasEtiquetaHandler implements RegrasEtiquetaHandlerInterface
{
    private Distribuicao $distribuicao;
    private ?Usuario $usuario = null;
    private ?Setor $setor = null;

    /**
     * Constructor.
     *
     * @param ArrayQueryBuilder                 $arrayQueryBuilder
     * @param RegraEtiquetaRepository           $regraEtiquetaRepository
     * @param LoggerInterface                   $logger
     * @param Traversable<FieldParserInterface> $parsers
     * @param DistribuicaoResource              $distribuicaoResource
     * @param VinculacaoEtiquetaResource        $vinculacaoEtiquetaResource
     */
    public function __construct(
        ArrayQueryBuilder $arrayQueryBuilder,
        RegraEtiquetaRepository $regraEtiquetaRepository,
        LoggerInterface $logger,
        #[TaggedIterator('supp_core.administrativo_backend.regras_etiqueta.criterias.parser')]
        Traversable $parsers,
        private readonly DistribuicaoResource $distribuicaoResource,
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
            ->setTarefa($this->distribuicao->getTarefa())
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
            $this->usuario?->getId(),
            $this->setor?->getId(),
            $this->setor?->getUnidade()?->getId(),
            $this->setor?->getUnidade()?->getModalidadeOrgaoCentral()?->getId(),
            $limit,
            $offset
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
            && SiglaMomentoDisparoRegraEtiqueta::TAREFA_DISTRIBUICAO->isEqual(
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
        $this->distribuicao = $this->distribuicaoResource->find(
            ['tarefa.uuid' => sprintf('eq:%s', $message->getEntityOrigemUuid())],
            ['id' => 'DESC'],
            1,
            0
        )['entities'][0];

        $this->usuario = null;
        if ($this->distribuicao?->getUsuarioAnterior()?->getId()
            !== $this->distribuicao->getUsuarioPosterior()->getId()) {
            $this->usuario = $this->distribuicao->getUsuarioPosterior();
        }

        $this->setor = null;
        if ($this->distribuicao->getSetorAnterior()?->getId() !== $this->distribuicao->getSetorPosterior()->getId()) {
            $this->setor = $this->distribuicao->getSetorPosterior();
        }

        if ($this->usuario || $this->setor) {
            parent::handle($message, $transactionId);
        }
    }

    /**
     * Retorna o procesos a ser utilizado pelo driver de IA para regras de etiqueta.
     *
     * @return Processo|null
     */
    public function getProcesso(): ?Processo
    {
        return $this->distribuicao->getTarefa()->getProcesso();
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
