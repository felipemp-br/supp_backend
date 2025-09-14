<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/MessageHandler/IndexacaoMessage.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\MessageHandler;

use ONGR\ElasticsearchBundle\Service\IndexService;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\Document\Assunto;
use SuppCore\AdministrativoBackend\Document\AssuntoAdministrativo;
use SuppCore\AdministrativoBackend\Document\Classificacao;
use SuppCore\AdministrativoBackend\Document\EspecieProcesso as EspecieProcessoDocument;
use SuppCore\AdministrativoBackend\Document\Etiqueta;
use SuppCore\AdministrativoBackend\Document\GeneroProcesso as GeneroProcessoDocument;
use SuppCore\AdministrativoBackend\Document\Interessado;
use SuppCore\AdministrativoBackend\Document\ModalidadeFase as ModalidadeFaseDocument;
use SuppCore\AdministrativoBackend\Document\ModalidadeInteressado;
use SuppCore\AdministrativoBackend\Document\ModalidadeMeio;
use SuppCore\AdministrativoBackend\Document\ModalidadeVinculacaoProcesso;
use SuppCore\AdministrativoBackend\Document\PessoaInteressado;
use SuppCore\AdministrativoBackend\Document\Procedencia;
use SuppCore\AdministrativoBackend\Document\Processo as ProcessoDocument;
use SuppCore\AdministrativoBackend\Document\ProcessoVinculado;
use SuppCore\AdministrativoBackend\Document\SetorAtual;
use SuppCore\AdministrativoBackend\Document\Unidade;
use SuppCore\AdministrativoBackend\Document\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Document\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\Document\Adaptador\AdaptadorManager;
use SuppCore\AdministrativoBackend\Entity\Assunto as AssuntoEntity;
use SuppCore\AdministrativoBackend\Entity\Interessado as InteressadoEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class IndexacaoMessageHandler.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
#[AsMessageHandler]
class IndexacaoMessageHandler
{
    private IndexService $processoIndex;
    private ProcessoResource $processoResource;
    private LoggerInterface $logger;
    private AdaptadorManager $adaptadoresManager;

    /**
     * IndexacaoMessageHandler constructor.
     */
    public function __construct(
        IndexService $processoIndex,
        ProcessoResource $processoResource,
        LoggerInterface $logger,
        AdaptadorManager $adaptadoresManager
    ) {
        $this->processoIndex = $processoIndex;
        $this->processoResource = $processoResource;
        $this->logger = $logger;
        $this->adaptadoresManager = $adaptadoresManager;
    }

