<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Handlers;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use ReflectionException;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\AbstractAcoesEtiquetaHandler;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\AcoesEtiquetaHandlerInterface;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models\DistribuirTarefaModel;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Entity\Acao;
use SuppCore\AdministrativoBackend\Entity\Interfaces\VinculacaoEtiquetaInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Enums\IdentificadorModalidadeAcaoEtiqueta;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;

/**
 * DistribuirTarefaHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DistribuirTarefaHandler extends AbstractAcoesEtiquetaHandler implements AcoesEtiquetaHandlerInterface
{
    /**
     * Constructor.
     *
     * @param SetorRepository   $setorRepository
     * @param TarefaResource    $tarefaResource
     * @param UsuarioRepository $usuarioRepository
     */
    public function __construct(
        private readonly SetorRepository $setorRepository,
        private readonly TarefaResource $tarefaResource,
        private readonly UsuarioRepository $usuarioRepository
    ) {
    }

    /**
     * Verifica se a ação é suportada pelo handler.
     *
     * @param Acao                        $acao
     * @param VinculacaoEtiquetaInterface $vinculacaoEtiqueta
     *
     * @return bool
     */
    public function support(
        Acao $acao,
        VinculacaoEtiquetaInterface $vinculacaoEtiqueta
    ): bool {
        return IdentificadorModalidadeAcaoEtiqueta::TAREFA_DISTRIBUIR_TAREFA
                ->isEqual($acao->getModalidadeAcaoEtiqueta()->getIdentificador())
                && $vinculacaoEtiqueta instanceof VinculacaoEtiqueta
                && $vinculacaoEtiqueta->getTarefa();
    }

    /**
     * Executa a ação.
     *
     * @param Acao                        $acao
     * @param VinculacaoEtiquetaInterface $vinculacaoEtiqueta
     * @param string                      $transactionId
     *
     * @return void
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function handle(
        Acao $acao,
        VinculacaoEtiquetaInterface $vinculacaoEtiqueta,
        string $transactionId
    ): void {
        $model = $this->getModel(
            $acao->getContexto(),
            DistribuirTarefaModel::class,
        );
        $tarefaEntity = $vinculacaoEtiqueta->getTarefa();
        $tarefaDTO = $this->tarefaResource->getDtoForEntity(
            $tarefaEntity->getId(),
            Tarefa::class,
            null,
            $tarefaEntity
        );

        $usuarioResponsavel = null;
        if ($model->getUsuarioId()) {
            $usuarioResponsavel = $this->usuarioRepository->find($model->getUsuarioId());
        }
        $tarefaDTO->setSetorResponsavel(
            $this->setorRepository->find($model->getSetorResponsavelId())
        );
        $tarefaDTO->setUsuarioResponsavel($usuarioResponsavel);
        $this->tarefaResource->update($tarefaDTO->getId(), $tarefaDTO, $transactionId);
    }
}
