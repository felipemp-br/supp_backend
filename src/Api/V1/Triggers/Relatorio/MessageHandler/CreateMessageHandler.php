<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Relatorio/MessageHandler/CreateMessageHandler.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Relatorio\MessageHandler;

use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Psr\Log\LoggerInterface as Logger;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Relatorio;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\RelatorioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Relatorio\Message\CreateMessage;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\Relatorio as RelatorioEntity;
use SuppCore\AdministrativoBackend\Helpers\TipoRelatorio\TipoRelatorioManager;
use SuppCore\AdministrativoBackend\Reports\ReportsFormatManager;
use SuppCore\AdministrativoBackend\Repository\ClassificacaoRepository;
use SuppCore\AdministrativoBackend\Repository\RelatorioRepository;
use SuppCore\AdministrativoBackend\Repository\TipoDocumentoRepository;
use SuppCore\AdministrativoBackend\Security\InternalLogInService;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

/**
 * Class CreateMessageHandler.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AsMessageHandler]
class CreateMessageHandler
{
    private EntityManagerInterface $entityManager;

    /**
     * CreateMessageHandler constructor.
     *
     * @param HubInterface                  $hub
     * @param RelatorioRepository           $relatorioRepository
     * @param RelatorioResource             $relatorioResource
     * @param DocumentoResource             $documentoResource
     * @param ComponenteDigitalResource     $componenteDigitalResource
     * @param TipoDocumentoRepository       $tipoDocumentoRepository
     * @param TransactionManager            $transactionManager
     * @param UsuarioResource               $usuarioResource
     * @param ReportsFormatManager          $reportsFormatManager
     * @param NotificacaoResource           $notificacaoResource
     * @param ModalidadeNotificacaoResource $modalidadeNotificacaoRepository
     * @param Logger                        $logger
     * @param TipoNotificacaoResource       $tipoNotificacaoResource
     * @param ClassificacaoRepository       $classificacaoRepository
     * @param ParameterBagInterface         $parameterBag
     * @param TipoRelatorioManager          $tipoRelatorioManager
     */
    public function __construct(
        private HubInterface $hub,
        private RelatorioRepository $relatorioRepository,
        private RelatorioResource $relatorioResource,
        private DocumentoResource $documentoResource,
        private ComponenteDigitalResource $componenteDigitalResource,
        private TipoDocumentoRepository $tipoDocumentoRepository,
        private TransactionManager $transactionManager,
        private UsuarioResource $usuarioResource,
        private ReportsFormatManager $reportsFormatManager,
        private NotificacaoResource $notificacaoResource,
        private ModalidadeNotificacaoResource $modalidadeNotificacaoRepository,
        private Logger $logger,
        private TipoNotificacaoResource $tipoNotificacaoResource,
        private ClassificacaoRepository $classificacaoRepository,
        private ParameterBagInterface $parameterBag,
        private readonly TipoRelatorioManager $tipoRelatorioManager,
        private readonly InternalLogInService $internalLogInService
    ) {
        $this->entityManager = $this->tipoDocumentoRepository->getEntityManager();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws Exception
     */
    public function __invoke(CreateMessage $message)
    {
        $workloadDTORelatorio = unserialize($message->getParametrosDTO());

        $usuario = $this->usuarioResource->getRepository()->findOneBy(
            ['username' => $workloadDTORelatorio['usuario']]
        );

        $this->internalLogInService->logUserIn($usuario);

        $relatorioEntity = $this->relatorioRepository->findOneBy(['uuid' => $message->getUuid()]);
        $tipoDocumento = $this->tipoDocumentoRepository
            ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_documento.const_3')]);
        $modalidadeNotificacao = $this->modalidadeNotificacaoRepository
            ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]);
        $tipoNotificacao = $this->tipoNotificacaoResource
            ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_3')]);

        $notificacaoDTO = (new Notificacao())
                ->setDestinatario($usuario)
                ->setTipoNotificacao($tipoNotificacao)
                ->setModalidadeNotificacao($modalidadeNotificacao);

        try {
            $transactionId = $this->transactionManager->begin();

            $documentoDTO = new Documento();
            $documentoDTO->setTipoDocumento($tipoDocumento);
            $documento = $this->documentoResource->create($documentoDTO, $transactionId);

            $documento->setRelatorio($relatorioEntity);

            $parametros = $this->processaParametros(
                $workloadDTORelatorio['parametros']
            );

            $driver = $this->tipoRelatorioManager->getTipoRelatorioDriver($relatorioEntity->getTipoRelatorio());

            if (!$driver) {
                $query = $this->entityManager->createQuery($relatorioEntity->getTipoRelatorio()->getDQL());
                if (is_array($workloadDTORelatorio['parametros'])) {
                    foreach ($workloadDTORelatorio['parametros'] as $nome => $parametro) {
                        $query->setParameter($nome, $parametro['value']);
                    }
                }
                if ($relatorioEntity->getTipoRelatorio()->getLimite()) {
                    $query->setMaxResults($relatorioEntity->getTipoRelatorio()->getLimite());
                }
                $arrayData = $query->getArrayResult();
            } else {
                $arrayData = $driver->getArrayResult(
                    $relatorioEntity->getTipoRelatorio(),
                    $parametros
                );
            }

            if ($relatorioEntity->getTipoRelatorio()->getNome() ===
                $this->parameterBag->get('constantes.entidades.tipo_relatorio.const_1')) {
                foreach ($arrayData as $key => $value) {
                    $id = $value['classificacao']['classificacao']['id'];
                    $classificao = $this->classificacaoRepository->find($id);
                    $arrayData[$key]['classificacao'] = $classificao->getNome();
                    $arrayData[$key]['codigo'] = $classificao->getCodigo();
                }
            }
            $componenteDigitalEntity = (new ComponenteDigitalEntity())
                ->setEditavel(false)
                ->setNivelComposicao(3)
                ->setDocumento($documento);

            $this->reportsFormatManager->exportToFormat(
                $workloadDTORelatorio['formato'],
                $componenteDigitalEntity,
                $usuario->getNome(),
                $relatorioEntity->getTipoRelatorio()->getNome(),
                $arrayData,
                $parametros,
                $relatorioEntity->getTipoRelatorio()->getTemplateHTML()
            );

            /** @var ComponenteDigitalDTO $componenteDigitalDTO */
            $componenteDigitalDTO = $this->componenteDigitalResource->getDtoForEntity(
                -1,
                ComponenteDigitalDTO::class,
                null,
                $componenteDigitalEntity
            );

            $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);

            /** @var Relatorio $relatorioDTO */
            $relatorioDTO = $this->relatorioResource->getDtoForEntity(
                $relatorioEntity->getId(),
                Relatorio::class
            );
            $relatorioDTO->setDocumento($documento);
            $relatorioDTO->setStatus(RelatorioEntity::STATUS_SUCESSO);
            $this->relatorioResource->update($relatorioEntity->getId(), $relatorioDTO, $transactionId);

            $contexto = json_encode(['id' => $relatorioEntity->getId()]);
            $notificacaoDTO
                ->setConteudo(
                    'GERAÇÃO DO RELATÓRIO '
                    .$componenteDigitalDTO->getFileName()
                    .' CONCLUÍDA!'
                )
                ->setContexto($contexto);
        } catch (Throwable $e) {
            $this->logger->critical('Erro ao Gerar Relatório: '.$e->getMessage().$e->getTraceAsString());
            $notificacaoDTO
                ->setConteudo(
                    'HOUVE ERRO NA GERAÇÃO DO RELATÓRIO!'
                );

            $transactionId = $this->transactionManager->getCurrentTransactionId();

            /** @var Relatorio $relatorioDTO */
            $relatorioDTO = $this->relatorioResource->getDtoForEntity(
                $relatorioEntity->getId(),
                Relatorio::class
            );
            $relatorioDTO->setStatus(RelatorioEntity::STATUS_ERRO);
            $this->relatorioResource->update($relatorioEntity->getId(), $relatorioDTO, $transactionId);
        } finally {
            $this->notificacaoResource->create($notificacaoDTO, $transactionId);
            $this->transactionManager->commit($transactionId);

            $update = new Update(
                $usuario->getUsername(),
                json_encode(
                    [
                        'relatorio_create' => [
                            'action' => $message->getAction(),
                            'relatorio' => $relatorioEntity->getId(),
                        ],
                    ]
                )
            );

            $this->hub->publish($update);
        }
    }

    /**
     * @param array $rawParams
     *
     * @return array
     *
     * @throws Exception
     */
    private function processaParametros(?array $rawParams): array
    {
        $parametros = [];

        if($rawParams === null) {
            return $parametros;
        }

        foreach ($rawParams as $rawParam) {
            switch ($rawParam['type']) {
                case 'dateTime':
                    $data = new DateTime(str_replace('T', ' ', $rawParam['value']));
                    $parametros[$rawParam['name']]['value'] = $data->format('Y-m-d H:i:s');
                    $parametros[$rawParam['name']]['label'] = $data->format('d-m-Y H:i:s');
                    break;
                case 'entity':
                    $parametros[$rawParam['name']]['value'] = $this->entityManager->getRepository(
                        $rawParam['class']
                    )->find($rawParam['value']);
                    $getter = $rawParam['getter'];
                    $parametros[$rawParam['name']]['label'] =
                        ('getPessoa' !== $getter) ?
                            $parametros[$rawParam['name']]['value']->$getter() :
                            $parametros[$rawParam['name']]['value']->$getter()->getNome();
                    break;
                default:
                    $parametros[$rawParam['name']]['value'] = $rawParam['value'];
                    $parametros[$rawParam['name']]['label'] = $rawParam['value'];
            }
        }

        return $parametros;
    }
}
