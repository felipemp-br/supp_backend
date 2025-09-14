<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/JuntadaResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use DateInterval;
use DateTime;
use Knp\Snappy\Pdf;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumentoAssinaturaExterna as VinculacaoDocumentoAssinaturaExternaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada as Entity;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\Documento as DocumentoEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumento;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumentoAssinaturaExterna;
use SuppCore\AdministrativoBackend\Entity\VinculacaoPessoaUsuario;
use SuppCore\AdministrativoBackend\Repository\JuntadaRepository as Repository;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoDocumentoAssinaturaExternaRepository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class JuntadaResource.
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
class JuntadaResource extends RestResource
{
/** @noinspection MagicMethodsValidityInspection */
    private RequestStack $requestStack;

    private UsuarioResource $usuarioResource;
    private MailerInterface $mailer;
    private Environment $twig;
    private ParameterBagInterface $parameterBag;
    private ComponenteDigitalResource $componenteDigitalResource;
    private Pdf $pdfManager;

    /**
     * JuntadaResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        UsuarioResource $usuarioResource,
        MailerInterface $mailer,
        Environment $twig,
        ParameterBagInterface $parameterBag,
        ComponenteDigitalResource $componenteDigitalResource,
        Pdf $pdfManager,
        private ProcessoResource $processoResource,
        private TarefaResource $tarefaResource,
        protected SetorRepository $setorRepository,
        protected VinculacaoDocumentoAssinaturaExternaRepository $vinculacaoDocumentoAssinaturaExternaRepository,
        private EspecieTarefaResource $especieTarefaResource,
        private VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource,
        private TokenStorageInterface $tokenStorage,
        private DocumentoResource $documentoResource,
        private TipoDocumentoResource $tipoDocumentoResource,
        private VinculacaoDocumentoAssinaturaExternaResource $vinculacaoDocumentoAssinaturaExternaResource,
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(Juntada::class);
        $this->usuarioResource = $usuarioResource;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->parameterBag = $parameterBag;
        $this->componenteDigitalResource = $componenteDigitalResource;
        $this->pdfManager = $pdfManager;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Throwable
     */
    public function sendEmailMethod(Request $request, int $id, string $transactionId, array $context = null): EntityInterface
    {
        //fetch $entity
        $entity = $this->getEntity($id);

        /** @var Juntada $restDto */
        $restDto = $this->getDtoForEntity(
            $id,
            Juntada::class,
            null,
            $entity
        );

        $this->beforeSendEmail($id, $restDto, $entity, $transactionId);

        $usuarioEnvio = $this->findUserContext($request);

        $this->enviarEmail($entity, $usuarioEnvio, $transactionId, $context);

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

    private function findUserContext(Request $request): Usuario
    {
        $entity = null;
        $context = null;

        if (null !== $request->get('context')) {
            $context = json_decode($request->get('context'));
        }

        if (null !== $context) {
            if (isset($context->usuario) && null !== $context->usuario) {
                $entity = $this->usuarioResource->findOne((int) $context->usuario);
            }
        }

        if (null === $entity) {
            throw new NotFoundHttpException('User to send not found');
        }

        return $entity;
    }

    /**
     * @param EntityInterface|Entity $juntada
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Throwable
     */
    private function enviarEmail(EntityInterface $juntada, Usuario $usuario, string $transactionId, array $context = null): bool
    {
        $message = (new Email())
            ->subject('Envio de Documentos pelo '.$this->parameterBag
                ->get('supp_core.administrativo_backend.nome_sistema'))
            ->from($this->parameterBag->get('supp_core.administrativo_backend.email_suporte'))
            ->to($usuario->getEmail())
            ->html(
                $this->twig->render(
                    $this->parameterBag->get('supp_core.administrativo_backend.template_envio_documentos_email'),
                    [
                        'sistema' => $this->parameterBag->get('supp_core.administrativo_backend.nome_sistema'),
                        'ambiente' => $this->parameterBag->get(
                            'supp_core.administrativo_backend.kernel_environment_mapping'
                        )[$this->parameterBag->get('kernel.environment')],
                        'NUP' => $juntada->getVolume()->getProcesso()->getNUPFormatado(),
                        'mensagem' => $context['mensagem'] ?? null
                    ]
                ),
            );

        try {
            $arquivosCriados = [];
            $success = true;
            $tempPath = sys_get_temp_dir().'/mail_'.rand(1, 999999);
            mkdir($tempPath);
            /** @var ComponenteDigital $componenteDigital */
            foreach ($juntada->getDocumento()->getComponentesDigitais() as $componenteDigital) {
                $arquivoParaEnvio = $this->componenteDigitalResource->downloadVinculado($componenteDigital->getId(), $transactionId);

                if($arquivoParaEnvio->getExtensao() === 'pdf' || $arquivoParaEnvio->getExtensao() === 'p7s') {
                    $pathWithFilename = $tempPath.'/'.$arquivoParaEnvio->getFileName();

                    $tmpFile = fopen($pathWithFilename, 'w');
                    fwrite($tmpFile, $arquivoParaEnvio->getConteudo());
                    fclose($tmpFile);
                    $arquivosCriados[] = $pathWithFilename;
                    $message->attachFromPath($pathWithFilename);
                } else {
                    $arquivoParaEnvio->setMimetype('application/pdf');
                    $arquivoParaEnvio->setExtensao('pdf');
                    $arquivoParaEnvio->setFileName(
                        str_replace(
                            '.html',
                            '.pdf',
                            str_replace('.HTML', '.pdf', $arquivoParaEnvio->getFileName())
                        )
                    );
                    $pathWithFilename = $tempPath.'/'.$arquivoParaEnvio->getFileName();

                    $tmpFile = fopen($pathWithFilename, 'w');
                    fwrite($tmpFile, $arquivoParaEnvio->getConteudo());
                    fclose($tmpFile);
                    $arquivosCriados[] = $pathWithFilename;
                    $message->attachFromPath($pathWithFilename);
                }
            }
            if ($juntada->getDocumento()->getVinculacoesDocumentos()->count() > 0) {
                foreach ($juntada->getDocumento()->getVinculacoesDocumentos() as $anexos) {
                    /** @var VinculacaoDocumento $anexos */
                    foreach ($anexos->getDocumentoVinculado()->getComponentesDigitais() as $componenteDigital) {
                        $arquivoParaEnvio = $this->componenteDigitalResource->downloadVinculado($componenteDigital->getId(), $transactionId);

                        if($arquivoParaEnvio->getExtensao() === 'pdf' || $arquivoParaEnvio->getExtensao() === 'p7s') {
                            $pathWithFilename = $tempPath.'/'.$arquivoParaEnvio->getFileName();

                            $tmpFile = fopen($pathWithFilename, 'w');
                            fwrite($tmpFile, $arquivoParaEnvio->getConteudo());
                            fclose($tmpFile);
                            $arquivosCriados[] = $pathWithFilename;
                            $message->attachFromPath($pathWithFilename);
                        } else {
                            $arquivoParaEnvio->setMimetype('application/pdf');
                            $arquivoParaEnvio->setExtensao('pdf');
                            $arquivoParaEnvio->setFileName(
                                str_replace(
                                    '.html',
                                    '.pdf',
                                    str_replace('.HTML', '.pdf', $arquivoParaEnvio->getFileName())
                                )
                            );
                            $pathWithFilename = $tempPath.'/'.$arquivoParaEnvio->getFileName();

                            $tmpFile = fopen($pathWithFilename, 'w');
                            fwrite($tmpFile, $arquivoParaEnvio->getConteudo());
                            fclose($tmpFile);
                            $arquivosCriados[] = $pathWithFilename;
                            $message->attachFromPath($pathWithFilename);
                        }
                    }
                }
            }
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

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Throwable
     */
    public function protocoloNupExistenteMethod(Request $request, int $id, string $transactionId): EntityInterface
    {
        $content = json_decode($request->getContent(), true);

        $vinculacaoUsuario = $this->vinculacaoPessoaUsuarioResource->getRepository()
            ->findBy(['usuarioVinculado' => $this->tokenStorage->getToken()->getUser()]);

        $vinculados = [];
        /** @var VinculacaoPessoaUsuario $vinc */
        foreach ($vinculacaoUsuario as $vinc) {
            $vinculados[] = $vinc->getPessoa()->getNome();
        }

        $processo = $this->processoResource->getRepository()->find($id);
        $restDto = null;

        $documentoEntity = $this->createDocumentoNupExistente($processo, $transactionId);
        $this->createComponenteDigitalNupExistente($vinculados, $content, $documentoEntity, $processo, $transactionId);
        $this->createTarefaNupExistente($id, $restDto, $processo, $transactionId);
        $entity = $this->createJuntadaNupExistente($documentoEntity, $processo, $transactionId);


        return $entity;
    }

    private function createJuntadaNupExistente(
        EntityInterface $documento,
        EntityInterface $processo,
        string $transactionId
    ): Entity {

        //RESGATA O VOLUME DO PROCESSO E JUNTA O DOCUMENTO
        $volume = $processo->getVolumes()[0];

        if($documento->getJuntadaAtual()){
            $juntadaEntity = $documento->getJuntadaAtual();
        }else {
            $juntadaDTO = new Juntada();
            $juntadaDTO->setDocumento($documento);
            $juntadaDTO->setVolume($volume);
            $juntadaDTO->setDescricao('DOCUMENTO RECEBIDO VIA PROTOCOLO EXTERNO');

            $juntadaEntity = $this->create($juntadaDTO, $transactionId);
        }

        return $juntadaEntity;
    }

    private function createDocumentoNupExistente(
        EntityInterface $processo,
        string $transactionId
    ): DocumentoEntity {

        $tipoDocumento = $this->tipoDocumentoResource
            ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_documento.const_2')]);
        $documentoDTO = new Documento();
        $documentoDTO->setTipoDocumento($tipoDocumento);
        $documentoDTO->setProcessoOrigem($processo);
        $documento = $this->documentoResource->create($documentoDTO, $transactionId);

        return $documento;
    }

    private function createComponenteDigitalNupExistente(
        array $vinculados,
        array $content,
        EntityInterface $documento,
        EntityInterface $processo,
        string $transactionId
    ): void {
        //CRIA DOCUMENTO DE REQUERIMENTO
        $data = new DateTime();
        $conteudoHTML = '<p><b>PROTOCOLO ELETRÔNICO</b></p>';
        $conteudoHTML .= '<p>REALIZADO POR: '.$this->tokenStorage->getToken()->getUser()->getNome().' </p>';
        $conteudoHTML .= '<p>EMAIL CADASTRADO: '.$this->tokenStorage->getToken()->getUser()->getEmail().' </p>';
        $conteudoHTML .= '<p>EM NOME DE:'.implode(', ', $vinculados).'</p>';
        $conteudoHTML .= '<p>NA DATA: '.$data->format('d/m/Y H:i:s').'</p>';
        $conteudoHTML .= '<p>TIPO:'.$content['requerimento'].'</p>';
        $conteudoHTML .= '<p>PARA: '.$processo->getSetorAtual()->getNome().'('.
            $processo->getSetorAtual()->getUnidade()->getSigla().')'.'</p>';
        $conteudoHTML .= '<p></p>';
        $conteudoHTML .= '<p>'.$content['descricao'].'</p>';

        $conteudoHTML = '<html><head></head><body>'.$conteudoHTML.'</body></html>';
        $componenteDigitalDTO = new \SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital();
        $componenteDigitalDTO->setProcessoOrigem($processo);
        $componenteDigitalDTO->setMimetype('text/html');
        $componenteDigitalDTO->setExtensao('html');
        $componenteDigitalDTO->setEditavel(false);
        $componenteDigitalDTO->setNivelComposicao(3);
        $componenteDigitalDTO->setFileName('REQUERIMENTO.html');
        $componenteDigitalDTO->setConteudo($conteudoHTML);
        $componenteDigitalDTO->setHash(hash('SHA256', $componenteDigitalDTO->getConteudo()));
        $componenteDigitalDTO->setTamanho(strlen($conteudoHTML));
        $componenteDigitalDTO->setDocumento($documento);

        $this->componenteDigitalResource->create($componenteDigitalDTO, $transactionId);
    }

    private function createTarefaNupExistente(
        int &$id,
        ?RestDtoInterface $dto,
        EntityInterface $processo,
        string $transactionId
    ): void {
        //CRIA TAREFA PARA O PROTOCOLO DA UNIDADE DO SETOR ATUAL
        $inicioPrazo = new DateTime();
        $finalPrazo = new DateTime();
        $finalPrazo->add(new DateInterval('P5D'));
        $especieTarefa = $this->especieTarefaResource->findOneBy(
            [
                'nome' => $this->parameterBag->get('constantes.entidades.especie_tarefa.const_5'),
            ]
        );

        $setorResponsavel = $this->setorRepository->findProtocoloInUnidade(
            $processo->getSetorAtual()->getUnidade()->getId());

        $tarefaDTO = new Tarefa();
        $tarefaDTO->setProcesso($processo);
        $tarefaDTO->setSetorResponsavel($setorResponsavel); //PROTOCOLO DA UNIDADE ENVIADA
        $tarefaDTO->setEspecieTarefa($especieTarefa);
        $tarefaDTO->setDataHoraInicioPrazo($inicioPrazo);
        $tarefaDTO->setDataHoraFinalPrazo($finalPrazo);

        $this->tarefaResource->create($tarefaDTO, $transactionId);
    }
}
