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
use SuppCore\AdministrativoBackend\AcoesEtiqueta\Tarefa\Models\CriarOficioModel;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Entity\Acao;
use SuppCore\AdministrativoBackend\Entity\Interfaces\VinculacaoEtiquetaInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Enums\IdentificadorModalidadeAcaoEtiqueta;
use SuppCore\AdministrativoBackend\Repository\EspecieDocumentoAvulsoRepository;
use SuppCore\AdministrativoBackend\Repository\ModeloRepository;
use SuppCore\AdministrativoBackend\Repository\PessoaRepository;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;

/**
 * CriarOficioHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CriarOficioHandler extends AbstractAcoesEtiquetaHandler implements AcoesEtiquetaHandlerInterface
{
    /**
     * Constructor.
     *
     * @param DocumentoAvulsoResource          $documentoAvulsoResource
     * @param EspecieDocumentoAvulsoRepository $especieDocumentoAvulsoRepository
     * @param ModeloRepository                 $modeloRepository
     * @param SetorRepository                  $setorRepository
     * @param PessoaRepository                 $pessoaRepository
     */
    public function __construct(
        private readonly DocumentoAvulsoResource $documentoAvulsoResource,
        private readonly EspecieDocumentoAvulsoRepository $especieDocumentoAvulsoRepository,
        private readonly ModeloRepository $modeloRepository,
        private readonly SetorRepository $setorRepository,
        private readonly PessoaRepository $pessoaRepository
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
        return IdentificadorModalidadeAcaoEtiqueta::TAREFA_CRIAR_OFICIO
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
            CriarOficioModel::class,
        );
        $documentoAvulsoDTO = (new DocumentoAvulso())
            ->setProcesso($vinculacaoEtiqueta->getTarefa()->getProcesso())
            ->setEspecieDocumentoAvulso(
                $this->especieDocumentoAvulsoRepository->find($model->getEspecieDocumentoAvulsoId())
            )
            ->setTarefaOrigem(
                $vinculacaoEtiqueta->getTarefa()
            )
            ->setModelo(
                $this->modeloRepository->find($model->getModeloId())
            )
            ->setMecanismoRemessa(
                $model->getMecanismoRemessa()
            )
            ->setObservacao(
                $model->getObservacao()
            )
            ->setDataHoraInicioPrazo(new DateTime())
            ->setDocumentoRemessa(
                $vinculacaoEtiqueta->getDocumento()
            )
            ->setDataHoraFinalPrazo(
                new DateTime(
                    sprintf(
                        '+ %d days',
                        $model->getPrazoFinal()
                    )
                )
            )
            ->setUrgente($model->getUrgente());

        if ($model->getPessoaDestinoId()) {
            $documentoAvulsoDTO->setPessoaDestino(
                $this->pessoaRepository->find($model->getPessoaDestinoId())
            );
        }

        if ($model->getSetorDestinoId()) {
            $documentoAvulsoDTO->setSetorDestino(
                $this->setorRepository->find($model->getSetorDestinoId())
            );
        }

        if ($model->getSetorOrigemId()) {
            $documentoAvulsoDTO->setSetorOrigem(
                $this->setorRepository->find($model->getSetorOrigemId())
            );
        }

        if (!empty($model->getBlocoResponsaveis())) {
            foreach ($model->getBlocoResponsaveis() as $responsavel) {
                $documentoAvulsoDTOResponsavel = clone $documentoAvulsoDTO;
                switch ($responsavel->getTipo()) {
                    case 'setor':
                        $documentoAvulsoDTOResponsavel->setSetorDestino(
                            $this->setorRepository->find($responsavel->getId())
                        );
                        break;
                    case 'pessoa':
                        $documentoAvulsoDTOResponsavel->setPessoaDestino(
                            $this->pessoaRepository->find($responsavel->getId())
                        );
                        break;
                }
                $this->documentoAvulsoResource->create($documentoAvulsoDTOResponsavel, $transactionId);
            }
        } else {
            $this->documentoAvulsoResource->create($documentoAvulsoDTO, $transactionId);
        }
    }
}