    /**
     * @param IndexacaoMessage $message
     */
    public function __invoke(IndexacaoMessage $message)
    {
        try {
            $processoEntity = $this->processoResource->getRepository()->findOneBy(['uuid' => $message->getUuid()]);
            if (!$processoEntity) {
                return;
            }
            $processoDocument = new ProcessoDocument();
            $processoDocument->setId($processoEntity->getId());
            $processoDocument->setDataHoraAbertura($processoEntity->getDataHoraAbertura());
            $processoDocument->setNUP($processoEntity->getNUP());
            $processoDocument->setTitulo($processoEntity->getTitulo());
            $processoDocument->setOutroNumero($processoEntity->getOutroNumero());
            $processoDocument->setDescricao($processoEntity->getDescricao());
            $processoDocument->setUnidadeArquivistica($processoEntity->getUnidadeArquivistica());
            //generoProcesso
            $generoProcessoDocument = new GeneroProcessoDocument();
            $generoProcessoDocument->setId($processoEntity->getEspecieProcesso()->getGeneroProcesso()->getId());
            $generoProcessoDocument->setNome($processoEntity->getEspecieProcesso()->getGeneroProcesso()->getNome());
            //especieProcesso
            $especieProcessoDocument = new EspecieProcessoDocument();
            $especieProcessoDocument->setId($processoEntity->getEspecieProcesso()->getId());
            $especieProcessoDocument->setNome($processoEntity->getEspecieProcesso()->getNome());
            $especieProcessoDocument->setGeneroProcesso($generoProcessoDocument);
            $processoDocument->setEspecieProcesso($especieProcessoDocument);
            //modalidadeFase
            $modalidadeFaseDocument = new ModalidadeFaseDocument();
            $modalidadeFaseDocument->setId($processoEntity->getModalidadeFase()->getId());
            $modalidadeFaseDocument->setValor($processoEntity->getModalidadeFase()->getValor());
            $processoDocument->setModalidadeFase($modalidadeFaseDocument);
            //classificacao
            $classificacaoDocument = new Classificacao();
            $classificacaoDocument->setId($processoEntity->getClassificacao()->getId());
            $classificacaoDocument->setNome($processoEntity->getClassificacao()->getNome());
            $processoDocument->setClassificacao($classificacaoDocument);
            //procedencia
            if ($processoEntity->getProcedencia()) {
                $procedenciaDocument = new Procedencia();
                $procedenciaDocument->setId($processoEntity->getProcedencia()->getId());
                $procedenciaDocument->setNome($processoEntity->getProcedencia()->getNome());
                $processoDocument->setProcedencia($procedenciaDocument);
            }
            //modalidadeMeio
            $modalidadeMeioDocument = new ModalidadeMeio();
            $modalidadeMeioDocument->setId($processoEntity->getModalidadeMeio()->getId());
            $modalidadeMeioDocument->setValor($processoEntity->getModalidadeMeio()->getValor());
            $processoDocument->setModalidadeMeio($modalidadeMeioDocument);
            //unidade
            $unidadeDocument = new Unidade();
            $unidadeDocument->setId($processoEntity->getSetorAtual()->getUnidade()->getId());
            $unidadeDocument->setNome($processoEntity->getSetorAtual()->getUnidade()->getNome());
            //setorAtual
            $setorAtualDocument = new SetorAtual();
            $setorAtualDocument->setId($processoEntity->getSetorAtual()->getId());
            $setorAtualDocument->setNome($processoEntity->getSetorAtual()->getNome());
            $setorAtualDocument->setUnidade($unidadeDocument);
            $processoDocument->setSetorAtual($setorAtualDocument);
            //vinculacoesProcessos
            /** @var VinculacaoProcessoEntity $vinculacaoProcesso */
            foreach ($processoEntity->getVinculacoesProcessos() as $vinculacaoProcesso) {
                $vinculacaoProcessoDocument = new VinculacaoProcesso();
                $vinculacaoProcessoDocument->setId($vinculacaoProcesso->getId());
                //processoVinculado
                $processoVinculadoDocument = new ProcessoVinculado();
                $processoVinculadoDocument->setId($vinculacaoProcesso->getProcessoVinculado()->getId());
                $processoVinculadoDocument->setNUP($vinculacaoProcesso->getProcessoVinculado()->getNUP());
                $vinculacaoProcessoDocument->setProcessoVinculado($processoVinculadoDocument);
                //modalidadeVinculacao
                $modalidadeVinculacaoProcessoDocument = new ModalidadeVinculacaoProcesso();
                $modalidadeVinculacaoProcessoDocument->setId(
                    $vinculacaoProcesso->getModalidadeVinculacaoProcesso()->getId()
                );
                $modalidadeVinculacaoProcessoDocument->setValor(
                    $vinculacaoProcesso->getModalidadeVinculacaoProcesso()->getValor()
                );
                $vinculacaoProcessoDocument->setModalidadeVinculacaoProcesso($modalidadeVinculacaoProcessoDocument);
                $processoDocument->setVinculacoesProcessos($vinculacaoProcessoDocument);
            }
            //interessados
            /** @var InteressadoEntity $interessado */
            foreach ($processoEntity->getInteressados() as $interessado) {
                $interessadoDocument = new Interessado();
                $interessadoDocument->setId($interessado->getId());
                //pessoa
                $pessoaInteressadoDocument = new PessoaInteressado();
                $pessoaInteressadoDocument->setId($interessado->getPessoa()->getId());
                $pessoaInteressadoDocument->setNome($interessado->getPessoa()->getNome());
                $pessoaInteressadoDocument->setNumeroDocumentoPrincipal(
                    $interessado->getPessoa()->getNumeroDocumentoPrincipal()
                );
                $interessadoDocument->setPessoa($pessoaInteressadoDocument);
                //modalidadeInteressado
                $modalidadeInteressadoDocument = new ModalidadeInteressado();
                $modalidadeInteressadoDocument->setId($interessado->getModalidadeInteressado()->getId());
                $modalidadeInteressadoDocument->setValor($interessado->getModalidadeInteressado()->getValor());
                $interessadoDocument->setModalidadeInteressado($modalidadeInteressadoDocument);
                $processoDocument->setInteressados($interessadoDocument);
            }
            //assuntos
            /** @var AssuntoEntity $assunto */
            foreach ($processoEntity->getAssuntos() as $assunto) {
                $assuntoDocument = new Assunto();
                $assuntoDocument->setId($assunto->getId());
                $assuntoDocument->setPrincipal($assunto->getPrincipal());
                //assuntoAdministrativo
                $assuntoAdministrativoDocument = new AssuntoAdministrativo();
                $assuntoAdministrativoDocument->setId($assunto->getAssuntoAdministrativo()->getId());
                $assuntoAdministrativoDocument->setNome($assunto->getAssuntoAdministrativo()->getNome());
                $assuntoDocument->setAssuntoAdministrativo($assuntoAdministrativoDocument);
                $processoDocument->setAssuntos($assuntoDocument);
            }
            //vinculacoesEtiquetas
            /** @var VinculacaoEtiquetaEntity $vinculacaoEtiqueta */
            foreach ($processoEntity->getVinculacoesEtiquetas() as $vinculacaoEtiqueta) {
                $vinculacaoEtiquetaDocument = new VinculacaoEtiqueta();
                $vinculacaoEtiquetaDocument->setId($vinculacaoEtiqueta->getId());
                //etiqueta
                $etiquetaDocument = new Etiqueta();
                $etiquetaDocument->setId($vinculacaoEtiqueta->getEtiqueta()->getId());
                $vinculacaoEtiquetaDocument->setEtiqueta($etiquetaDocument);
                $processoDocument->setVinculacoesEtiquetas($vinculacaoEtiquetaDocument);
            }

            $this->adaptadoresManager->proccess(
                $processoDocument,
                $processoEntity
            );

            $this->processoIndex->persist($processoDocument);
            $this->processoIndex->commit('none');

            $em = $this->processoResource->getRepository()->getEntityManager();
            $conn = $em->getConnection();
            $conn->update(
                'ad_processo',
                ['data_hora_indexacao' => $processoEntity->getAtualizadoEm()->format(
                    $conn->getDatabasePlatform()->getDateTimeFormatString()
                )],
                ['id' => $processoEntity->getId()]
            );
        } catch (Throwable $t) {
            $this->logger->critical($t->getMessage().$t->getTraceAsString());
        }
    }
}
