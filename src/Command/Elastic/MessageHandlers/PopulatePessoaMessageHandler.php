<?php /** @noinspection PhpUnused */

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Command\Elastic\MessageHandlers;

use ONGR\ElasticsearchBundle\Service\IndexService;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Command\Elastic\Messages\PopulatePessoaMessage;
use SuppCore\AdministrativoBackend\Document\ModalidadeGeneroPessoa;
use SuppCore\AdministrativoBackend\Document\ModalidadeQualificacaoPessoa;
use SuppCore\AdministrativoBackend\Document\Pessoa as PessoaDocument;
use SuppCore\AdministrativoBackend\Document\VinculacaoPessoaBarramento;
use SuppCore\AdministrativoBackend\Document\OrigemDados as OrigemDadosDocument;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class PopulatePessoaMessageHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class PopulatePessoaMessageHandler
{
    private IndexService $pessoaIndex;
    private PessoaResource $pessoaResource;
    private LoggerInterface $logger;

    /**
     * PopulatePessoaMessageHandler constructor.
     *
     * @param IndexService   $pessoaIndex
     * @param PessoaResource $pessoaResource
     * @param LoggerInterface $logger
     */
    public function __construct(
        IndexService $pessoaIndex,
        PessoaResource $pessoaResource,
        LoggerInterface $logger
    ) {
        $this->pessoaIndex = $pessoaIndex;
        $this->pessoaResource = $pessoaResource;
        $this->logger = $logger;
    }

    /**
     * @param PopulatePessoaMessage $message
     */
    public function __invoke(PopulatePessoaMessage $message)
    {
        $startId = $message->getStartId();
        $endId = $message->getEndId();
        $currentId = $startId;

        while ($currentId <= $endId) {
            try {
                $pessoaEntity = $this->pessoaResource->getRepository()->find($currentId);

                if (!$pessoaEntity) {
                    ++$currentId;
                    continue;
                }

                $pessoaDocument = new PessoaDocument();
                $pessoaDocument->setId($pessoaEntity->getId());
                $pessoaDocument->setNome($pessoaEntity->getNome());
                $pessoaDocument->setPessoaValidada($pessoaEntity->getPessoaValidada());
                $pessoaDocument->setPessoaConveniada($pessoaEntity->getPessoaConveniada());
                $pessoaDocument->setNumeroDocumentoPrincipal($pessoaEntity->getNumeroDocumentoPrincipal());
                $pessoaDocument->setDataNascimento($pessoaEntity->getDataNascimento());
                $pessoaDocument->setDataObito($pessoaEntity->getDataObito());
                $pessoaDocument->setNomeGenitora($pessoaEntity->getNomeGenitora());

                if ($pessoaEntity->getVinculacaoPessoaBarramento()) {
                    $vinculacaoPessoaBarramentoDocument = new VinculacaoPessoaBarramento();
                    $vinculacaoPessoaBarramentoDocument->setId($pessoaEntity->getVinculacaoPessoaBarramento()->getId());
                    $vinculacaoPessoaBarramentoDocument->setRepositorio(
                        $pessoaEntity->getVinculacaoPessoaBarramento()->getRepositorio()
                    );
                    $vinculacaoPessoaBarramentoDocument->setEstrutura(
                        $pessoaEntity->getVinculacaoPessoaBarramento()->getEstrutura()
                    );
                    $vinculacaoPessoaBarramentoDocument->setNomeEstrutura(
                        $pessoaEntity->getVinculacaoPessoaBarramento()->getNomeEstrutura()
                    );
                    $pessoaDocument->setVinculacaoPessoaBarramento($vinculacaoPessoaBarramentoDocument);
                }
                if ($pessoaEntity->getModalidadeGeneroPessoa()) {
                    $modalidadeGeneroPessoaDocument = new ModalidadeGeneroPessoa();
                    $modalidadeGeneroPessoaDocument->setId($pessoaEntity->getModalidadeGeneroPessoa()->getId());
                    $modalidadeGeneroPessoaDocument->setValor($pessoaEntity->getModalidadeGeneroPessoa()->getValor());
                    $pessoaDocument->setModalidadeGeneroPessoa($modalidadeGeneroPessoaDocument);
                }
                if ($pessoaEntity->getModalidadeQualificacaoPessoa()) {
                    $modalidadeQualificacaoPessoaDocument = new ModalidadeQualificacaoPessoa();
                    $modalidadeQualificacaoPessoaDocument->setId(
                        $pessoaEntity->getModalidadeQualificacaoPessoa()->getId()
                    );
                    $modalidadeQualificacaoPessoaDocument->setValor(
                        $pessoaEntity->getModalidadeQualificacaoPessoa()->getValor()
                    );
                    $pessoaDocument->setModalidadeQualificacaoPessoa($modalidadeQualificacaoPessoaDocument);
                }
                if ($pessoaEntity->getOrigemDados()) {
                    $origemDadosDocument = new OrigemDadosDocument();
                    $origemDadosDocument->setId($pessoaEntity->getOrigemDados()->getId());
                    $origemDadosDocument->setFonteDados($pessoaEntity->getOrigemDados()->getFonteDados());
                    $origemDadosDocument->setIdExterno($pessoaEntity->getOrigemDados()->getIdExterno());
                    $pessoaDocument->setOrigemDados($origemDadosDocument);
                }

                $this->pessoaIndex->persist($pessoaDocument);

                $em = $this->pessoaResource->getRepository()->getEntityManager();
                $conn = $em->getConnection();
                $conn->update(
                    'ad_pessoa',
                    ['data_hora_indexacao' => $pessoaEntity->getAtualizadoEm()->format(
                        $conn->getDatabasePlatform()->getDateTimeFormatString()
                    )],
                    ['id' => $pessoaEntity->getId()]
                );
            } catch (Throwable $t) {
                $this->logger->critical($t->getMessage().$t->getTraceAsString());
            }
            ++$currentId;
        }

        $this->pessoaIndex->commit('none');
        $this->pessoaResource->getRepository()->getEntityManager()->clear();
    }
}
