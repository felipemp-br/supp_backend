<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Repositorio/MessageHandler/IndexacaoMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Repositorio\MessageHandler;

use ONGR\ElasticsearchBundle\Service\IndexService;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\RepositorioResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Repositorio\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\Document\ComponenteDigital;
use SuppCore\AdministrativoBackend\Document\Documento;
use SuppCore\AdministrativoBackend\Document\EspecieSetor;
use SuppCore\AdministrativoBackend\Document\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Document\ModalidadeRepositorio;
use SuppCore\AdministrativoBackend\Document\Repositorio as RepositorioDocument;
use SuppCore\AdministrativoBackend\Document\Setor;
use SuppCore\AdministrativoBackend\Document\TipoDocumento;
use SuppCore\AdministrativoBackend\Document\Unidade;
use SuppCore\AdministrativoBackend\Document\Usuario;
use SuppCore\AdministrativoBackend\Document\VinculacaoRepositorio;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio as VinculacaoRepositorioEntity;
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
    private IndexService $repositorioIndex;
    private RepositorioResource $repositorioResource;
    private ComponenteDigitalResource $componenteDigitalResource;
    private TransactionManager $transactionManager;
    private LoggerInterface $logger;

    /**
     * IndexacaoMessageHandler constructor.
     */
    public function __construct(
        IndexService $repositorioIndex,
        RepositorioResource $repositorioResource,
        ComponenteDigitalResource $componenteDigitalResource,
        TransactionManager $transactionManager,
        LoggerInterface $logger
    ) {
        $this->repositorioIndex = $repositorioIndex;
        $this->repositorioResource = $repositorioResource;
        $this->componenteDigitalResource = $componenteDigitalResource;
        $this->transactionManager = $transactionManager;
        $this->logger = $logger;
    }

    /**
     * @param IndexacaoMessage $message
     */
    public function __invoke(IndexacaoMessage $message)
    {
        try {
            $repositorioEntity = $this->repositorioResource->getRepository()->findOneBy(
                ['uuid' => $message->getUuid()]
            );

            if ($repositorioEntity) {
                $repositorioDocument = new RepositorioDocument();
                $repositorioDocument->setId($repositorioEntity->getId());
                $repositorioDocument->setNome($repositorioEntity->getNome());
                $repositorioDocument->setDescricao($repositorioEntity->getDescricao());
                $repositorioDocument->setAtivo($repositorioEntity->getAtivo());

                $modalidadeRepositorioDocument = new ModalidadeRepositorio();
                $modalidadeRepositorioDocument->setId($repositorioEntity->getModalidadeRepositorio()->getId());
                $modalidadeRepositorioDocument->setValor($repositorioEntity->getModalidadeRepositorio()->getValor());

                $repositorioDocument->setModalidadeRepositorio($modalidadeRepositorioDocument);

                /** @var VinculacaoRepositorioEntity $vinculacaoRepositorioEntity */
                foreach ($repositorioEntity->getVinculacoesRepositorios() as $vinculacaoRepositorioEntity) {
                    if ($vinculacaoRepositorioEntity->getSetor()) {
                        $setorDocument = new Setor();
                        $setorDocument->setId($vinculacaoRepositorioEntity->getSetor()->getId());
                        $setorDocument->setNome($vinculacaoRepositorioEntity->getSetor()->getNome());

                        $unidadeDocument = new Unidade();
                        $unidadeDocument->setId($vinculacaoRepositorioEntity->getSetor()->getUnidade()->getId());
                        $unidadeDocument->setNome($vinculacaoRepositorioEntity->getSetor()->getUnidade()->getNome());
                        $setorDocument->setUnidade($unidadeDocument);

                        $especieSetorDocument = new EspecieSetor();
                        $especieSetorDocument->setId(
                            $vinculacaoRepositorioEntity->getSetor()->getEspecieSetor()->getId()
                        );
                        $especieSetorDocument->setNome(
                            $vinculacaoRepositorioEntity->getSetor()->getEspecieSetor()->getNome()
                        );
                        $setorDocument->setEspecieSetor($especieSetorDocument);

                        $vinculacaoRepositorioDocument = new VinculacaoRepositorio();
                        $vinculacaoRepositorioDocument->setId($vinculacaoRepositorioEntity->getId());
                        $vinculacaoRepositorioDocument->setSetor($setorDocument);

                        $repositorioDocument->setVinculacoesRepositorios($vinculacaoRepositorioDocument);
                    }
                    if ($vinculacaoRepositorioEntity->getUsuario()) {
                        $usuarioDocument = new Usuario();
                        $usuarioDocument->setId($vinculacaoRepositorioEntity->getUsuario()->getId());
                        $usuarioDocument->setNome($vinculacaoRepositorioEntity->getUsuario()->getNome());

                        $vinculacaoRepositorioDocument = new VinculacaoRepositorio();
                        $vinculacaoRepositorioDocument->setId($vinculacaoRepositorioEntity->getId());
                        $vinculacaoRepositorioDocument->setUsuario($usuarioDocument);

                        $repositorioDocument->setVinculacoesRepositorios($vinculacaoRepositorioDocument);
                    }

                    if ($vinculacaoRepositorioEntity->getModalidadeOrgaoCentral()) {
                        $modalidadeOrgaoCentralDocument = new ModalidadeOrgaoCentral();
                        $modalidadeOrgaoCentralDocument->setId(
                            $vinculacaoRepositorioEntity->getModalidadeOrgaoCentral()->getId()
                        );
                        $modalidadeOrgaoCentralDocument->setValor(
                            $vinculacaoRepositorioEntity->getModalidadeOrgaoCentral()->getValor()
                        );

                        $vinculacaoRepositorioDocument = new VinculacaoRepositorio();
                        $vinculacaoRepositorioDocument->setId($vinculacaoRepositorioEntity->getId());
                        $vinculacaoRepositorioDocument->setModalidadeOrgaoCentral($modalidadeOrgaoCentralDocument);

                        $repositorioDocument->setVinculacoesRepositorios($vinculacaoRepositorioDocument);
                    }

                    if ($vinculacaoRepositorioEntity->getEspecieSetor()) {
                        $especieSetorDocument = new EspecieSetor();
                        $especieSetorDocument->setId($vinculacaoRepositorioEntity->getEspecieSetor()->getId());
                        $especieSetorDocument->setNome($vinculacaoRepositorioEntity->getEspecieSetor()->getNome());

                        $vinculacaoRepositorioDocument = new VinculacaoRepositorio();
                        $vinculacaoRepositorioDocument->setId($vinculacaoRepositorioEntity->getId());
                        $vinculacaoRepositorioDocument->setEspecieSetor($especieSetorDocument);

                        $repositorioDocument->setVinculacoesRepositorios($vinculacaoRepositorioDocument);
                    }
                }

                $documentoDocument = new Documento();
                $documentoDocument->setId($repositorioEntity->getDocumento()->getId());

                $tipoDocumentoDocument = new TipoDocumento();
                $tipoDocumentoDocument->setId($repositorioEntity->getDocumento()->getTipoDocumento()->getId());
                $tipoDocumentoDocument->setNome($repositorioEntity->getDocumento()->getTipoDocumento()->getNome());

                $documentoDocument->setTipoDocumento($tipoDocumentoDocument);

                $componenteDigitalDocument = new ComponenteDigital();
                $componenteDigitalDocument->setId(
                    $repositorioEntity->getDocumento()->getComponentesDigitais()[0]->getId()
                );

                $transactionId = $this->transactionManager->begin();
                $componenteDigitalDocument->setConteudo(
                    base64_encode(
                        $this->componenteDigitalResource->download(
                            $repositorioEntity->getDocumento()->getComponentesDigitais()[0]->getId(),
                            $transactionId
                        )->getConteudo()
                    )
                );
                $this->transactionManager->commit($transactionId);

                $documentoDocument->setComponentesDigitais($componenteDigitalDocument);

                $repositorioDocument->setDocumento($documentoDocument);

                $this->repositorioIndex->persist($repositorioDocument);
                $this->repositorioIndex->commit(
                    'none',
                    [
                        'pipeline' => 'attachment_documento_componentes_digitais_conteudo',
                    ]
                );

                $em = $this->repositorioResource->getRepository()->getEntityManager();
                $conn = $em->getConnection();
                $conn->update(
                    'ad_repositorio',
                    ['data_hora_indexacao' => $repositorioEntity->getAtualizadoEm()->format(
                        $conn->getDatabasePlatform()->getDateTimeFormatString()
                    )],
                    ['id' => $repositorioEntity->getId()]
                );
            }
        } catch (Throwable $t) {
            $this->logger->critical($t->getMessage().$t->getTraceAsString());
        }
    }
}
