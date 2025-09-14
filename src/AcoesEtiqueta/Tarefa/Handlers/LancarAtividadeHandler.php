<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Handlers;

use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use ReflectionException;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\AbstractAcoesEtiquetaHandler;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\AcoesEtiquetaHandlerInterface;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models\LancarAtividadeModel;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AtividadeResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieAtividadeResource;
use SuppCore\AdministrativoBackend\Entity\Acao;
use SuppCore\AdministrativoBackend\Entity\Interfaces\VinculacaoEtiquetaInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Enums\IdentificadorModalidadeAcaoEtiqueta;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;

/**
 * LancarAtividadeHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class LancarAtividadeHandler extends AbstractAcoesEtiquetaHandler implements AcoesEtiquetaHandlerInterface
{
    /**
     * Constructor.
     *
     * @param AtividadeResource        $atividadeResource
     * @param EspecieAtividadeResource $especieAtividadeResource
     * @param UsuarioRepository        $usuarioRepository
     * @param SetorRepository          $setorRepository
     */
    public function __construct(
        private readonly AtividadeResource $atividadeResource,
        private readonly EspecieAtividadeResource $especieAtividadeResource,
        private readonly UsuarioRepository $usuarioRepository,
        private readonly SetorRepository $setorRepository
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
        return IdentificadorModalidadeAcaoEtiqueta::TAREFA_LANCAR_ATIVIDADE
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
            LancarAtividadeModel::class,
        );
        $especiesAtividadeId = $model->getEspeciesAtividadeId();
        if ($model->getEspecieAtividadeId()) {
            $especiesAtividadeId[] = $model->getEspecieAtividadeId();
        }
        $totalAtividades = count($especiesAtividadeId) - 1;
        $atividadeDTO = (new Atividade())
            ->setTarefa($vinculacaoEtiqueta->getTarefa())
            ->setDataHoraConclusao(new DateTime())
            ->setEncerraTarefa(false)
            ->setObservacao($model->getObservacao())
            ->setUsuario($this->usuarioRepository->find($model->getUsuarioId()));
        foreach($especiesAtividadeId as $key => $especieAtividadeId) {
            $atividade = clone $atividadeDTO;
            $especieAtividade = $this->especieAtividadeResource->findOne($especieAtividadeId);
            $atividade->setEspecieAtividade($especieAtividade);

            // Destina as minutas e/ou encerra a tarefa somente na última atividade do bloco
            if($totalAtividades === $key) {
                $atividade->setEncerraTarefa($model->getEncerraTarefa());
                // Caso existam minutas na tarefa, destinar de acordo com seleção do usuário
                if($vinculacaoEtiqueta->getTarefa()->getMinutas()->count()) {
                    $atividade->setDestinacaoMinutas($model->getDestinacaoMinutas());
                    // Adiciona todas as minutas para destinação
                    foreach($vinculacaoEtiqueta->getTarefa()->getMinutas() as $minuta) {
                        $atividade->addDocumento($minuta);
                    }
                    // Caso destinação seja 'submeter_aprovacao', preencher dados obrigatórios.
                    if($model->getDestinacaoMinutas() === 'submeter_aprovacao') {
                        $atividade->setSetorAprovacao(
                            $this->setorRepository->find($model->getSetorAprovacaoId())
                        );
                        $atividade->setUsuarioAprovacao(
                            $this->usuarioRepository->find($model->getUsuarioAprovacaoId())
                        );
                    }
                }
            }
            $this->atividadeResource->create($atividade, $transactionId);
        }
    }
}
