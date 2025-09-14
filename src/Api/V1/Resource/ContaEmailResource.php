<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/ContaEmailResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Knp\Snappy\Pdf;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ContaEmail as DTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\EmailClient\EmailClientServiceInterface;
use SuppCore\AdministrativoBackend\EmailClient\EmailProcessoForm;
use SuppCore\AdministrativoBackend\Entity\ContaEmail as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\ContaEmailRepository as Repository;
use SuppCore\AdministrativoBackend\Repository\VolumeRepository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Utils\HTMLPurifier;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\VarExporter\LazyObjectInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/** @noinspection PhpHierarchyChecksInspection */

/**
 * Class ContaEmailResource.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @codingStandardsIgnoreStart
 *
 * @method Repository  getRepository(): Repository
 * @method Entity[]    find(array $criteria = null, array $orderBy = null, int $limit = null, int $offset = null, array $search = null, array $populate = null): array
 * @method Entity|null findOne(int $id, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity      create(RestDtoInterface $dto, string $transactionId, bool $skipValidation = null): EntityInterface
 * @method Entity      update(int $id, RestDtoInterface $dto, string $transactionId, bool $skipValidation = null): EntityInterface
 * @method Entity      delete(int $id, string $transactionId): EntityInterface
 * @method Entity      save(EntityInterface $entity, string $transactionId, bool $skipValidation = null): EntityInterface
 *
 * @codingStandardsIgnoreEnd
 */
class ContaEmailResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * @param Repository $repository
     * @param ValidatorInterface $validator
     * @param ParameterBagInterface $parameterBag
     * @param EmailClientServiceInterface $emailClientService
     * @param EspecieProcessoResource $especieProcessoResource
     * @param ModalidadeMeioResource $modalidadeMeioResource
     * @param DocumentoResource $documentoResource
     * @param TipoDocumentoResource $tipoDocumentoResource
     * @param ProcessoResource $processoResource
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param ClassificacaoResource $classificacaoResource
     * @param JuntadaResource $juntadaResource
     * @param TokenStorageInterface $tokenStorage
     * @param VolumeRepository $volumeRepository
     * @param Environment $twig
     * @param Pdf $pdfManager
     * @param LoggerInterface $logger
     * @param HTMLPurifier $purifier
     */
    public function __construct(Repository $repository,
                                ValidatorInterface $validator,
                                private ParameterBagInterface $parameterBag,
                                private EmailClientServiceInterface $emailClientService,
                                private EspecieProcessoResource $especieProcessoResource,
                                private ModalidadeMeioResource $modalidadeMeioResource,
                                private DocumentoResource $documentoResource,
                                private TipoDocumentoResource $tipoDocumentoResource,
                                private ProcessoResource $processoResource,
                                private ComponenteDigitalResource $componenteDigitalResource,
                                private ClassificacaoResource $classificacaoResource,
                                private JuntadaResource $juntadaResource,
                                private TokenStorageInterface $tokenStorage,
                                private VolumeRepository $volumeRepository,
                                private Environment $twig,
                                private Pdf $pdfManager,
                                private LoggerInterface $logger,
                                private HTMLPurifier $purifier) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(DTO::class);
    }

    /**
     * @param EmailProcessoForm $emailProcessoForm
     * @param string $transactionId
     * @param null $skipValidation
     * @return EntityInterface
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function emailProcessoForm(
        EmailProcessoForm $emailProcessoForm,
        string $transactionId,
        $skipValidation = null
    ): EntityInterface {
        $dbt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        $parent = isset($dbt[2]['class']) ? $dbt[2]['class'] : null;

        $this->logger->debug(
            'resource',
            [
                'type' => 'resource',
                'function' => 'emailProcessoForm',
                'id' => $this instanceof LazyObjectInterface ? get_parent_class($this) : get_class($this),
                'parent' => $parent,
                'object' => get_class($emailProcessoForm),
            ]
        );

        $skipValidation ??= false;
        $this->validateDto($emailProcessoForm, $skipValidation);
        $this->beforeEmailProcessoForm($emailProcessoForm, $emailProcessoForm, $transactionId);

        $message = $this->emailClientService->getMessage(
            $emailProcessoForm->getContaEmail(),
            $emailProcessoForm->getFolderIdentifier(),
            $emailProcessoForm->getMessageIdentifier(),
            true
        );

        if ($emailProcessoForm->getTipo() === 'novo_processo') {
            $setor = $this->tokenStorage
                ->getToken()
                ->getUser()
                ->getColaborador()
                ->getLotacoes()
                ->filter(fn($lotacao) => $lotacao->getPrincipal() === true)
                ->first()
                ->getSetor();

            $processoDTO = (new Processo())
                ->setTitulo(sprintf('NUP gerado a partir do e-mail %s', $message->getSubject()))
                ->setUnidadeArquivistica(ProcessoEntity::UA_DOCUMENTO_AVULSO)
                ->setTipoProtocolo(ProcessoEntity::TP_NOVO)
                ->setClassificacao(
                    $this->classificacaoResource
                        ->findOneBy(
                            ['codigo' => $this->parameterBag->get('constantes.entidades.classificacao.const_2')]
                        )
                )
                ->setEspecieProcesso(
                    $this->especieProcessoResource
                        ->findOneBy(
                            ['nome' => $this->parameterBag->get('constantes.entidades.especie_processo.const_1')]
                        )
                )
                ->setModalidadeMeio(
                    $this->modalidadeMeioResource
                        ->findOneBy(
                            ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_meio.const_1')]
                        )
                )
                ->setSetorAtual($setor);

            $processoEntity = $this->processoResource->create($processoDTO, $transactionId);
            $volume = $processoDTO->getVolumes()[0];
            $volume->setProcesso($processoEntity);
        } else {
            $processoEntity = $emailProcessoForm->getProcesso();
            $volume = $this->volumeRepository->findVolumeAbertoByProcessoId($processoEntity->getId());
            $setor = $processoEntity->getSetorAtual();
        }

        $componenteDigitalConfig = $this->parameterBag->get(
            'supp_core.administrativo_backend.componente_digital_extensions'
        );

        $attachments = array_filter(
            $message->getAttachments(),
            fn($attachment) => isset($componenteDigitalConfig[$attachment->getExtension()]['allowUpload'])
                && $componenteDigitalConfig[$attachment->getExtension()]['allowUpload'] == true
        );

        $conteudo = $this->pdfManager->getOutputFromHtml(
            $this->twig->render(
                'Resources/EmailClient/email.twig',
                [
                    'subject' => $message->getSubject(),
                    'body' => $this->purifier->sanitize($message->getHtmlBody()),
                    'from' => $message->getFrom()->getFull(),
                    'to' => array_map(
                        fn($address) => $address->getFull(),
                        ($message->getTo() ?? [])
                    ),
                    'cc' => array_map(
                        fn($address) => $address->getFull(),
                        ($message->getCc() ?? [])
                    ),
                    'bcc' => array_map(
                        fn($address) => $address->getFull(),
                        ($message->getBcc() ?? [])
                    ),
                    'attachments' => array_map(
                        fn($attachment) => $attachment->getFileName(),
                        ($attachments ?? [])
                    ),
                    'date' => $message->getDate(),
                ]
            )
        );
        $tipoDocumento = $this->tipoDocumentoResource->retornaTipoDocumentoByFilename(
            'e-mail.pdf',
            'pdf'
        );
        if (!$tipoDocumento) {
            $tipoDocumento = $this->tipoDocumentoResource
                ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_documento.const_1')]);
        }
        $documentoDTO = (new Documento())
            ->setTipoDocumento($tipoDocumento)
            ->setProcessoOrigem($processoEntity)
            ->setSetorOrigem($setor);

        $documento = $this->documentoResource->create($documentoDTO, $transactionId);

        $componenteDigitalDTO = (new ComponenteDigital())
            ->setExtensao('pdf')
            ->setFileName('e-mail.pdf')
            ->setMimetype('application/pdf')
            ->setConteudo($conteudo)
            ->setHash(hash('SHA256', $conteudo))
            ->setTamanho(strlen($conteudo))
            ->setDocumento($documento)
            ->setProcessoOrigem($processoEntity);

        $componenteDigitalEntity = $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);

        foreach ($attachments as $attachment) {
            $conteudoAttachment = explode('base64,', $attachment->getContent());
            $componenteDigitalDTOAttachment = (new ComponenteDigital())
                ->setExtensao($attachment->getExtension())
                ->setFileName($attachment->getFileName())
                ->setMimetype($attachment->getMimeType())
                ->setConteudo(base64_decode($conteudoAttachment[1]))
                ->setTamanho(strlen($conteudoAttachment[1]))
                ->setHash(hash('SHA256', $conteudoAttachment[1]))
                ->setDocumentoOrigem($componenteDigitalEntity->getDocumento());

            $this->componenteDigitalResource->create($componenteDigitalDTOAttachment, $transactionId);
        }

        $juntadaDTO = (new Juntada())
            ->setDocumento($documento)
            ->setDescricao(
                sprintf(
                    'JUNTADA DE E-MAIL POR %s EM %s',
                    $this->tokenStorage->getToken()->getUser()->getNome(),
                    (new DateTime())->format('d/m/Y H:i')
                )
            )
            ->setVolume($volume);

        $this->juntadaResource->create($juntadaDTO, $transactionId);

        $emailProcessoForm->setProcesso($processoEntity);
        $this->afterEmailProcessoForm($emailProcessoForm, $emailProcessoForm, $transactionId);

        return $processoEntity;
    }

    /**
     * Before lifecycle method for emailProcessoForm method.
     * @param RestDtoInterface|null $dto
     * @param EntityInterface $entity
     * @param string $transactionId
     */
    protected function beforeEmailProcessoForm(
        ?RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertEmailProcessoForm');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeEmailProcessoForm');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeEmailProcessoForm');
    }

    /**
     * After lifecycle method for emailProcessoForm method.
     * @param RestDtoInterface|null $dto
     * @param EntityInterface $entity
     * @param string $transactionId
     */
    protected function afterEmailProcessoForm(
        ?RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void
    {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterEmailProcessoForm');
    }
}
