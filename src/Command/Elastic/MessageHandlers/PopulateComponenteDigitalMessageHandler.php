<?php /** @noinspection PhpUnused */

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\Elastic\MessageHandlers;

use ONGR\ElasticsearchBundle\Service\IndexService;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulateComponenteDigitalMessage;
use SuppCore\AdministrativoBackend\Document\ComponenteDigital as ComponenteDigitalDocument;
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
 * Class PopulateComponenteDigitalMessageHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class PopulateComponenteDigitalMessageHandler
{
    private IndexService $componenteDigitalIndex;
    private ComponenteDigitalResource $componenteDigitalResource;
    private string $indexName;
    private TransactionManager $transactionManager;
    private LoggerInterface $logger;

    /**
     * PopulateComponenteDigitalMessageHandler constructor.
     * @param IndexService $componenteDigitalIndex
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param TransactionManager $transactionManager
     * @param LoggerInterface $logger
     * @param ComponenteDigitalDocumentService $componenteDigitalDocumentService
     */
    public function __construct(
        IndexService $componenteDigitalIndex,
        ComponenteDigitalResource $componenteDigitalResource,
        TransactionManager $transactionManager,
        LoggerInterface $logger,
        private ComponenteDigitalDocumentService $componenteDigitalDocumentService
    ) {
        $this->componenteDigitalIndex = $componenteDigitalIndex;
        $this->componenteDigitalResource = $componenteDigitalResource;
        $this->indexName = $this->componenteDigitalIndex->getIndexSettings()->getIndexName();
        $this->transactionManager = $transactionManager;
        $this->logger = $logger;
    }

    /**
     * @param PopulateComponenteDigitalMessage $message
     */
    public function __invoke(PopulateComponenteDigitalMessage $message)
    {
        $startId = $message->getStartId();
        $endId = $message->getEndId();
        $currentId = $startId;

        while ($currentId <= $endId) {
            try {
                $componenteDigitalEntity = $this->componenteDigitalResource->getRepository()->find(
                    $currentId
                );

                if (!$componenteDigitalEntity) {
                    ++$currentId;
                    continue;
                }

                if (!$componenteDigitalEntity->getDocumento()->getJuntadaAtual() ||
                    !$componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getAtivo()) {
                    ++$currentId;
                    continue;
                }

                $dataHoraJuntada = $componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getCriadoEm();

                $transactionId = $this->transactionManager->begin();
                $componenteDigitalEntity = $this->componenteDigitalResource->download($currentId, $transactionId);
                $this->transactionManager->resetTransaction($transactionId);

                $componenteDigitalDocument = $this->componenteDigitalDocumentService
                    ->convertToDocument($componenteDigitalEntity);

                $documentoDocument = new DocumentoOrigem();
                $documentoDocument->setId($componenteDigitalEntity->getDocumento()->getId());
                $documentoDocument->setAutor($componenteDigitalEntity->getDocumento()->getAutor());
                $documentoDocument->setRedator($componenteDigitalEntity->getDocumento()->getRedator());
                $documentoDocument->setDestinatario($componenteDigitalEntity->getDocumento()->getDestinatario());
                $tipoDocumentoDocument = new TipoDocumento();
                $tipoDocumentoDocument->setId($componenteDigitalEntity->getDocumento()->getTipoDocumento()->getId());
                $tipoDocumentoDocument->setNome(
                    $componenteDigitalEntity->getDocumento()->getTipoDocumento()->getNome()
                );
                $documentoDocument->setTipoDocumento($tipoDocumentoDocument);

                if ($componenteDigitalEntity->getDocumento()->getSetorOrigem()) {
                    $setorDocument = new Setor();
                    $setorDocument->setId($componenteDigitalEntity->getDocumento()->getSetorOrigem()->getId());
                    $setorDocument->setNome($componenteDigitalEntity->getDocumento()->getSetorOrigem()->getNome());
                    $especieSetorDocument = new EspecieSetor();
                    $especieSetorDocument->setId(
                        $componenteDigitalEntity->getDocumento()->getSetorOrigem()->getEspecieSetor()->getId()
                    );
                    $especieSetorDocument->setNome(
                        $componenteDigitalEntity->getDocumento()->getSetorOrigem()->getEspecieSetor()->getNome()
                    );
                    $setorDocument->setEspecieSetor($especieSetorDocument);
                    $documentoDocument->setSetorOrigem($setorDocument);
                }

                $juntadaDocument = new Juntada();
                $juntadaDocument->setId($componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getId());
                $juntadaDocument->setCriadoEm(
                    $componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getCriadoEm()
                );
                $juntadaDocument->setNumeracaoSequencial(
                    $componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getNumeracaoSequencial()
                );
                if ($componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getCriadoPor()) {
                    $usuarioDocument = new Usuario();
                    $usuarioDocument->setId(
                        $componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getCriadoPor()->getId()
                    );
                    $usuarioDocument->setNome(
                        $componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getCriadoPor()->getNome()
                    );
                    $juntadaDocument->setCriadoPor($usuarioDocument);
                }

                $volumeDocument = new Volume();
                $volumeDocument->setId(
                    $componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getVolume()->getId()
                );
                $volumeDocument->setNumeracaoSequencial(
                    $componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getVolume()->getNumeracaoSequencial()
                );
                $processoDocument = new ProcessoVolume();
                $processoDocument->setId(
                    $componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getVolume()->getProcesso()->getId()
                );
                $processoDocument->setNUP(
                    $componenteDigitalEntity->getDocumento()->getJuntadaAtual()->getVolume()->getProcesso()->getNUP()
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

                $em = $this->componenteDigitalResource->getRepository()->getEntityManager();
                $conn = $em->getConnection();
                $conn->update(
                    'ad_componente_digital',
                    ['data_hora_indexacao' => $componenteDigitalEntity->getAtualizadoEm()->format(
                        $conn->getDatabasePlatform()->getDateTimeFormatString()
                    )],
                    ['id' => $componenteDigitalEntity->getId()]
                );
            } catch (Throwable $t) {
                $this->logger->critical($t->getMessage().$t->getTraceAsString());
            }
            ++$currentId;
        }

        $this->componenteDigitalIndex->commit(
            'none',
            ['pipeline' => 'attachment_componente_digital_conteudo']
        );
        $this->componenteDigitalResource->getRepository()->getEntityManager()->clear();
    }
}
