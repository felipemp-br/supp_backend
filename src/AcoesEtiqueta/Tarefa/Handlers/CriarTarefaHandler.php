<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Handlers;

use DateInterval;
use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use ReflectionException;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\AbstractAcoesEtiquetaHandler;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\AcoesEtiquetaHandlerInterface;
use SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models\CriarTarefaModel;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Entity\Acao;
use SuppCore\AdministrativoBackend\Entity\Interfaces\VinculacaoEtiquetaInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Enums\IdentificadorModalidadeAcaoEtiqueta;
use SuppCore\AdministrativoBackend\Repository\EspecieTarefaRepository;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;

/**
 * CriarTarefaHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CriarTarefaHandler extends AbstractAcoesEtiquetaHandler implements AcoesEtiquetaHandlerInterface
{
    /**
     * Constructor.
     *
     * @param SetorRepository         $setorRepository
     * @param TarefaResource          $tarefaResource
     * @param EspecieTarefaRepository $especieTarefaRepository
     * @param UsuarioRepository       $usuarioRepository
     */
    public function __construct(
        private readonly SetorRepository $setorRepository,
        private readonly TarefaResource $tarefaResource,
        private readonly EspecieTarefaRepository $especieTarefaRepository,
        private readonly UsuarioRepository $usuarioRepository,
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
        return IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_TAREFA
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
            CriarTarefaModel::class,
        );
        $tarefaDTO = (new TarefaDTO())
            ->setProcesso($vinculacaoEtiqueta->getTarefa()->getProcesso())
            ->setEspecieTarefa(
                $this->especieTarefaRepository->find($model->getEspecieTarefaId())
            )
            ->setSetorResponsavel(
                $this->setorRepository->find($model->getSetorResponsavelId())
            )
            ->setSetorOrigem(
                $this->setorRepository->find($model->getSetorOrigemId())
            )
            ->setUrgente($model->getUrgente())
            ->setObservacao($model->getObservacao());

        if ($model->getUsuarioResponsavelId()) {
            $tarefaDTO->setUsuarioResponsavel(
                $this->usuarioRepository->find($model->getUsuarioResponsavelId())
            );
        }

        $dataInicial = new DateTime();
        $dataFinal = clone $dataInicial;
        $prazo = $model->getPrazoDias();
        $feriados = $this->getFeriados();
        if ($model->getDiasUteis()) {
            while ($prazo > 0) {
                $dayOfWeek = (int) $dataFinal->format('N');
                if (0 === $dayOfWeek || 6 === $dayOfWeek || in_array($dataFinal->format('d-m'), $feriados)) {
                    $dataFinal->modify('+1 day');
                }
                $dataFinal->modify('+1 day');
                --$prazo;
            }
        } else {
            $diasPrazo = 'P'.$prazo.'D';
            $dataFinal->add(new DateInterval($diasPrazo));
        }

        $tarefaDTO->setDataHoraInicioPrazo($dataInicial);
        $tarefaDTO->setDataHoraFinalPrazo($dataFinal);
        $this->tarefaResource->create($tarefaDTO, $transactionId);
    }

    /**
     * @return string[]
     */
    private function getFeriados(): array
    {
        return ['01-01', '21-04', '01-05', '07-09', '12-10', '02-11', '15-11', '25-12'];
    }
}
