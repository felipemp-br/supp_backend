<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Triggers/Juntada/MessageHandler/IndexacaoMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada\MessageHandler;

use ONGR\ElasticsearchBundle\Service\IndexService;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\Document\DocumentoOrigem;
use SuppCore\AdministrativoBackend\Document\EspecieSetor;
use SuppCore\AdministrativoBackend\Document\Juntada;
use SuppCore\AdministrativoBackend\Document\ProcessoVolume;
use SuppCore\AdministrativoBackend\Document\Setor;
use SuppCore\AdministrativoBackend\Document\TipoDocumento;
use SuppCore\AdministrativoBackend\Document\Usuario;
use SuppCore\AdministrativoBackend\Document\Volume;
use SuppCore\AdministrativoBackend\Elastic\ComponenteDigitalDocumentService;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class IndexacaoMessageHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class IndexacaoMessageHandler
{
    private IndexService $componenteDigitalIndex;
    private JuntadaResource $juntadaResource;
    private ComponenteDigitalResource $componenteDigitalResource;
    private string $indexName;
    private TransactionManager $transactionManager;
    private LoggerInterface $logger;

    /**
     * IndexacaoMessageHandler constructor.
     *
     * @param IndexService                     $componenteDigitalIndex
     * @param ComponenteDigitalResource        $componenteDigitalResource
     * @param TransactionManager               $transactionManager
     * @param JuntadaResource                  $juntadaResource
     * @param LoggerInterface                  $logger
     * @param ComponenteDigitalDocumentService $componenteDigitalDocumentService
     */
    public function __construct(
        IndexService $componenteDigitalIndex,
        ComponenteDigitalResource $componenteDigitalResource,
        TransactionManager $transactionManager,
        JuntadaResource $juntadaResource,
        LoggerInterface $logger,
        private ComponenteDigitalDocumentService $componenteDigitalDocumentService
    ) {
        $this->componenteDigitalIndex = $componenteDigitalIndex;
        $this->componenteDigitalResource = $componenteDigitalResource;
        $this->indexName = $this->componenteDigitalIndex->getIndexSettings()->getIndexName();
        $this->transactionManager = $transactionManager;
        $this->juntadaResource = $juntadaResource;
        $this->logger = $logger;
    }

    /**
     * @param IndexacaoMessage $message
     */
    public function __invoke(IndexacaoMessage $message)
    {
        try {
            $juntadaEntity = $this->juntadaResource->getRepository()->findOneBy(
                ['uuid' => $message->getUuid()]
            );

            if (!$juntadaEntity || !$juntadaEntity->getAtivo()) {
                return;
            }

            $dataHoraJuntada = $juntadaEntity->getCriadoEm();
            $documentoEntity = $juntadaEntity->getDocumento();

            foreach ($documentoEntity->getComponentesDigitais() as $componenteDigitalEntity) {
                $transactionId = $this->transactionManager->begin();
                $componenteDigitalEntity = $this->componenteDigitalResource->download(
                    $componenteDigitalEntity->getId(),
                    $transactionId
                );
                $this->transactionManager->resetTransaction($transactionId);
                $componenteDigitalDocument = $this->componenteDigitalDocumentService
                    ->convertToDocument($componenteDigitalEntity);

                $documentoDocument = new DocumentoOrigem();
                $documentoDocument->setId($documentoEntity->getId());
                $documentoDocument->setAutor($documentoEntity->getAutor());
                $documentoDocument->setRedator($documentoEntity->getRedator());
                $documentoDocument->setDestinatario($documentoEntity->getDestinatario());
                $tipoDocumentoDocument = new TipoDocumento();
                $tipoDocumentoDocument->setId($documentoEntity->getTipoDocumento()->getId());
                $tipoDocumentoDocument->setNome($documentoEntity->getTipoDocumento()->getNome());
                $documentoDocument->setTipoDocumento($tipoDocumentoDocument);

                if ($documentoEntity->getSetorOrigem()) {
                    $setorDocument = new Setor();
                    $setorDocument->setId($documentoEntity->getSetorOrigem()->getId());
                    $setorDocument->setNome($documentoEntity->getSetorOrigem()->getNome());
                    if ($documentoEntity->getSetorOrigem()->getEspecieSetor()) {
                        $especieSetorDocument = new EspecieSetor();
                        $especieSetorDocument->setId(
                            $documentoEntity->getSetorOrigem()->getEspecieSetor()->getId()
                        );
                        $especieSetorDocument->setNome(
                            $documentoEntity->getSetorOrigem()->getEspecieSetor()->getNome()
                        );
                        $setorDocument->setEspecieSetor($especieSetorDocument);
                    }
                    $documentoDocument->setSetorOrigem($setorDocument);
                }

                $juntadaDocument = new Juntada();
                $juntadaDocument->setId($juntadaEntity->getId());
                $juntadaDocument->setCriadoEm($juntadaEntity->getCriadoEm());
                $juntadaDocument->setNumeracaoSequencial(
                    $juntadaEntity->getNumeracaoSequencial()
                );
                if ($juntadaEntity->getCriadoPor()) {
                    $usuarioDocument = new Usuario();
                    $usuarioDocument->setId(
                        $juntadaEntity->getCriadoPor()->getId()
                    );
                    $usuarioDocument->setNome(
                        $juntadaEntity->getCriadoPor()->getNome()
                    );
                    $juntadaDocument->setCriadoPor($usuarioDocument);
                }

                $volumeDocument = new Volume();
                $volumeDocument->setId(
                    $juntadaEntity->getVolume()->getId()
                );
                $volumeDocument->setNumeracaoSequencial(
                    $juntadaEntity->getVolume()->getNumeracaoSequencial()
                );
                $processoDocument = new ProcessoVolume();
                $processoDocument->setId(
                    $juntadaEntity->getVolume()->getProcesso()->getId()
                );
                $processoDocument->setNUP(
                    $juntadaEntity->getVolume()->getProcesso()->getNUP()
                );
                $volumeDocument->setProcesso($processoDocument);
                $juntadaDocument->setVolume($volumeDocument);

                $documentoDocument->setJuntadaAtual($juntadaDocument);

                $componenteDigitalDocument->setDocumento($documentoDocument);

                $this->componenteDigitalIndex->persist($componenteDigitalDocument);
                // ajuste do indice de maneira dinâmica
                $this->componenteDigitalIndex->getIndexSettings()->setIndexName(
                    $this->indexName.'-'.$dataHoraJuntada->format('Ym')
                );
                $this->componenteDigitalIndex->commit(
                    'none',
                    ['pipeline' => 'attachment_componente_digital_conteudo']
                );
                $em = $this->componenteDigitalResource->getRepository()->getEntityManager();
                $conn = $em->getConnection();
                $conn->update(
                    'ad_componente_digital',
                    ['data_hora_indexacao' => $componenteDigitalEntity->getAtualizadoEm()->format(
                        $conn->getDatabasePlatform()->getDateTimeFormatString()
                    )],
                    ['id' => $componenteDigitalEntity->getId()]
                );
            }
        } catch (Throwable $t) {
            $this->logger->critical($t->getMessage().$t->getTraceAsString());
        }
    }
}
