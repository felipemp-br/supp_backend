<?php
/**
 * @noinspection LongLine
 *
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);
/**
 * /src/Api/V1/Resource/DocumentoResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Doctrine\ORM\TransactionRequiredException;
use Exception;
use ReflectionException;
use RuntimeException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\EARQ\EARQEventoPreservacaoLoggerInterface;
use SuppCore\AdministrativoBackend\Entity\Documento as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\RendererManager;
use SuppCore\AdministrativoBackend\Repository\DocumentoRepository as Repository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeVinculacaoDocumentoRepository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use \Jurosh\PDFMerge\PDFMerger;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Throwable;
use Twig\Environment;

/**
 * Class DocumentoResource.
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
class DocumentoResource extends RestResource
{
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        private ComponenteDigitalResource $componenteDigitalResource,
        private AssinaturaResource $assinaturaResource,
        private TokenStorageInterface $tokenStorage,
        private VinculacaoDocumentoResource $vinculacaoDocumentoResource,
        private RendererManager $rendererManager,
        private EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger,
        private TransactionManager $transactionManager,
        private ModalidadeVinculacaoDocumentoRepository $modalidadeVinculacaoDocumentoRepository,
        private ParameterBagInterface $parameterBag,
        private TarefaResource $tarefaResource,
        private MailerInterface $mailer,
        private UsuarioRepository $usuarioRepository,
        private SetorRepository $setorRepository,
        private AclProviderInterface $aclProvider,
        private Environment $twig,
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(Documento::class);
    }

    /**
     * Generic method to delete specified entity from database.
     *
     * @throws NotFoundHttpException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     * @throws ORMException
     * @throws Exception
     */
    public function deleteAssinatura(int $id, string $transactionId): EntityInterface
    {
        /** @var Entity $entity */
        $entity = $this->getEntity($id);

        if (!$this->tokenStorage->getToken() || !$this->tokenStorage->getToken()->getUser()) {
            throw new RuntimeException('Não há usuário logado!');
        }

        if (null === $entity) {
            throw new RuntimeException('Não foi possível localizar o documento!');
        }

        $usuario = $this->tokenStorage->getToken()->getUser();

        $ok = false;

        // excluir assinaturas dos anexos, quando for documento principal
        if (0 === $entity->getVinculacaoDocumentoPrincipal()->count()) {
            $componenteDigitais = $this
                ->componenteDigitalResource
                ->getRepository()
                ->findVinculadosByDocumento($entity);
        } else {
            $componenteDigitais = $entity->getComponentesDigitais();
        }

        foreach ($componenteDigitais as $componenteDigitalEntity) {
            foreach ($componenteDigitalEntity->getAssinaturas() as $assinatura) {
                /* @noinspection PhpPossiblePolymorphicInvocationInspection */
                if ($assinatura->getCriadoPor()
                    && $assinatura->getCriadoPor()->getId() === $usuario->getId()) {
                    $this->assinaturaResource->delete($assinatura->getId(), $transactionId);
                    $ok = true;
                }
            }
        }

        if (!$ok) {
            throw new RuntimeException('Usuário não possui assinaturas no documento!');
        }

        return $entity;
    }

    /**
     * @param int           $id
     * @param Entity|null   $documento
     * @param string        $transactionId
     * @param Processo|null $reprocessarProcesso
     *
     * @return EntityInterface
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function clonar(
        int $id,
        ?Entity $documento,
        string $transactionId,
        ?Processo $reprocessarProcesso = null,
        bool $getVinc = true,
    ): EntityInterface {
        /** @var Entity $documentoClonado */
        $documentoClonado = $this->getRepository()->find($id);

        $this->transactionManager->addContext(
            new Context('clonarDocumento', true),
            $transactionId
        );

        if (!$documento) {
            // copia o documento
            $dto = new Documento();
            $dto->setTipoDocumento($documentoClonado->getTipoDocumento());

            $documento = $this->create($dto, $transactionId);
        }

        /** @var \SuppCore\AdministrativoBackend\Entity\ComponenteDigital $componenteDigitalClonado */
        foreach ($documentoClonado->getComponentesDigitais() as $componenteDigitalClonado) {
            $componenteDigitalDTO = new ComponenteDigital();
            $componenteDigitalDTO->setDocumento($documento);
            $componenteDigitalDTO->setFileName($componenteDigitalClonado->getFileName());
            $componenteDigitalDTO->setHash($componenteDigitalClonado->getHash());
            $componenteDigitalDTO->setTamanho($componenteDigitalClonado->getTamanho());
            $componenteDigitalDTO->setMimetype($componenteDigitalClonado->getMimetype());
            $componenteDigitalDTO->setExtensao($componenteDigitalClonado->getExtensao());
            $componenteDigitalDTO->setEditavel($componenteDigitalClonado->getEditavel());

            if ($componenteDigitalClonado->getConteudo()) {
                $componenteDigitalDTO->setConteudo($componenteDigitalClonado->getConteudo());
                $componenteDigitalDTO->setTamanho(mb_strlen($componenteDigitalClonado->getConteudo()));
                $componenteDigitalDTO->setHash(hash('SHA256', $componenteDigitalClonado->getConteudo()));
            }

            if ($reprocessarProcesso && $componenteDigitalClonado->getEditavel()) {
                $conteudoOriginal = $this->componenteDigitalResource->download(
                    $componenteDigitalClonado->getId(),
                    $transactionId
                )->getConteudo();
                $componenteDigitalDTO->setProcessoOrigem($reprocessarProcesso);
                $conteudoReprocessado = $this->rendererManager->renderModelo(
                    $componenteDigitalDTO,
                    $transactionId,
                    [],
                    $conteudoOriginal
                );

                $componenteDigitalDTO->setConteudo($conteudoReprocessado);
                $componenteDigitalDTO->setTamanho(mb_strlen($conteudoReprocessado));
                $componenteDigitalDTO->setHash(hash('SHA256', $conteudoReprocessado));
            }

            $componenteDigital = $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);

            $documento->addComponenteDigital($componenteDigital);

            if (!$reprocessarProcesso) {
                try {
                    $this->transactionManager->addContext(
                        new Context('clonarAssinatura', true),
                        $transactionId
                    );
                    /** @var Assinatura $assinaturaClonada */
                    foreach ($componenteDigitalClonado->getAssinaturas() as $assinaturaClonada) {
                        // Não clonar quando tiver origem de dados (veio por integração)
                        if ($assinaturaClonada->getAssinatura() && !$assinaturaClonada->getOrigemDados()) {
                            $assinaturaDTO = new Assinatura();
                            $assinaturaDTO->setAlgoritmoHash($assinaturaClonada->getAlgoritmoHash());
                            $assinaturaDTO->setAssinatura($assinaturaClonada->getAssinatura());
                            $assinaturaDTO->setCadeiaCertificadoPEM($assinaturaClonada->getCadeiaCertificadoPEM());
                            $assinaturaDTO->setCadeiaCertificadoPkiPath($assinaturaClonada->getCadeiaCertificadoPkiPath());
                            $assinaturaDTO->setDataHoraAssinatura($assinaturaClonada->getDataHoraAssinatura());
                            $assinaturaDTO->setComponenteDigital($componenteDigital);
                            $assinaturaDTO->setCriadoPor($assinaturaClonada->getCriadoPor());
                            $assinaturaDTO->setPadrao($assinaturaClonada->getPadrao());
                            $this->assinaturaResource->create($assinaturaDTO, $transactionId);
                        }
                    }
                    $this->transactionManager->removeContext(
                        'clonarAssinatura',
                        $transactionId
                    );
                } catch(Exception $e) { }
            }

            $this->eventoPreservacaoLogger->logEPRES7Replicacao($componenteDigitalClonado, $componenteDigitalDTO);
        }

        if ($getVinc) {
            /** @var \SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento $vinculacaoDocumentoClonada */
            foreach ($documentoClonado->getVinculacoesDocumentos() as $vinculacaoDocumentoClonada) {
                // copia o documento remessa
                $documentoDTO = new Documento();
                $documentoDTO->setTipoDocumento(
                    $vinculacaoDocumentoClonada->getDocumentoVinculado()->getTipoDocumento()
                );

                $documentoVinculado = $this->create($documentoDTO, $transactionId);

                /** @var \SuppCore\AdministrativoBackend\Entity\ComponenteDigital $componenteDigitalClonado */
                foreach ($vinculacaoDocumentoClonada->getDocumentoVinculado()->
                getComponentesDigitais() as $componenteDigitalClonado) {
                    $componenteDigitalDTO = new ComponenteDigital();
                    $componenteDigitalDTO->setDocumento($documentoVinculado);
                    $componenteDigitalDTO->setFileName($componenteDigitalClonado->getFileName());
                    $componenteDigitalDTO->setHash($componenteDigitalClonado->getHash());
                    $componenteDigitalDTO->setTamanho($componenteDigitalClonado->getTamanho());
                    $componenteDigitalDTO->setMimetype($componenteDigitalClonado->getMimetype());
                    $componenteDigitalDTO->setExtensao($componenteDigitalClonado->getExtensao());
                    $componenteDigitalDTO->setModelo($componenteDigitalClonado->getModelo());
                    $componenteDigitalDTO->setEditavel($componenteDigitalClonado->getEditavel());

                    if ($reprocessarProcesso && $componenteDigitalClonado->getEditavel()) {
                        $conteudoOriginal = $this->componenteDigitalResource->download(
                            $componenteDigitalClonado->getId(),
                            $transactionId
                        )->getConteudo();
                        $componenteDigitalDTO->setProcessoOrigem($reprocessarProcesso);
                        $conteudoReprocessado = $this->rendererManager->renderModelo(
                            $componenteDigitalDTO,
                            $transactionId,
                            [],
                            $conteudoOriginal
                        );

                        $componenteDigitalDTO->setConteudo($conteudoReprocessado);
                        $componenteDigitalDTO->setTamanho(mb_strlen($conteudoReprocessado));
                        $componenteDigitalDTO->setHash(hash('SHA256', $conteudoReprocessado));
                    }

                    $componenteDigital = $this->componenteDigitalResource->create(
                        $componenteDigitalDTO,
                        $transactionId
                    );

                    if (!$reprocessarProcesso) {
                        $this->transactionManager->addContext(
                            new Context('clonarAssinatura', true),
                            $transactionId
                        );
                        /** @var Assinatura $assinaturaClonada */
                        foreach ($componenteDigitalClonado->getAssinaturas() as $assinaturaClonada) {
                            // Não clonar quando tiver origem de dados (veio por integração)
                            if ($assinaturaClonada->getAssinatura() && !$assinaturaClonada->getOrigemDados()) {
                                $assinaturaDTO = new Assinatura();
                                $assinaturaDTO->setAlgoritmoHash($assinaturaClonada->getAlgoritmoHash());
                                $assinaturaDTO->setAssinatura($assinaturaClonada->getAssinatura());
                                $assinaturaDTO->setCadeiaCertificadoPEM($assinaturaClonada->getCadeiaCertificadoPEM());
                                $assinaturaDTO->setCadeiaCertificadoPkiPath($assinaturaClonada->getCadeiaCertificadoPkiPath());
                                $assinaturaDTO->setDataHoraAssinatura($assinaturaClonada->getDataHoraAssinatura());
                                $assinaturaDTO->setComponenteDigital($componenteDigital);
                                $assinaturaDTO->setCriadoPor($assinaturaClonada->getCriadoPor());
                                $assinaturaDTO->setPadrao($assinaturaClonada->getPadrao());
                                $this->assinaturaResource->create($assinaturaDTO, $transactionId);
                            }
                        }
                        $this->transactionManager->removeContext(
                            'clonarAssinatura',
                            $transactionId
                        );
                    }

                    $this->eventoPreservacaoLogger->logEPRES7Replicacao($componenteDigitalClonado, $componenteDigitalDTO);
                }

                $vinculacaoDocumentoDTO = new VinculacaoDocumento();
                $vinculacaoDocumentoDTO->setDocumento($documento);
                $vinculacaoDocumentoDTO->setDocumentoVinculado($documentoVinculado);
                $vinculacaoDocumentoDTO->setModalidadeVinculacaoDocumento(
                    $vinculacaoDocumentoClonada->getModalidadeVinculacaoDocumento()
                );

                $vinculacaoDocumento = $this->vinculacaoDocumentoResource->create($vinculacaoDocumentoDTO, $transactionId);

                $documento->addVinculacaoDocumento($vinculacaoDocumento);
            }
        }

        return $documento;
    }

    /**
     * @param int       $id
     * @param string    $transactionId
     * @param bool|null $skipValidation
     *
     * @return EntityInterface
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws Exception
     */
    public function convertToPDF(
        int $id,
        string $transactionId,
        ?bool $skipValidation = null,
    ): EntityInterface {
        $skipValidation ??= false;

        $entity = $this->findOne($id);

        // Validate current entity
        $dto = $this->getDtoForEntity($entity->getId(), $this->getDtoClass(), null, $entity);
        $this->validateDto($dto, $skipValidation);

        // Before callback method call
        $this->beforeConvertToPDF($dto, $entity, $transactionId);

        // Convert and calls update lyfecicle from ComponenteDigital resource
        foreach ($entity->getComponentesDigitais() as $componenteDigitalEntity) {
            /** @var ComponenteDigital $componenteDigitalDto */
            $componenteDigitalDto = $this->componenteDigitalResource->getDtoForEntity(
                $componenteDigitalEntity->getId(),
                $this->componenteDigitalResource->getDtoClass(),
                null,
                $componenteDigitalEntity
            );

            $this->componenteDigitalResource->convert($componenteDigitalDto, $componenteDigitalEntity);
            $this->componenteDigitalResource->update(
                $componenteDigitalEntity->getId(),
                $componenteDigitalDto,
                $transactionId
            );
        }

        // After callback method call
        $this->afterConvertToPDF($dto, $entity, $transactionId);

        return $entity;
    }

    public function beforeConvertToPDF(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeConvertToPDF');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeConvertToPDF');
    }

    public function afterConvertToPDF(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterConvertToPDF');
    }

    /**
     * Converte a minuta de origem em anexo da minuta destino.
     *
     * @param int    $documentoOrigemId
     * @param int    $documentoDestinoId
     * @param string $transactionId
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function converteMinutaEmAnexo(
        int $documentoOrigemId,
        int $documentoDestinoId,
        string $transactionId,
    ): void {
        /** @var Entity $entityDocumentoOrigem */
        $entityDocumentoOrigem = $this->findOne($documentoOrigemId);

        /** @var Documento $dtoDocumentoOrigem */
        $dtoDocumentoOrigem = $this->getDtoForEntity(
            $entityDocumentoOrigem->getId(),
            $this->getDtoClass(),
            null,
            $entityDocumentoOrigem
        );

        // Removendo referência de tarefa de origem da minuta a ser convertida
        $dtoDocumentoOrigem->setTarefaOrigem(null);

        // Before callback method call
        $this->beforeConverteMinutaEmAnexo($dtoDocumentoOrigem, $entityDocumentoOrigem, $transactionId);

        $this->update($documentoOrigemId, $dtoDocumentoOrigem, $transactionId);

        /** @var Entity $entityDocumentoDestino */
        $entityDocumentoDestino = $this->findOne($documentoDestinoId);

        /** @var Documento $dtoDocumentoDestino */
        $dtoDocumentoDestino = $this->getDtoForEntity(
            $entityDocumentoDestino->getId(),
            $this->getDtoClass(),
            null,
            $entityDocumentoDestino
        );

        $this->afterConverteMinutaEmAnexo(
            $dtoDocumentoOrigem,
            $entityDocumentoOrigem,
            $dtoDocumentoDestino,
            $entityDocumentoDestino,
            $transactionId
        );
    }

    /**
     * @param RestDtoInterface $dto
     * @param EntityInterface  $entity
     * @param string           $transactionId
     *
     * @return void
     */
    public function beforeConverteMinutaEmAnexo(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeConverteMinutaEmAnexo');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeConverteMinutaEmAnexo');
    }

    /**
     * @param RestDtoInterface $dtoDocumentoOrigem
     * @param EntityInterface  $entityDocumentoOrigem
     * @param RestDtoInterface $dtoDocumentoDestino
     * @param EntityInterface  $entityDocumentoDestino
     * @param string           $transactionId
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function afterConverteMinutaEmAnexo(
        RestDtoInterface $dtoDocumentoOrigem,
        EntityInterface $entityDocumentoOrigem,
        RestDtoInterface $dtoDocumentoDestino,
        EntityInterface $entityDocumentoDestino,
        string $transactionId,
    ): void {
        $vinculacaoDocumentoDTO = (new VinculacaoDocumento())
            ->setDocumento($entityDocumentoDestino)
            ->setDocumentoVinculado($entityDocumentoOrigem)
            ->setModalidadeVinculacaoDocumento(
                $this->modalidadeVinculacaoDocumentoRepository->findOneBy(
                    ['valor' => $this->parameterBag->get(
                        'constantes.entidades.modalidade_vinculacao_documento.const_1'
                    )]
                )
            );

        $dtoDocumentoDestino->addVinculacaoDocumento($vinculacaoDocumentoDTO);

        $this->vinculacaoDocumentoResource->create($vinculacaoDocumentoDTO, $transactionId);

        $this->triggersManager->proccess(
            $dtoDocumentoOrigem,
            $entityDocumentoOrigem,
            $transactionId,
            'afterConverteMinutaEmAnexo'
        );
    }

    /**
     * Converte a minuta de origem em anexo da minuta destino.
     *
     * @param int    $id
     * @param int    $tarefaId
     * @param string $transactionId
     *
     * @return EntityInterface
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function converteAnexoEmMinuta(
        int $id,
        int $tarefaId,
        string $transactionId,
    ): EntityInterface {
        /** @var Entity $entityDocumento */
        $entityDocumento = $this->findOne($id);

        /** @var Documento $dtoDocumento */
        $dtoDocumento = $this->getDtoForEntity(
            $entityDocumento->getId(),
            $this->getDtoClass(),
            null,
            $entityDocumento
        );

        $this->transactionManager->addContext(new Context('converteAnexoEmMinuta', $id), $transactionId);

        // Before callback method call
        $this->beforeConverteAnexoEmMinuta($dtoDocumento, $entityDocumento, $transactionId);

        // Vinculando minuta com tarefa de origem
        $tarefaOrigem = $this->tarefaResource->findOne($tarefaId);
        $dtoDocumento->setTarefaOrigem($tarefaOrigem);

        $entityDocumento = $this->update($id, $dtoDocumento, $transactionId);

        $this->afterConverteAnexoEmMinuta($dtoDocumento, $entityDocumento, $transactionId);

        return $entityDocumento;
    }

    public function downloadAllPdf(int $id, string $transactionId) {
        try {
            $documentoEntity = $this->getRepository()->find($id);
    
            $arquivosCriados = [];
            $tempPath = sys_get_temp_dir().'/download_minuta_anexos_'.rand(1, 999999);
            $filenameOutput = 'minuta_anexos.pdf';
            mkdir($tempPath, 0777, true);

            $arquivoParaDownload = $this->componenteDigitalResource->download($documentoEntity->getId(), $transactionId);
            if($arquivoParaDownload->getExtensao() === 'pdf' || $arquivoParaDownload->getExtensao() === 'p7s') {
                $pathWithFilename = $tempPath.'/'.$arquivoParaDownload->getFileName();

                $tmpFile = fopen($pathWithFilename, 'w');
                fwrite($tmpFile, $arquivoParaDownload->getConteudo());
                fclose($tmpFile);
                $arquivosCriados[] = $pathWithFilename;
            } else {
                $arquivoParaDownload->setMimetype('application/pdf');
                $arquivoParaDownload->setExtensao('pdf');
                $arquivoParaDownload->setFileName(
                    str_replace(
                        '.html',
                        '.pdf',
                        str_replace('.HTML', '.pdf', $arquivoParaDownload->getFileName())
                    )
                );
                $pathWithFilename = $tempPath.'/'.$arquivoParaDownload->getFileName();

                $tmpFile = fopen($pathWithFilename, 'w');
                fwrite($tmpFile, $arquivoParaDownload->getConteudo());
                fclose($tmpFile);
                $arquivosCriados[] = $pathWithFilename;
            }

            if ($documentoEntity->getVinculacoesDocumentos()->count() > 0) {
                foreach ($documentoEntity->getVinculacoesDocumentos() as $anexos) {
                    /** @var VinculacaoDocumento $anexos */
                    foreach ($anexos->getDocumentoVinculado()->getComponentesDigitais() as $componenteDigital) {
                        $arquivoParaDownload = $this->componenteDigitalResource->downloadVinculado($componenteDigital->getId(), $transactionId);

                        if($arquivoParaDownload->getExtensao() === 'pdf' || $arquivoParaDownload->getExtensao() === 'p7s') {
                            $pathWithFilename = $tempPath.'/'.$arquivoParaDownload->getFileName();

                            $tmpFile = fopen($pathWithFilename, 'w');
                            fwrite($tmpFile, $arquivoParaDownload->getConteudo());
                            fclose($tmpFile);
                            $arquivosCriados[] = $pathWithFilename;
                        } else {
                            $arquivoParaDownload->setMimetype('application/pdf');
                            $arquivoParaDownload->setExtensao('pdf');
                            $arquivoParaDownload->setFileName(
                                str_replace(
                                    '.html',
                                    '.pdf',
                                    str_replace('.HTML', '.pdf', $arquivoParaDownload->getFileName())
                                )
                            );
                            $pathWithFilename = $tempPath.'/'.$arquivoParaDownload->getFileName();

                            $tmpFile = fopen($pathWithFilename, 'w');
                            fwrite($tmpFile, $arquivoParaDownload->getConteudo());
                            fclose($tmpFile);
                            $arquivosCriados[] = $pathWithFilename;
                        }
                    }
                }
            }
            
            $pdf = new PDFMerger();

            if(count($arquivosCriados) == 0) {
                throw new BadRequestException('Não há arquivos para gerar o PDF!');
            }

            foreach($arquivosCriados as $arquivo) {
                $pdf->addPDF($arquivo, 'all');
            }

            $pdf->merge('file', $tempPath.'/'. $filenameOutput);

            return $this->componenteDigitalResource->downloadMinutaAnexos($tempPath, $filenameOutput);
        } catch(Exception $e) { 
            throw new BadRequestException($e->getMessage());
        }
    }

    /**
     * @param RestDtoInterface $dto
     * @param EntityInterface  $entity
     * @param string           $transactionId
     *
     * @return void
     */
    public function beforeConverteAnexoEmMinuta(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeConverteAnexoEmMinuta');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeConverteAnexoEmMinuta');
    }

    /**
     * @param RestDtoInterface $dto
     * @param EntityInterface  $entity
     * @param string           $transactionId
     *
     * @return void
     */
    public function afterConverteAnexoEmMinuta(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterConverteAnexoEmMinuta');
    }


    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Throwable
     */
    public function sendEmailMethod(Request $request, int $id, string $transactionId): EntityInterface
    {
        //fetch $entity
        $entity = $this->getEntity($id);

        $restDto = null;

        $this->beforeSendEmail($id, $restDto, $entity, $transactionId);

        $remetente = $this->tokenStorage->getToken()->getUser();
        $destinatarios = [];

        $acl = $this->aclProvider->findAcl(ObjectIdentity::fromDomainObject($entity));
        $aclId = $request->get('visibilidade')['id'];
        if($acl) {
            $ace = (function() use ($acl, $aclId) {
                foreach($acl->getObjectAces() as $ace) {
                    if($ace->getId() === $aclId) {
                        return $ace;
                    }
                }
                return null;
            })();

            if(!$ace) {
                throw new BadRequestException('Não foi possível preocessar a sua requisição.');
            }

            if ($ace->getSecurityIdentity() instanceof UserSecurityIdentity) {
                $usuario = $this->usuarioRepository->findOneBy(
                    [
                        'username' => $ace->getSecurityIdentity()->getUsername(),
                    ]
                );
                if($usuario && $usuario->getEmail()) {
                    $destinatarios[] = $usuario->getEmail();
                }
            } else {
                $roles = explode('_', (string) $ace->getSecurityIdentity()->getRole());
                /** @var Setor $setor */
                $setor = $this->setorRepository->find((int) $roles[2]);
                if($setor && $setor->getEmail()) {
                    $destinatarios[] = $setor->getEmail();
                }
            }

            if(empty($destinatarios)) {
                throw new BadRequestException('Não foi possível recuperar os destinatários da sua requisição.');
            }
        }

        $this->enviarEmail($entity, $remetente, $destinatarios, $request->get('justificativa'));

        $this->afterSendEmail($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeSendEmail(
        int &$id,
        ?RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeSendEmail');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeSendEmail');
    }

    public function afterSendEmail(
        int &$id,
        ?RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'afterSendEmail');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterSendEmail');
    }

    /**
     * @param EntityInterface|Entity $documento
     * @param Usuario|Entity $remetente
     * @param array $destinatarios
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Throwable
     */
    private function enviarEmail(EntityInterface $documento, Usuario $remetente, array $destinatarios, string $justificativa): bool
    {
        ;
        $message = (new Email())
            ->subject('Solicitação de acesso ao documento pelo '.$this->parameterBag
                ->get('supp_core.administrativo_backend.nome_sistema'))
            ->from($this->parameterBag->get('supp_core.administrativo_backend.email_suporte'))
            ->replyTo($remetente->getEmail())
            ->to(...$destinatarios)
            ->html(
                $this->twig->render(
                    $this->parameterBag->get('supp_core.administrativo_backend.template_envio_solicitacao_acesso_documento_email'),
                    [
                        'usuario' => $remetente->getNome(),
                        'justificativa' => $justificativa,
                        'sistema' => $this->parameterBag->get('supp_core.administrativo_backend.nome_sistema'),
                        'ambiente' => $this->parameterBag->get(
                            'supp_core.administrativo_backend.kernel_environment_mapping'
                        )[$this->parameterBag->get('kernel.environment')],
                        'documentoId' => $documento->getId(),
                        'juntadaId' => $documento?->getJuntadaAtual()->getId(),
                        'sequencial' => $documento->getJuntadaAtual()->getNumeracaoSequencial(),
                        'NUP' => $documento?->getJuntadaAtual()->getVolume()->getProcesso()->getNUPFormatado()
                    ]
                ),
            );

        try {
            $success = true;
            $tempPath = sys_get_temp_dir().'/mail_'.rand(1, 999999);
            mkdir($tempPath);
            $this->mailer->send($message);
        } catch (Throwable $e) {
            $success = false;
        } finally {
            // Ao excluir a pasta temp na mesma instrução o arquivo não é anexado
            // criar um command para exclusão dos arquivos enviados
            /* foreach ($arquivosCriados as $arquivoCriado) {
                unlink($arquivoCriado);
            }
            if (is_dir($tempPath)) {
                rmdir($tempPath);
            } */
            if (isset($e)) {
                throw $e;
            }
        }

        return $success;
    }
}
