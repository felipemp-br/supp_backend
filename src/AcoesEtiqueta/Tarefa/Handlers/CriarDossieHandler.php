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
use SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models\CriarDossieModel;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DossieResource;
use SuppCore\AdministrativoBackend\Entity\Acao;
use SuppCore\AdministrativoBackend\Entity\Interessado;
use SuppCore\AdministrativoBackend\Entity\Interfaces\VinculacaoEtiquetaInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Enums\IdentificadorModalidadeAcaoEtiqueta;
use SuppCore\AdministrativoBackend\Repository\ModalidadeInteressadoRepository;
use SuppCore\AdministrativoBackend\Repository\TipoDossieRepository;

/**
 * CriarDossieHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CriarDossieHandler extends AbstractAcoesEtiquetaHandler implements AcoesEtiquetaHandlerInterface
{
    /**
     * Constructor.
     *
     * @param ModalidadeInteressadoRepository $modalidadeInteressadoRepository
     * @param TipoDossieRepository            $tipoDossieRepository
     * @param DossieResource                  $dossieResource
     */
    public function __construct(
        private readonly ModalidadeInteressadoRepository $modalidadeInteressadoRepository,
        private readonly TipoDossieRepository $tipoDossieRepository,
        private readonly DossieResource $dossieResource
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
        return IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_DOSSIE
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
            CriarDossieModel::class,
        );
        $modalidadeInteressado = $this->modalidadeInteressadoRepository->find(
            $model->getModalidadeInteressadoId()
        );
        $dossieDTO = (new Dossie())
            ->setProcesso($vinculacaoEtiqueta->getTarefa()->getProcesso())
            ->setSobDemanda(true)
            ->setVisibilidade($model->getVisibilidade());
        $interessados = array_filter(
            $vinculacaoEtiqueta->getTarefa()->getProcesso()->getInteressados()->toArray(),
            function (Interessado $interessado) use ($modalidadeInteressado) {
                return $interessado->getModalidadeInteressado()->getId() === $modalidadeInteressado?->getId();
            }
        );
        foreach ($interessados as $interessado) {
            foreach ($model->getTipoDossie() as $tipoDossie) {
                $dossieDTOInteressado = clone $dossieDTO;
                $dossieDTOInteressado
                    ->setTipoDossie(
                        $this->tipoDossieRepository->find($tipoDossie->getId())
                    )
                    ->setPessoa($interessado->getPessoa());
                $this->dossieResource->create($dossieDTOInteressado, $transactionId);
            }
        }
    }
}
