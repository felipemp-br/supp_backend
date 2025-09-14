<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\Elastic\MessageHandlers;

use ONGR\ElasticsearchBundle\Service\IndexService;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModeloResource;
use SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateModeloMessage;
use SuppCore\AdministrativoBackend\Document\ComponenteDigital;
use SuppCore\AdministrativoBackend\Document\Documento;
use SuppCore\AdministrativoBackend\Document\EspecieSetor;
use SuppCore\AdministrativoBackend\Document\ModalidadeModelo;
use SuppCore\AdministrativoBackend\Document\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Document\Modelo as ModeloDocument;
use SuppCore\AdministrativoBackend\Document\Setor;
use SuppCore\AdministrativoBackend\Document\TipoDocumento;
use SuppCore\AdministrativoBackend\Document\Unidade;
use SuppCore\AdministrativoBackend\Document\Usuario;
use SuppCore\AdministrativoBackend\Document\VinculacaoModelo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo as VinculacaoModeloEntity;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\DenseVector\Message\DenseVectorMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/**
 * Class PopulateModeloMessageHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class PopulateModeloMessageHandler
{
    /**
     * PopulateModeloMessageHandler constructor.
     *
     * @param IndexService              $modeloIndex
     * @param ModeloResource            $modeloResource
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param TransactionManager        $transactionManager
     * @param MessageBusInterface       $bus
     * @param LoggerInterface           $logger
     */
    public function __construct(
        private readonly IndexService $modeloIndex,
        private readonly ModeloResource $modeloResource,
        private readonly ComponenteDigitalResource $componenteDigitalResource,
        private readonly TransactionManager $transactionManager,
        private readonly MessageBusInterface $bus,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param PopulateModeloMessage $message
     */
    public function __invoke(PopulateModeloMessage $message): void
    {
        $startId = $message->getStartId();
        $endId = $message->getEndId();
        $currentId = $startId;

        while ($currentId <= $endId) {
            try {
                $modeloEntity = $this->modeloResource->getRepository()->find($currentId);

                if (!$modeloEntity) {
                    ++$currentId;
                    continue;
                }

                $modeloDocument = new ModeloDocument();
                $modeloDocument->setId($modeloEntity->getId());
                $modeloDocument->setNome($modeloEntity->getNome());
                $modeloDocument->setDescricao($modeloEntity->getDescricao());
                $modeloDocument->setAtivo($modeloEntity->getAtivo());
                $modeloDocument->setCriadoEm($modeloEntity->getCriadoEm());
                $modeloDocument->setAtualizadoEm($modeloEntity->getAtualizadoEm());

                $modalidadeModeloDocument = new ModalidadeModelo();
                $modalidadeModeloDocument->setId($modeloEntity->getModalidadeModelo()->getId());
                $modalidadeModeloDocument->setValor($modeloEntity->getModalidadeModelo()->getValor());

                $modeloDocument->setModalidadeModelo($modalidadeModeloDocument);

                /** @var VinculacaoModeloEntity $vinculacaoModeloEntity */
                foreach ($modeloEntity->getVinculacoesModelos() as $vinculacaoModeloEntity) {
                    if ($vinculacaoModeloEntity->getSetor()) {
                        $setorDocument = new Setor();
                        $setorDocument->setId($vinculacaoModeloEntity->getSetor()->getId());
                        $setorDocument->setNome($vinculacaoModeloEntity->getSetor()->getNome());

                        $unidadeDocument = new Unidade();
                        $unidadeDocument->setId($vinculacaoModeloEntity->getSetor()->getUnidade()->getId());
                        $unidadeDocument->setNome($vinculacaoModeloEntity->getSetor()->getUnidade()->getNome());
                        $setorDocument->setUnidade($unidadeDocument);

                        $especieSetorDocument = new EspecieSetor();
                        $especieSetorDocument->setId($vinculacaoModeloEntity->getSetor()->getEspecieSetor()->getId());
                        $especieSetorDocument->setNome(
                            $vinculacaoModeloEntity->getSetor()->getEspecieSetor()->getNome()
                        );
                        $setorDocument->setEspecieSetor($especieSetorDocument);

                        $vinculacaoModeloDocument = new VinculacaoModelo();
                        $vinculacaoModeloDocument->setId($vinculacaoModeloEntity->getId());
                        $vinculacaoModeloDocument->setSetor($setorDocument);

                        $modeloDocument->setVinculacoesModelos($vinculacaoModeloDocument);
                    }
                    if ($vinculacaoModeloEntity->getUsuario()) {
                        $usuarioDocument = new Usuario();
                        $usuarioDocument->setId($vinculacaoModeloEntity->getUsuario()->getId());
                        $usuarioDocument->setNome($vinculacaoModeloEntity->getUsuario()->getNome());

                        $vinculacaoModeloDocument = new VinculacaoModelo();
                        $vinculacaoModeloDocument->setId($vinculacaoModeloEntity->getId());
                        $vinculacaoModeloDocument->setUsuario($usuarioDocument);

                        $modeloDocument->setVinculacoesModelos($vinculacaoModeloDocument);
                    }

                    if ($vinculacaoModeloEntity->getModalidadeOrgaoCentral()) {
                        $modalidadeOrgaoCentralDocument = new ModalidadeOrgaoCentral();
                        $modalidadeOrgaoCentralDocument->setId(
                            $vinculacaoModeloEntity->getModalidadeOrgaoCentral()->getId()
                        );
                        $modalidadeOrgaoCentralDocument->setValor(
                            $vinculacaoModeloEntity->getModalidadeOrgaoCentral()->getValor()
                        );

                        $vinculacaoModeloDocument = new VinculacaoModelo();
                        $vinculacaoModeloDocument->setId($vinculacaoModeloEntity->getId());
                        $vinculacaoModeloDocument->setModalidadeOrgaoCentral($modalidadeOrgaoCentralDocument);

                        $modeloDocument->setVinculacoesModelos($vinculacaoModeloDocument);
                    }

                    if ($vinculacaoModeloEntity->getEspecieSetor()) {
                        $especieSetorDocument = new EspecieSetor();
                        $especieSetorDocument->setId($vinculacaoModeloEntity->getEspecieSetor()->getId());
                        $especieSetorDocument->setNome($vinculacaoModeloEntity->getEspecieSetor()->getNome());

                        $vinculacaoModeloDocument = new VinculacaoModelo();
                        $vinculacaoModeloDocument->setId($vinculacaoModeloEntity->getId());
                        $vinculacaoModeloDocument->setEspecieSetor($especieSetorDocument);

                        $modeloDocument->setVinculacoesModelos($vinculacaoModeloDocument);
                    }
                }

                $documentoDocument = new Documento();
                $documentoDocument->setId($modeloEntity->getDocumento()->getId());

                $tipoDocumentoDocument = new TipoDocumento();
                $tipoDocumentoDocument->setId($modeloEntity->getDocumento()->getTipoDocumento()->getId());
                $tipoDocumentoDocument->setNome($modeloEntity->getDocumento()->getTipoDocumento()->getNome());

                $documentoDocument->setTipoDocumento($tipoDocumentoDocument);

                $transactionId = $this->transactionManager->begin();
                $componenteDigitalDocument = new ComponenteDigital();
                $componenteDigitalDocument->setId($modeloEntity->getDocumento()->getComponentesDigitais()[0]->getId());
                $componenteDigitalDocument->setConteudo(
                    base64_encode(
                        $this->componenteDigitalResource->download(
                            $modeloEntity->getDocumento()->getComponentesDigitais()[0]->getId(),
                            $transactionId
                        )->getConteudo()
                    )
                );

                $documentoDocument->setComponentesDigitais($componenteDigitalDocument);

                $modeloDocument->setDocumento($documentoDocument);

                $this->modeloIndex->persist($modeloDocument);

                $em = $this->modeloResource->getRepository()->getEntityManager();
                $conn = $em->getConnection();
                $conn->update(
                    'ad_modelo',
                    ['data_hora_indexacao' => $modeloEntity->getAtualizadoEm()->format(
                        $conn->getDatabasePlatform()->getDateTimeFormatString()
                    )],
                    ['id' => $modeloEntity->getId()]
                );

//                $denseVectorMessage = new DenseVectorMessage();
//                $denseVectorMessage->setId($modeloEntity->getDocumento()->getComponentesDigitais()[0]->getId());
//                $this->bus->dispatch($denseVectorMessage);
            } catch (Throwable $t) {
                $this->logger->critical($t->getMessage().$t->getTraceAsString());
            }
            ++$currentId;
        }

        $this->modeloIndex->commit(
            'none',
            [
                'pipeline' => 'attachment_documento_componentes_digitais_conteudo',
            ]
        );

        $this->modeloResource->getRepository()->getEntityManager()->clear();
    }
}
