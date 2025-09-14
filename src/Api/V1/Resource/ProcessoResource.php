<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/ProcessoResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use Exception;
use Knp\Snappy\Pdf;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as Entity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Utils\ZipStream;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Symfony\Component\Mime\Email;
use Throwable;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\UsuarioRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Model\AclProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class ProcessoResource.
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
class ProcessoResource extends RestResource
{
    private ComponenteDigitalResource $componenteDigitalResource;

    private Pdf $pdfManager;

    private AuthorizationCheckerInterface $authorizationChecker;

    private Environment $twig;

    private ParameterBagInterface $parameterBag;

    private ZipStream $zipStream;

    /**
     * ProcessoResource constructor.
     */
    public function __construct(
        Environment $twig,
        Repository $repository,
        ValidatorInterface $validator,
        AuthorizationCheckerInterface $authorizationChecker,
        ComponenteDigitalResource $componenteDigitalResource,
        Pdf $pdfManager,
        ParameterBagInterface $parameterBag,
        ZipStream $zipStream,
        private MailerInterface $mailer,
        private TokenStorageInterface $tokenStorage,
        private UsuarioRepository $usuarioRepository,
        private SetorRepository $setorRepository,
        private AclProviderInterface $aclProvider
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(Processo::class);
        $this->twig = $twig;
        $this->authorizationChecker = $authorizationChecker;
        $this->componenteDigitalResource = $componenteDigitalResource;
        $this->pdfManager = $pdfManager;
        $this->parameterBag = $parameterBag;
        $this->zipStream = $zipStream;
    }

    /**
     * @param Processo|RestDtoInterface $dto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws Exception
     */
    public function arquivar(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null
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
        $this->beforeArquivar($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterArquivar($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeArquivar(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeArquivar');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeArquivar');
    }

    public function afterArquivar(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->triggersManager->proccess(null, $entity, $transactionId, 'afterArquivar');
    }

    /**
     * @param Processo|RestDtoInterface $dto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws Exception
     */
    public function autuar(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        ?bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        $restDto->setUnidadeArquivistica(ProcessoEntity::UA_PROCESSO);

        if (ProcessoEntity::UA_DOSSIE === $entity->getUnidadeArquivistica()) {
            $restDto->setTipoProtocolo(ProcessoEntity::TP_NOVO);
        }

        if (ProcessoEntity::UA_DOCUMENTO_AVULSO === $entity->getUnidadeArquivistica()) {
            $restDto->setTipoProtocolo(ProcessoEntity::TP_INFORMADO);
        }

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeAutuar($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterAutuar($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeAutuar(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertAutuar');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeAutuar');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeAutuar');
    }

    public function afterAutuar(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->triggersManager->proccess(null, $entity, $transactionId, 'afterAutuar');
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function download(int $id, string $transactionId): ?EntityInterface
    {
        $entity = $this->findOne($id);
        $dto = $this->getDtoForEntity(
            $id,
            $this->getDtoClass(),
            null,
            $entity
        );

        $this->validateDto($dto, false);
        $this->beforeDownload($dto, $entity, $transactionId);
        $this->afterDownload($dto, $entity, $transactionId);

        return $entity;
    }

    /**
     * Before lifecycle method for download method.
     */
    public function beforeDownload(RestDtoInterface $dto,
                                    EntityInterface $entity,
                                    string $transactionId): void
    {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertDownload');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeDownload');
    }

    /**
     * After lifecycle method for download method.
     */
    public function afterDownload(RestDtoInterface $dto,
                                    EntityInterface $entity,
                                    string $transactionId): void
    {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterDownload');
    }

    /**
     * Processa os digitos conforme expressão passada no download do processo.
     * @param string|null $expressao
     * @return array
     */
    public function processaDigitosExpressaoDownload(?string $expressao): array
    {
        $digitos = [];
        if (!strlen($expressao)) {
            return $digitos;
        }
        $intervalos = explode(',', $expressao);
        foreach ($intervalos as $intervalo) {
            $inicioFim = explode('-', $intervalo);
            if (count($inicioFim) > 1) {
                for ($j = min($inicioFim); $j <= max($inicioFim); ++$j) {
                    $digitos[] = (int) $j;
                }
            } else {
                $digitos[] = (int) $inicioFim[0];
            }
        }

        return $digitos;
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
     * @param EntityInterface|Entity $processo
     * @param Usuario|Entity $remetente
     * @param array $destinatarios
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Throwable
     */
    private function enviarEmail(EntityInterface $processo, Usuario $remetente, array $destinatarios, string $justificativa): bool
    {
        ;
        $message = (new Email())
            ->subject('Solicitação de acesso ao processo pelo '.$this->parameterBag
                ->get('supp_core.administrativo_backend.nome_sistema'))
            ->from($this->parameterBag->get('supp_core.administrativo_backend.email_suporte'))
            ->replyTo($remetente->getEmail())
            ->to(...$destinatarios)
            ->html(
                $this->twig->render(
                    $this->parameterBag->get('supp_core.administrativo_backend.template_envio_solicitacao_acesso_email'),
                    [
                        'usuario' => $remetente->getNome(),
                        'justificativa' => $justificativa,
                        'sistema' => $this->parameterBag->get('supp_core.administrativo_backend.nome_sistema'),
                        'ambiente' => $this->parameterBag->get(
                            'supp_core.administrativo_backend.kernel_environment_mapping'
                        )[$this->parameterBag->get('kernel.environment')],
                        'NUP' => $processo->getNUPFormatado(),
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
