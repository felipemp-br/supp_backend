<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Resource/ComponenteDigitalResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use Knp\Snappy\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Html as ReaderHtml;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use Psr\Log\LoggerInterface;
use ReflectionException;
use RuntimeException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Crypto\CryptoManager;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\EARQ\EARQEventoPreservacaoLoggerInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Enums\AssinaturaPadrao;
use SuppCore\AdministrativoBackend\Filesystem\FilesystemManager;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository as Repository;
use SuppCore\AdministrativoBackend\Repository\DadosFormularioRepository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Utils\CompressServiceInterface;
use SuppCore\AdministrativoBackend\Utils\HTMLPurifier;
use SuppCore\AdministrativoBackend\Utils\X509Service;
use SuppCore\AdministrativoBackend\Utils\ZipStream;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Process\Process;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

use function count;
use function get_class;
use function ord;
use function strlen;

/**
 * Class ComponenteDigitalResource.
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
class ComponenteDigitalResource extends RestResource
{
    /**
     * ComponenteDigitalResource constructor.
     *
     * @param Repository                           $repository
     * @param ValidatorInterface                   $validator
     * @param ParameterBagInterface                $parameterBag
     * @param CompressServiceInterface             $compresser
     * @param Pdf                                  $pdfManager
     * @param Environment                          $twig
     * @param CryptoManager                        $cryptoManager
     * @param FilesystemManager                    $filesystemManager
     * @param ZipStream                            $zipStream
     * @param AuthorizationCheckerInterface        $authorizationChecker
     * @param EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger
     * @param HTMLPurifier                         $purifier
     */
    public function __construct(
        private readonly Repository $repository,
        private readonly ValidatorInterface $validator,
        private readonly ParameterBagInterface $parameterBag,
        private readonly CompressServiceInterface $compresser,
        private readonly Pdf $pdfManager,
        private readonly Environment $twig,
        private readonly CryptoManager $cryptoManager,
        private readonly FilesystemManager $filesystemManager,
        private readonly ZipStream $zipStream,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly EARQEventoPreservacaoLoggerInterface $eventoPreservacaoLogger,
        private HTMLPurifier $purifier,
        #[AutowireIterator('supp_backend.chancela')]
        protected iterable $chancelaComponenteDigitalInterfaces,
        private readonly X509Service $x509Service,
        private readonly LoggerInterface $logger,
        private readonly DadosFormularioRepository $dadosFormularioRepository
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(ComponenteDigitalDTO::class);
    }

    /**
     * @param int         $id
     * @param string      $transactionId
     * @param bool|null   $chancelaAssinatura
     * @param bool|null   $asPdf
     * @param string|null $versao
     * @param bool|null   $throwExceptionIfNotFound
     * @param bool|null   $asXls
     *
     * @return Entity|null
     *
     * @throws AnnotationException
     * @throws Exception
     * @throws LoaderError
     * @throws ReflectionException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function download(
        int $id,
        string $transactionId,
        ?bool $chancelaAssinatura = null,
        ?bool $asPdf = null,
        ?string $versao = null,
        ?bool $throwExceptionIfNotFound = null,
        ?bool $asXls = null,
    ): ?Entity {
        $throwExceptionIfNotFound ??= false;

        /** @var ComponenteDigital $entity */
        // Fetch entity
        $entity = $this->getEntity($id);
        $restDto = $this->getDtoForEntity($id, ComponenteDigitalDTO::class);

        // Before callback method call
        $this->beforeDownload($id, $restDto, $entity, $transactionId);

        // Entity not found
        if ($throwExceptionIfNotFound && null === $entity) {
            throw new NotFoundHttpException('Not found');
        }

        // After callback method call
        $this->afterDownload($id, $restDto, $entity, $transactionId, $chancelaAssinatura, $asPdf, $versao, $asXls);

        return $entity;
    }

    /**
     * @param int                   $id
     * @param RestDtoInterface|null $dto
     * @param EntityInterface       $entity
     * @param string                $transactionId
     *
     * @return void
     */
    public function beforeDownload(
        int &$id,
        ?RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertDownload');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeDownload');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeDownload');
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function afterDownload(
        int &$id,
        ?RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
        ?bool $chancelaAssinatura = null,
        ?bool $asPdf = null,
        ?string $versao = null,
        ?bool $asXls = null,
    ): void {
        /** @var ComponenteDigital $entity */
        $filesystem = $this->filesystemManager
            ->getFilesystemService($entity)
            ->get();
        if ($filesystem->has($entity->getHash())) {
            $entity->setConteudo(
                $this->compresser->uncompress(
                    $this->cryptoManager->getCryptoService(
                        $entity,
                        $dto
                    )->decrypt(
                        $filesystem->read($versao ?: $entity->getHash())
                    )
                )
            );

            $this->eventoPreservacaoLogger
                ->logEPRES1Descompressao($entity)
                ->logEPRES2Decifracao($entity);
        } elseif ('prod' !== $this->parameterBag->get('kernel.environment')) {
            $html =
                <<<'HTML'
                        <!DOCTYPE html>
                        <html lang="pt">
                            <head>
                                <meta charset="utf-8">
                                <title>SUPP</title>
                            </head>
                            <body>
                                <p>Documento gerado para desenvolvimento</p>
                            </body>
                        </html>
HTML;
            if ('text/html' === $entity->getMimetype()) {
                $entity->setConteudo($html);
            } else {
                if (!mb_detect_encoding($html, 'UTF-8', true)) {
                    $html = mb_convert_encoding($html, 'UTF-8', 'ISO-8859-1');
                }
                $entity->setConteudo(
                    $this->pdfManager->getOutputFromHtml($html)
                );
            }
        } else {
            throw new RuntimeException('Houve erro na recuperação do arquivo no filesystem!');
        }

        // validação storage filesystem
        $hash = hash($this->parameterBag->get('algoritmo_hash_componente_digital'), $entity->getConteudo());
        if (!$versao && $entity->getHash() !== $hash) {
            $this->eventoPreservacaoLogger->logEPRES5FixidadeInvalida($dto, 'checksum');
            throw new RuntimeException('O conteúdo do Componente Digital não bate com o hash!');
        }
        $this->eventoPreservacaoLogger->logEPRES5FixidadeValida($entity, 'checksum');

        // append das chancelas de assinaturas
        if ($chancelaAssinatura && 'text/html' === $entity->getMimetype()) {
            // Adiciona o QRCode de avaliação logo após o conteúdo do documento
            $entity->setConteudo($entity->getConteudo() . $this->getQRCodeAvaliacao($entity));

            $linkSistema = $this->parameterBag->get('supp_core.administrativo_backend.url_sistema_frontend');
            if ($entity->getDocumento()->getJuntadaAtual()) {
                $chaveAcesso = $entity->getDocumento()->getJuntadaAtual()->getVolume()->getProcesso()->getChaveAcesso();
            } else {
                $chaveAcesso = $entity->getDocumento()->getProcessoOrigem()?->getChaveAcesso();
            }

            foreach ($entity->getAssinaturas() as $assinatura) {
                try {
                    // Recupera a cadeia de certificados PEM. Pode conter um ou mais
                    $sCertChain = $assinatura->getCadeiaCertificadoPEM();

                    // Assinatura em modo teste tem cadeia_teste. Coloca a chancela teste, continue
                    if ('cadeia_teste' === $sCertChain) {
                        $assinatura = "<br><hr><div style='text-align: justify;'>Documento assinado eletronicamente por TESTE, de acordo com os normativos legais aplicáveis.
                            A conferência da autenticidade do documento está disponível com o código $id e chave de acesso $chaveAcesso no endereço eletrônico $linkSistema.
                            Informações adicionais:
                            Signatário (a): TESTE.
                            Data e Hora: TESTE.
                            Número de Série: TESTE.
                            Emissor: TESTE.</div><hr>";
                        $conteudo = str_replace('</body>', '', $entity->getConteudo());
                        $conteudo = str_replace('</html>', '', $conteudo);

                        $entity->setConteudo($conteudo . $assinatura . '</body></html>');
                        continue;
                    }

                    // PEM é representado por: -----BEGIN CERTIFICATE-----<certificado Base64>-----END CERTIFICATE-----
                    // Se não tiver no formato válido, não coloca a chancela, continue
                    if (empty($sCertChain)
                        || !str_contains($sCertChain, '-----BEGIN CERTIFICATE-----')
                        || !str_contains($sCertChain, '-----END CERTIFICATE-----')) {
                        continue;
                    }

                    $aCertChain = explode('-----END CERTIFICATE-----', $sCertChain);

                    // O primeiro certificado da cadeia é o que realizou a assinatura
                    $firstCert = $aCertChain[0] . '-----END CERTIFICATE-----';


                    $parsed = $this->x509Service->getCredentials($firstCert);

                    if (!$parsed) {
                        continue;
                    }

                    $serialNumber = $parsed['serialNumber'];
                    $emissor = $parsed['emissor'];

                    if ($parsed['institucional']) {
                        $nome = $assinatura->getCriadoPor()->getNome() .
                            ', com certificado A1 institucional (' . $parsed['cn'] . ')';
                    } else {
                        $nome = $parsed['nome']
                            . ', com certificado '
                            . $parsed['tipo']
                            . (match ($parsed['naturezaJuridica']) {
                                'PF' => ' de Pessoa Física',
                                'PJ' => ' de Pessoa Jurídica',
                                default => ''
                            });
                    }

                    $dataHora = $assinatura->getDataHoraAssinatura()->format('d-m-Y H:i');
                    $id = $entity->getId();
                    $assinatura = $this->twig->render(
                            $this->parameterBag->get('supp_core.administrativo_backend.template_chancela_assinatura'),
                            [
                                'nome' => $nome,
                                'id' => $id,
                                'chaveAcesso' => $chaveAcesso,
                                'linkSistema' => $linkSistema,
                                'dataHora' => $dataHora,
                                'serialNumber' => $serialNumber,
                                'emissor' => $emissor,
                            ]
                        );
                    $conteudo = str_replace('</body>', '', $entity->getConteudo());
                    $conteudo = str_replace('</html>', '', $conteudo);

                    $entity->setConteudo($conteudo . $assinatura . '</body></html>');

                } catch (Throwable $throwable ) {
                    $this->logger->critical($throwable->getMessage());
                }
            }
        }

        // transforma em PDF
        if ($asPdf && 'text/html' === $entity->getMimetype()) {
            $html = $entity->getConteudo();
            $entity->setConteudo(
                $this->pdfManager->getOutputFromHtml($html)
            );
            /*
            $entity->setMimetype('application/pdf');
            $entity->setExtensao('pdf');
            $entity->setFileName(str_replace('.html', '.pdf', str_replace('.HTML', '.pdf', $entity->getFileName())));
            */
        }

        // transforma em XLSX
        if ($asXls && 'text/html' === $entity->getMimetype()) {
            $entity->setConteudo(
                $this->htmlToXls($entity->getConteudo())
            );
            /*
            $entity->setMimetype('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $entity->setExtensao('xlsx');
            $entity->setFileName(
                str_replace(
                    '.html',
                    '.xlsx',
                    str_replace('.HTML', '.xlsx', $entity->getFileName())
                )
            );*/
        }
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'afterDownload');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterDownload');
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws \Exception
     */
    public function reverter(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null,
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeReverter($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterReverter($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    /**
     * @param int              $id
     * @param RestDtoInterface $dto
     * @param EntityInterface  $entity
     * @param string           $transactionId
     *
     * @return void
     */
    public function beforeReverter(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertReverter');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeReverter');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeReverter');
    }

    /**
     * @param int              $id
     * @param RestDtoInterface $dto
     * @param EntityInterface  $entity
     * @param string           $transactionId
     *
     * @return void
     */
    public function afterReverter(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterReverter');
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Exception
     */
    public function aprovar(
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null,
    ): EntityInterface {
        /*
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */

        // Validate DTO
        $this->validateDto($dto, $skipValidation);

        // Before callback method call

        // Create or update entity
        $entity = new Entity();

        $this->beforeAprovar($dto, $entity, $transactionId);

        // $skipValidation ??= false;

        $this->persistEntity($entity, $dto, $transactionId);

        // After callback method call
        $this->afterAprovar($dto, $entity, $transactionId);

        return $entity;
    }

    /**
     * @param RestDtoInterface $dto
     * @param EntityInterface  $entity
     * @param string           $transactionId
     *
     * @return void
     */
    public function beforeAprovar(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeAprovar');
    }

    /**
     * @param RestDtoInterface $dto
     * @param EntityInterface  $entity
     * @param string           $transactionId
     *
     * @return void
     */
    public function afterAprovar(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterAprovar');
    }

    /**
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws \Exception
     */
    public function convertPDF(
        int $documentoId,
        string $transactionId,
        ?bool $skipValidation = null,
    ): array {
        $componentesDigitais = $this->getRepository()->findBy(['documento' => $documentoId]);
        $return['entities'] = [];

        foreach ($componentesDigitais as $componenteDigital) {
            if (!$this->authorizationChecker->isGranted('VIEW', $componenteDigital->getDocumento())) {
                throw new AccessDeniedException('Acesso negado!');
            }

            $processo = $componenteDigital->getDocumento()->getJuntadaAtual()?->getVolume()->getProcesso();
            if ($componenteDigital->getDocumento()->getJuntadaAtual()
                && (
                    !$this->authorizationChecker->isGranted('VIEW', $processo)
                    || ($processo->getClassificacao()
                        && $processo->getClassificacao()->getId()
                        && !$this->authorizationChecker->isGranted('VIEW', $processo->getClassificacao()))
                )) {
                throw new AccessDeniedException('Acesso negado!');
            }

            /** @var ComponenteDigitalDTO $restDto */
            $restDto = $this->getDtoForEntity(
                $componenteDigital->getId(),
                ComponenteDigitalDTO::class,
                null,
                $componenteDigital
            );

            $this->validateDto($restDto, $skipValidation);
            $this->convert($restDto, $componenteDigital);
            $return['entities'][] = $this->update($componenteDigital->getId(), $restDto, $transactionId);
        }

        $return['total'] = count($componentesDigitais);

        return $return;
    }

    /**
     * Converte um componente digital HTML em PDF
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws \Exception
     */
    public function convertHtmlToPDF(
        ComponenteDigital $componenteDigital,
        string $transactionId,
        bool $skipValidation,
    ): ComponenteDigital {

        if (!$this->authorizationChecker->isGranted('VIEW', $componenteDigital->getDocumento())) {
            throw new AccessDeniedException('Acesso negado!');
        }

        $processo = $componenteDigital->getDocumento()->getJuntadaAtual()?->getVolume()->getProcesso();
        if ($componenteDigital->getDocumento()->getJuntadaAtual()
            && (
                !$this->authorizationChecker->isGranted('VIEW', $processo)
                || ($processo->getClassificacao()
                    && $processo->getClassificacao()->getId()
                    && !$this->authorizationChecker->isGranted('VIEW', $processo->getClassificacao()))
            )) {
            throw new AccessDeniedException('Acesso negado!');
        }

        // Se já é um PDF, não precisa transformar em PDF
        if (strcasecmp('application/pdf', $componenteDigital->getMimetype()) === 0) {
            return $componenteDigital;
        }

        /** @var ComponenteDigitalDTO $restDto */
        $restDto = $this->getDtoForEntity(
            $componenteDigital->getId(),
            ComponenteDigitalDTO::class,
            null,
            $componenteDigital
        );

        $this->validateDto($restDto, $skipValidation);
        $this->convert($restDto, $componenteDigital);
        return $this->update($componenteDigital->getId(), $restDto, $transactionId);
    }

    /**
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws \Exception
     */
    public function convertPdfInternoToHTML(
        int $documentoId,
        string $transactionId,
        ?bool $skipValidation = null,
    ): array {
        $componentesDigitais = $this->getRepository()->findBy(['documento' => $documentoId]);
        $return['entities'] = [];

        $logEntryRepository = $this->getRepository()
            ->getEntityManager()
            ->getRepository('Gedmo\Loggable\Entity\LogEntry');

        foreach ($componentesDigitais as $componenteDigital) {
            $restDto = $this->getDtoForEntity(
                $componenteDigital->getId(),
                ComponenteDigitalDTO::class,
                null,
                $componenteDigital
            );

            $this->validateDto($restDto, $skipValidation);

            if ('application/pdf' === $restDto->getMimetype() && $restDto->getConvertidoPdf()) {
                $filesystem = $this->filesystemManager
                    ->getFilesystemService($componenteDigital, $restDto)
                    ->get();
                $logs = $logEntryRepository->getLogEntries($componenteDigital);

                // $lastHtmlVersion = $logs[1]->getData()['hash'];
                $lastHtmlVersion = $this->getLastHashOfHtml($logs);

                if (empty($lastHtmlVersion)) {
                    throw new RuntimeException('Não encontrada a última versão HTML do '.$componenteDigital->getFileName());
                }

                $restDto
                    ->setHashAntigo($restDto->getHash())
                    ->setConteudo(
                        $this->compresser->uncompress(
                            $this->cryptoManager->getCryptoService($componenteDigital, $restDto)
                                ->decrypt($filesystem->read($lastHtmlVersion))
                        )
                    )
                    ->setHash(hash('SHA256', $restDto->getConteudo()))
                    ->setMimetype('text/html')
                    ->setExtensao('html')
                    ->setEditavel(true)
                    ->setConvertidoPdf(false)
                    ->setTamanho(strlen($restDto->getConteudo()))
                    ->setFileName(
                        str_replace(
                            '.pdf',
                            '.html',
                            str_replace('.PDF', '.html', $restDto->getFileName())
                        )
                    );

                $this->eventoPreservacaoLogger
                    ->logEPRES1Descompressao($restDto)
                    ->logEPRES2Decifracao($restDto);
            } else {
                throw new RuntimeException('Arquivo não pode ser convertido!');
            }

            $return['entities'][] = $this->update($restDto->getId(), $restDto, $transactionId, $skipValidation);
        }

        $return['total'] = count($componentesDigitais);

        return $return;
    }

    /**
     * Converte componente digital PDF em HTML<br/>
     * Obs: a conversão é o retorno ao último HTML cadastrado no LogEntry
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     * @throws \Exception
     */
    public function convertPdfInternoToHTML2(
        ComponenteDigital $componenteDigital,
        string $transactionId,
        bool $skipValidation,
    ): ComponenteDigital {

        $logEntryRepository = $this->getRepository()
            ->getEntityManager()
            ->getRepository('Gedmo\Loggable\Entity\LogEntry');

        /** @var ComponenteDigitalDTO $componenteDigitalDTO */
        $componenteDigitalDTO = $this->getDtoForEntity(
            $componenteDigital->getId(),
            ComponenteDigitalDTO::class,
            null,
            $componenteDigital
        );

        $this->validateDto($componenteDigitalDTO, $skipValidation);

        if ('application/pdf' === $componenteDigitalDTO->getMimetype() && $componenteDigitalDTO->getConvertidoPdf()) {
            $filesystem = $this->filesystemManager
                ->getFilesystemService($componenteDigital, $componenteDigitalDTO)
                ->get();
            $logs = $logEntryRepository->getLogEntries($componenteDigital);

            $lastHtmlVersion = $this->getLastHashOfHtml($logs);

            if (empty($lastHtmlVersion)) {
                throw new RuntimeException('Não encontrada a última versão HTML do '.$componenteDigital->getFileName());
            }

            $componenteDigitalDTO
                ->setHashAntigo($componenteDigitalDTO->getHash())
                ->setConteudo(
                    $this->compresser->uncompress(
                        $this->cryptoManager->getCryptoService($componenteDigital, $componenteDigitalDTO)
                            ->decrypt($filesystem->read($lastHtmlVersion))
                    )
                )
                ->setHash(hash('SHA256', $componenteDigitalDTO->getConteudo()))
                ->setMimetype('text/html')
                ->setExtensao('html')
                ->setEditavel(true)
                ->setConvertidoPdf(false)
                ->setTamanho(strlen($componenteDigitalDTO->getConteudo()))
                ->setFileName(
                    str_replace(
                        '.pdf',
                        '.html',
                        str_replace('.PDF', '.html', $componenteDigitalDTO->getFileName())
                    )
                );

            $this->eventoPreservacaoLogger
                ->logEPRES1Descompressao($componenteDigitalDTO)
                ->logEPRES2Decifracao($componenteDigitalDTO);
        } else {
            throw new RuntimeException('Arquivo não pode ser convertido em HTML!');
        }

        return $this->update($componenteDigitalDTO->getId(), $componenteDigitalDTO, $transactionId, $skipValidation);
    }

    /**
     * @param ComponenteDigitalDTO|null $restDto
     * @param Entity|null               $componenteDigital
     *
     * @return void
     */
    public function convert(
        ?ComponenteDigitalDTO $restDto = null,
        ?Entity $componenteDigital = null,
    ): void {
        // transforma em PDF
        if ('text/html' === $restDto->getMimetype()) {
            $filesystem = $this->filesystemManager
                ->getFilesystemService($componenteDigital, $restDto)
                ->get();

            $conteudoHTML = $this->compresser->uncompress(
                $this->cryptoManager->getCryptoService($componenteDigital, $restDto)
                    ->decrypt($filesystem->read($restDto->getHash()))
            );

            $conteudoHTMLFromTwig = $this->twig->render(
                $this->parameterBag->get('supp_core.administrativo_backend.template_ckeditor_administrativo_comum'),
                [
                    'conteudo' => $conteudoHTML
                ]
            );

            $restDto->setHashAntigo($restDto->getHash());
            $restDto->setConteudo(
                $this->pdfManager->getOutputFromHtml(
                    $conteudoHTMLFromTwig
                )
            );
            $restDto->setHash(hash('SHA256', $restDto->getConteudo()));
            $restDto->setMimetype('application/pdf');
            $restDto->setExtensao('pdf');
            $restDto->setConvertidoPdf(true);
            $restDto->setEditavel(false);
            $restDto->setTamanho(strlen($restDto->getConteudo()));
            $restDto->setFileName(str_replace('.html', '.pdf', str_replace('.HTML', '.pdf', $restDto->getFileName())));

            $this->eventoPreservacaoLogger
                ->logEPRES1Descompressao($restDto)
                ->logEPRES2Decifracao($restDto);
        } else {
            throw new RuntimeException('Arquivo não pode ser convertido!');
        }
    }

    /**
     * @return false|string
     *
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws Exception
     */
    private function htmlToXls(string $html): bool|string
    {
        $readerHtml = new ReaderHtml();
        // CRIA NOVO READER HTML, PARA REALIZAR A CONVERSÃO DO HTML PARA XLS
        $spreadsheet = $readerHtml->loadFromString($html);

        // REALIZA A CONVERSÃO
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        // VERIFICA O DIRETÓRIO TEMPORÁRIO
        $temp = rtrim(sys_get_temp_dir());

        // GERA UM NOME ÚNICO PARA O ARQUIVO
        $filename = $temp.DIRECTORY_SEPARATOR.uniqid('supp_excel', true).'.xlsx';

        // SALVA O ARQUIVO NO DIRETÓRIO TEMPORÁRIO
        $writer->save($filename);

        // LÊ O CONTEÚDO DO ARQUIVO RECÉM CRIADO E INSERE NO ATRIBUTO CONTEÚDO DO COMPONENTE DIGITAL
        $conteudo = file_get_contents($filename);

        // EXCLUÍ O ARQUIVO DO DIRETÓRIO TEMPORÁRIO
        if (file_exists($filename)) {
            unlink($filename);
        }

        return $conteudo;
    }

    /**
     * @param int       $id
     * @param string    $transactionId
     * @param bool|null $throwExceptionIfNotFound
     *
     * @return ComponenteDigitalDTO
     *
     * @throws AnnotationException
     * @throws Exception
     * @throws LoaderError
     * @throws ReflectionException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function downloadP7s(
        int $id,
        string $transactionId,
        ?bool $throwExceptionIfNotFound = null,
    ): ComponenteDigitalDTO {
        /** @var Entity $componenteDigitalEntity */
        $componenteDigitalEntity = $this->getEntity($id);

        if ($throwExceptionIfNotFound && (null === $componenteDigitalEntity)) {
            throw new NotFoundHttpException('Not found');
        }

        $conteudo = $this
            ->download($componenteDigitalEntity->getId(), $transactionId)->getConteudo();

        $hash = $componenteDigitalEntity->getHash();
        $filename = '/tmp/'.$hash;
        file_put_contents($filename, $conteudo);

        $ass = [];

        foreach ($componenteDigitalEntity->getAssinaturas() as $assinatura) {
            if (AssinaturaPadrao::PAdES->value === $assinatura->getPadrao()) {
                throw new RuntimeException('Documento com assinatura PAdES não pode ser exportado em formato p7s, favor, baixe o próprio PDF!');
            }
            if ('cadeia_teste' === $assinatura->getCadeiaCertificadoPEM()) {
                continue;
            }
            $data = base64_decode($assinatura->getAssinatura());
            if ($this->isPkcs7($data)) {
                $assFilename = '/tmp/ass'.$assinatura->getId().'.p7s';
                $ass[$assFilename] = 'ass'.$assinatura->getId();
                file_put_contents($assFilename, $data);
            }
        }

        if (!count($ass)) {
            throw new RuntimeException('Não existem assinaturas p7s válidas!');
        }

        $signerProxyParams = [];
        $signerProxy = $this->parameterBag->get('supp_core.administrativo_backend.signer_proxy');

        if ($signerProxy) {
            $signerProxyParams = explode(' ', $signerProxy);
        }
        $params = [
            'java',
            '-jar',
            '/usr/local/bin/supp-signer.jar',
            '--mode=attach',
            '--hash='.$hash,
            '--assinaturas='.implode(',', $ass),
        ];
        $process = new Process(
            array_merge($params, $signerProxyParams)
        );
        $process->run();
        if ($process->isSuccessful()) {
            $conteudo = file_get_contents($filename.'.p7s');
        }
        unlink($filename);
        unlink($filename.'.p7s');
        foreach ($ass as $f => $a) {
            unlink($f);
        }

        if (!$conteudo) {
            throw new RuntimeException('Erro! Não foi possível gerar o ZIP!');
        }

        $componenteDigitalResponse = new ComponenteDigitalDTO();
        $componenteDigitalResponse->setConteudo($conteudo);
        $componenteDigitalResponse->setExtensao('p7s');
        $componenteDigitalResponse->setMimetype('application/pkcs7-signature');
        $componenteDigitalResponse->setTamanho(strlen($conteudo));
        $componenteDigitalResponse->setFileName($componenteDigitalEntity->getFileName().'.p7s');

        $documentoResponse = new DocumentoDTO();
        $componenteDigitalResponse->setDocumento($documentoResponse);

        return $componenteDigitalResponse;
    }

    /**
     * @param string $assinatura
     *
     * @return bool
     */
    public function isPkcs7(string $assinatura): bool
    {
        $byteSignature = '30 80 06 09 2A 86 48 86 F7 0D 01 07 02';
        $hex_ary = [];
        $ok = false;
        foreach (str_split(substr($assinatura, 0, 15)) as $chr) {
            $hex_ary[] = sprintf('%02X', ord($chr));
        }
        $s = '';
        $maxSize = 0;
        foreach ($hex_ary as $item) {
            ++$maxSize;
            $s .= $item;
            if ($s === $byteSignature) {
                $ok = true;
                break;
            }
            $s .= ' ';
        }

        return $ok;
    }

    /**
     * @param string $conteudo
     * @param string $filename
     * @param string $transactionId
     *
     * @return Entity|null
     */
    public function renderHtmlContent(string $conteudo, string $filename, string $transactionId): ?Entity
    {
        $entity = new Entity();
        $restDto = (new ComponenteDigitalDTO())
            ->setConteudo(base64_decode($conteudo))
            ->setFileName($filename);
        // Before callback method call
        $this->beforeRenderHtmlContent($restDto, $entity, $transactionId);

        $this->afterRenderHtmlContent($restDto, $entity, $transactionId);

        return $entity;
    }

    /**
     * @param RestDtoInterface $dto
     * @param EntityInterface  $entity
     * @param string           $transactionId
     *
     * @return void
     */
    public function beforeRenderHtmlContent(RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertRenderHtmlContent');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeRenderHtmlContent');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeRenderHtmlContent');
    }

    /**
     * @param RestDtoInterface $dto
     * @param EntityInterface  $entity
     * @param string           $transactionId
     *
     * @return void
     */
    public function afterRenderHtmlContent(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId,
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterRenderHtmlContent');
    }

    /**
     * @param $ids
     *
     * @return mixed
     */
    public function findByIds($ids)
    {
        return $this->repository->findByIds($ids);
    }

    /**
     * Recupera o hash referente ao text/html mais recente.
     *
     * @param array $logs
     *
     * @return string|null
     */
    private function getLastHashOfHtml(array $logs): ?string
    {
        /*
         * O LogEntryRepository getLogEntries() retorna LogEntries ordenados pela versão
         * Atualmente, no ambiente de produção do SUPP, os logs não retornam em ordem, pois a versão é sempre 1
         */

        // Ordenar crescente por loggedAt
        usort($logs, fn (AbstractLogEntry $a, AbstractLogEntry $b) => ($a->getLoggedAt() < $b->getLoggedAt() ? -1 : 1));

        $mimeType = null;
        $hash = null;
        foreach ($logs as $logEntry) {
            /** @var AbstractLogEntry $logEntry */
            if (array_key_exists('mimetype', $logEntry?->getData())) {
                $mimeType = mb_strtolower($logEntry->getData()['mimetype']);
            }
            if ('text/html' === $mimeType && array_key_exists('hash', $logEntry?->getData())) {
                $hash = $logEntry->getData()['hash'];
            }
        }

        return $hash;
    }

    public function downloadVinculado(
        int $id,
        string $transactionId,
        ?bool $throwExceptionIfNotFound = null
    ): ComponenteDigitalDTO | Entity {
        /** @var Entity $componenteDigitalEntity */
        $componenteDigitalEntity = $this->getEntity($id);

        if ($throwExceptionIfNotFound && (null === $componenteDigitalEntity)) {
            throw new NotFoundHttpException('Not found');
        }

        $haveP7S = false;

        foreach ($componenteDigitalEntity->getAssinaturas() as $assinatura) {
            if ('cadeia_teste' === $assinatura->getCadeiaCertificadoPEM()) {
                continue;
            }
            $data = base64_decode($assinatura->getAssinatura());
            if ($this->isPkcs7($data)) {
                $haveP7S = true;
                continue;
            }
        }

        if ($haveP7S) {
            return $this->downloadP7s($id, $transactionId);
        }

        return $this->download($id, $transactionId);
    }

    /**
     * @param string    $dir
     * @param string    $filename
     *
     * @return ComponenteDigitalDTO
     *
     * @throws AnnotationException
     * @throws Exception
     * @throws LoaderError
     * @throws ReflectionException
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function downloadMinutaAnexos(
        string $dir,
        string $filename,
    ): ComponenteDigitalDTO {
        $conteudo = file_get_contents($dir . '/' . $filename);

        if (!$conteudo) {
            throw new RuntimeException('Erro! Não foi possível gerar o PDF!');
        }

        $componenteDigitalResponse = new ComponenteDigitalDTO();
        $componenteDigitalResponse->setConteudo($conteudo);
        $componenteDigitalResponse->setExtensao('pdf');
        $componenteDigitalResponse->setMimetype('application/pdf');
        $componenteDigitalResponse->setTamanho(strlen($conteudo));
        $componenteDigitalResponse->setFileName($filename);

        system("rm -rf ".escapeshellarg($dir));

        return $componenteDigitalResponse;
    }

    /**
     * @param ComponenteDigital $entity
     * 
     * @return string
     */
    public function getQRCodeAvaliacao(ComponenteDigital $entity) {
        foreach ($this->chancelaComponenteDigitalInterfaces as $chancelaInterface) {
            $documento = $entity->getDocumento();

            if($this->dadosFormularioRepository->findAnswersByFormularioDocumento($chancelaInterface->getIdFormularioAvaliacao(), $documento->getId()) < $chancelaInterface->getQtdMaximaRespostas()) {
                $processo = $documento?->getProcessoOrigem();

                if(isset($documento) && isset($processo)) {
                    if ($chancelaInterface->supports($processo, $documento)) {
                        $qrCode = $chancelaInterface->getQRCodeAvaliacao($processo, $documento);
                        if(isset($qrCode)) {
                            $base64 = 'data:image/png;base64,' . base64_encode($qrCode);
                            return "<br /><div style='text-align: center;'><img src='".$base64."' style='display: inline-block; vertical-align: middle; border: 2px solid #666; padding: 2px; width: 80px !important; height: 80px !important;' /><br />
                            <span style='display: inline-block; vertical-align: middle;  width: 150px; font-size: .9em; margin-top: 10px; color: #444;'>".$chancelaInterface->getDescricaoAvaliacao()."</span></div>";
                        }
                    }
                }
            }
        }
    }
}
