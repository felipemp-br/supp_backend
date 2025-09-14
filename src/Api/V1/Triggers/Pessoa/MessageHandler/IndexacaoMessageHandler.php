<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Pessoa/MessageHandler/IndexacaoMessage.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Pessoa\MessageHandler;

use ONGR\ElasticsearchBundle\Service\IndexService;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Pessoa\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\Document\ModalidadeGeneroPessoa;
use SuppCore\AdministrativoBackend\Document\ModalidadeQualificacaoPessoa;
use SuppCore\AdministrativoBackend\Document\OrigemDados as OrigemDadosDocument;
use SuppCore\AdministrativoBackend\Document\Pessoa as PessoaDocument;
use SuppCore\AdministrativoBackend\Document\VinculacaoPessoaBarramento;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class IndexacaoMessageHandler.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class IndexacaoMessageHandler
{
    private IndexService $pessoaIndex;
    private PessoaResource $pessoaResource;
    private LoggerInterface $logger;

    /**
     * Trigger0005 constructor.
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
     * @param IndexacaoMessage $message
     */
    public function __invoke(IndexacaoMessage $message)
    {
        try {
            $pessoaEntity = $this->pessoaResource->getRepository()->findOneBy(['uuid' => $message->getUuid()]);

            if ($pessoaEntity) {
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
                $this->pessoaIndex->commit('none');

                $em = $this->pessoaResource->getRepository()->getEntityManager();
                $conn = $em->getConnection();
                $conn->update(
                    'ad_pessoa',
                    ['data_hora_indexacao' => $pessoaEntity->getAtualizadoEm()->format(
                        $conn->getDatabasePlatform()->getDateTimeFormatString()
                    )],
                    ['id' => $pessoaEntity->getId()]
                );
            }
        } catch (Throwable $t) {
            $this->logger->critical($t->getMessage().$t->getTraceAsString());
        }
    }
}
