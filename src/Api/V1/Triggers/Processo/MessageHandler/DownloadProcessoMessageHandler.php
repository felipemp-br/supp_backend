<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/MessageHandler/DownloadProcessoMessageHandler.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\MessageHandler;

use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Gedmo\Blameable\BlameableListener;
use Knp\Snappy\Pdf;
use Psr\Log\LoggerInterface as Logger;
use RuntimeException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao as NotificacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoNotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\UsuarioResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo\Message\DownloadProcessoMessage;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Security\RolesService;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\ZipStream;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Throwable;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class DownloadProcessoMessageHandler.
 */
#[AsMessageHandler]
class DownloadProcessoMessageHandler
{
    /**
     * DownloadProcessoMessageHandler constructor.
     *
     * @param BlameableListener             $blameableListener
     * @param ProcessoResource              $processoResource
     * @param ComponenteDigitalResource     $componenteDigitalResource
     * @param UsuarioResource               $usuarioResource
     * @param ModalidadeNotificacaoResource $modalidadeNotificacaoResource
     * @param TipoNotificacaoResource       $tipoNotificacaoResource
     * @param Environment                   $twig
     * @param Pdf                           $pdfManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param ParameterBagInterface         $parameterBag
     * @param Logger                        $logger
     * @param TransactionManager            $transactionManager
     * @param TokenStorageInterface         $tokenStorage
     * @param RolesService                  $rolesService
     * @param NotificacaoResource           $notificacaoResource
     */
    public function __construct(
        private readonly BlameableListener $blameableListener,
        private ProcessoResource $processoResource,
        private ComponenteDigitalResource $componenteDigitalResource,
        private UsuarioResource $usuarioResource,
        private ModalidadeNotificacaoResource $modalidadeNotificacaoResource,
        private TipoNotificacaoResource $tipoNotificacaoResource,
        private Environment $twig,
        private Pdf $pdfManager,
        private AuthorizationCheckerInterface $authorizationChecker,
        private ParameterBagInterface $parameterBag,
        private Logger $logger,
        private TransactionManager $transactionManager,
        private TokenStorageInterface $tokenStorage,
        private RolesService $rolesService,
        private NotificacaoResource $notificacaoResource
    ) {
    }

    public function __invoke(DownloadProcessoMessage $message)
    {
        $processo = $this->processoResource->findOneBy(['uuid' => $message->getUuid()]);
        $usuario = $this->usuarioResource->findOneBy(['username' => $message->getUsername()]);

        $this->blameableListener->setUserValue($usuario);

        $token = new PostAuthenticationToken(
            $usuario,
            'user_provider',
            $this->rolesService->getContextualRoles($usuario)
        );

        $token->setAttribute('username', $usuario->getUsername());
        $this->tokenStorage->setToken($token);

        try {
            $transactionId = $this->transactionManager->begin();
            $componenteDigitalEntity = match ($message->getTypeDownload()) {
                $message::DOWNLOAD_AS_PDF => $this->downloadAsPdf(
                    $processo,
                    $message->getSequencial(),
                    $message->getPartNumber(),
                    $transactionId
                ),
                $message::DOWNLOAD_AS_ZIP => $this->downloadAsZip(
                    $processo,
                    $message->getSequencial(),
                    $message->getPartNumber(),
                    $transactionId
                )
            };

            $componenteDigitalEntity->setProcessoOrigem($processo);

            $this->transactionManager->commit($transactionId);
            $transactionId = $this->transactionManager->begin();

            $processo = $this->processoResource->findOneBy(['uuid' => $message->getUuid()]);
            $usuario = $this->usuarioResource->findOneBy(['username' => $message->getUsername()]);

            $modalidadeNotificacao = $this->modalidadeNotificacaoResource
                ->findOneBy(
                    ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]
                );
            $tipoNotificacao = $this->tipoNotificacaoResource->findOneBy(
                ['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_4')]
            );

            $contexto = json_encode(
                [
                    'id' => $processo->getId(),
                    'componente_digital_id' => $componenteDigitalEntity->getId(),
                    'documento_id' => $componenteDigitalEntity->getDocumento()->getId(),
                ]
            );

            $msg = '';
            if ($message->getPartNumber() > 0) {
                $msg = 'A PARTE '.$message->getPartNumber().'/'.$message->getTotalParts().' DO NUP ['.$processo->getNUP().'] ESTÁ PRONTO PARA DOWNLOAD.';
            } else {
                $msg = 'O NUP ['.$processo->getNUP().'] ESTÁ PRONTO PARA DOWNLOAD.';
            }

            $notificacaoDTO = (new NotificacaoDTO())
                ->setDestinatario($usuario)
                ->setModalidadeNotificacao($modalidadeNotificacao)
                ->setConteudo($msg)
                ->setTipoNotificacao($tipoNotificacao)
                ->setContexto($contexto);

            $this->notificacaoResource->create($notificacaoDTO, $transactionId);
            $this->transactionManager->commit($transactionId);
        } catch (Throwable $t) {
            $this->logger->critical($t->getMessage().' - '.$t->getTraceAsString());
            $this->transactionManager->resetTransaction();
            $transactionId = $this->transactionManager->begin();

            $processo = $this->processoResource->findOneBy(['uuid' => $message->getUuid()]);
            $usuario = $this->usuarioResource->findOneBy(['username' => $message->getUsername()]);
            $modalidadeNotificacao = $this->modalidadeNotificacaoResource
                ->findOneBy(
                    ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]
                );

            $msg = '';
            if ($message->getPartNumber() > 0) {
                $msg = 'A PARTE '.$message->getPartNumber().'/'.$message->getTotalParts().' DO NUP ['.$processo->getNUP().'] ESTÁ PRONTO PARA DOWNLOAD.';
            } else {
                $msg = 'O NUP ['.$processo->getNUP().'] ESTÁ PRONTO PARA DOWNLOAD.';
            }

            $notificacaoDTO = (new NotificacaoDTO())
                ->setDestinatario($usuario)
                ->setModalidadeNotificacao($modalidadeNotificacao)
                ->setConteudo($msg)
                ->setTipoNotificacao(null);

            $this->notificacaoResource->create($notificacaoDTO, $transactionId);
            $this->transactionManager->commit($transactionId);
        }
    }

    /**
     * @param Processo $processo
     * @param string|null $sequencial
     * @param int|null $parte
     * @param string $transactionId
     * @return ComponenteDigitalEntity
     * @throws LoaderError
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function downloadAsPdf(
        Processo $processo,
        ?string $sequencial,
        ?int $parte,
        string $transactionId
    ): ComponenteDigitalEntity {
        $sequenciasId = [];
        if ('all' !== $sequencial) {
            $sequenciasId = $this->processoResource->processaDigitosExpressaoDownload($sequencial);
        }

        $agora = new DateTime();
        $stamp = $agora->format('YmdHis');
        $tempFiles = [];
        $i = 0;

        foreach ($processo->getVolumes() as $volume) {
            foreach ($volume->getJuntadas() as $juntada) {
                if ($juntada->getVinculada()) {
                    continue;
                }

                $selecionado = false;
                if (!count($sequenciasId) ||
                    in_array(
                        $juntada->getNumeracaoSequencial(),
                        $sequenciasId
                    )) {
                    $selecionado = true;
                }

                $identificacao = 'SEQ '.str_pad(
                    (string) $juntada->getNumeracaoSequencial(),
                    3,
                    '0',
                    STR_PAD_LEFT
                );

                if ($selecionado && !$juntada->getAtivo()) {
                    $tempFileName = sys_get_temp_dir().'/'.$processo->getNUP().'_'.$i.'_'.$stamp.'.pdf';
                    $handle = fopen($tempFileName, 'w+');
                    fwrite(
                        $handle,
                        $this->pdfManager->getOutputFromHtml(
                            $this->twig->render(
                                $this->parameterBag
                                    ->get('supp_core.administrativo_backend.template_processo_pdf_desentranhamento'),
                                [
                                    'identificacao' => $identificacao,
                                    'descricao' => $juntada->getDescricao(),
                                    'logo' => $this->parameterBag
                                        ->get('supp_core.administrativo_backend.logo_instituicao'),
                                ]
                            )
                        )
                    );
                    $tempFiles[$tempFileName] = $identificacao.' - DESENTRANHADO';
                    ++$i;
                    continue;
                }

                if ($selecionado && !$this->authorizationChecker->isGranted(
                    'VIEW',
                    $juntada->getDocumento()
                )) {
                    $tempFileName = sys_get_temp_dir().'/'.$processo->getNUP().'_'.$i.'_'.$stamp.'.pdf';
                    $handle = fopen($tempFileName, 'w+');
                    fwrite(
                        $handle,
                        $this->pdfManager->getOutputFromHtml(
                            $this->twig->render(
                                $this->parameterBag
                                    ->get('supp_core.administrativo_backend.template_processo_pdf_acesso_negado'),
                                [
                                    'identificacao' => $identificacao,
                                    'descricao' => $juntada->getDescricao(),
                                    'logo' => $this->parameterBag
                                        ->get('supp_core.administrativo_backend.logo_instituicao'),
                                ]
                            )
                        )
                    );
                    $tempFiles[$tempFileName] = $identificacao.' - ACESSO RESTRITO';
                    ++$i;
                    continue;
                }

                if ($selecionado && $juntada->getDocumento()->getOrigemDados() &&
                    (1 !== $juntada->getDocumento()->getOrigemDados()->getStatus())
                ) {
                    $tempFileName = sys_get_temp_dir().'/'.$processo->getNUP().'_'.$i.'_'.$stamp.'.pdf';
                    $handle = fopen($tempFileName, 'w+');
                    fwrite(
                        $handle,
                        $this->pdfManager->getOutputFromHtml(
                            $this->twig->render(
                                $this->parameterBag
                                    ->get('supp_core.administrativo_backend.template_processo_pdf_erro_integracao'),
                                [
                                    'identificacao' => $identificacao,
                                    'descricao' => $juntada->getDescricao(),
                                    'logo' => $this->parameterBag
                                        ->get('supp_core.administrativo_backend.logo_instituicao'),
                                ]
                            )
                        )
                    );
                    $tempFiles[$tempFileName] = $identificacao.' - ERRO INTEGRAÇÃO';
                    ++$i;
                    continue;
                }

                $arrComponentesDigitais = [];

                if ($selecionado) {
                    $arrComponentesDigitais = $juntada->getDocumento()->getComponentesDigitais()->toArray();
                }

                foreach ($juntada->getDocumento()->getVinculacoesDocumentos() as $vinculacaoDocumento) {
                    if ($vinculacaoDocumento->getDocumentoVinculado()->getJuntadaAtual() &&
                        (count($sequenciasId) &&
                        !in_array(
                            $vinculacaoDocumento->getDocumentoVinculado()->getJuntadaAtual()->getNumeracaoSequencial(),
                            $sequenciasId
                        )
                    )
                    ) {
                        continue;
                    }

                    if ($vinculacaoDocumento->getDocumentoVinculado()->getJuntadaAtual() &&
                        $vinculacaoDocumento->getDocumentoVinculado()->getJuntadaAtual()->getAtivo() &&
                        $this->authorizationChecker->isGranted(
                            'VIEW',
                            $vinculacaoDocumento->getDocumentoVinculado()
                        )
                    ) {
                        $arrComponentesDigitais = array_merge(
                            $arrComponentesDigitais,
                            $vinculacaoDocumento->getDocumentoVinculado()->getComponentesDigitais()->toArray()
                        );
                    }
                }

                foreach ($arrComponentesDigitais as $componenteDigital) {
                    if (('text/html' == $componenteDigital->getMimetype()) ||
                        ('text/plain' == $componenteDigital->getMimetype())
                    ) {
                        try {
                            $tempFileName = sys_get_temp_dir().'/'.$processo->getNUP().
                                '_'.$i.'_'.$stamp.'.pdf';
                            $handle = fopen($tempFileName, 'w+');
                            fwrite(
                                $handle,
                                $this->componenteDigitalResource->download(
                                    $componenteDigital->getId(),
                                    $transactionId,
                                    true,
                                    true
                                )->getConteudo()
                            );
                            $tempFiles[$tempFileName] = $identificacao.' - '.
                                $juntada->getDocumento()->getTipoDocumento()->getNome();
                            ++$i;
                        } catch (Throwable $e) {
                            $tempFileName = sys_get_temp_dir().'/'.$processo->getNUP().
                                '_'.$i.'_'.$stamp.'.pdf';
                            $handle = fopen($tempFileName, 'w+');
                            fwrite(
                                $handle,
                                $this->pdfManager->getOutputFromHtml(
                                    $this->twig->render(
                                        $this->parameterBag
                                            ->get(
                                                'supp_core.administrativo_backend'
                                                .'.template_processo_pdf_erro_desconhecido'
                                            ),
                                        [
                                            'identificacao' => $identificacao,
                                            'descricao' => $juntada->getDescricao(),
                                            'logo' => $this->parameterBag
                                                ->get('supp_core.administrativo_backend.logo_instituicao'),
                                        ]
                                    )
                                )
                            );
                            $tempFiles[$tempFileName] = $identificacao.' - '.
                                $juntada->getDocumento()->getTipoDocumento()->getNome();
                            ++$i;
                        }
                    } elseif ('application/pdf' == $componenteDigital->getMimetype()) {
                        $tempFileName = sys_get_temp_dir().'/'.$processo->getNUP().
                            '_'.$i.'_'.$stamp.'.pdf';
                        $handle = fopen($tempFileName, 'w+');
                        fwrite(
                            $handle,
                            $this->componenteDigitalResource->download(
                                $componenteDigital->getId(),
                                $transactionId
                            )->getConteudo()
                        );
                        $tempFiles[$tempFileName] = $identificacao.' - '.
                            $juntada->getDocumento()->getTipoDocumento()->getNome();
                        ++$i;
                    } else {
                        $tempFileName = sys_get_temp_dir().'/'.$processo->getNUP().
                            '_'.$i.'_'.$stamp.'.pdf';
                        $handle = fopen($tempFileName, 'w+');
                        fwrite(
                            $handle,
                            $this->pdfManager->getOutputFromHtml(
                                $this->twig->render(
                                    $this->parameterBag
                                        ->get(
                                            'supp_core.administrativo_backend.template_processo_pdf_erro_extensao'
                                        ),
                                    [
                                        'identificacao' => $identificacao,
                                        'descricao' => $juntada->getDescricao(),
                                        'extensao' => $componenteDigital->getExtensao(),
                                        'logo' => $this->parameterBag
                                            ->get('supp_core.administrativo_backend.logo_instituicao'),
                                    ]
                                )
                            )
                        );
                        $tempFiles[$tempFileName] = $identificacao.' - '.
                            $juntada->getDocumento()->getTipoDocumento()->getNome();
                        ++$i;
                    }
                }

                if ($selecionado && empty($arrComponentesDigitais)) {
                    $tempFileName = sys_get_temp_dir().'/'.$processo->getNUP().'_'.$i.'_'.$stamp.'.pdf';
                    $handle = fopen($tempFileName, 'w+');
                    fwrite(
                        $handle,
                        $this->pdfManager->getOutputFromHtml(
                            $this->twig->render(
                                $this->parameterBag
                                    ->get('supp_core.administrativo_backend.template_processo_pdf_sem_componentes'),
                                [
                                    'identificacao' => $identificacao,
                                    'descricao' => $juntada->getDescricao(),
                                    'logo' => $this->parameterBag
                                        ->get('supp_core.administrativo_backend.logo_instituicao'),
                                ]
                            )
                        )
                    );
                    $tempFiles[$tempFileName] = $identificacao.' - '.
                        $juntada->getDocumento()->getTipoDocumento()->getNome();
                    ++$i;
                }
            }
        }

        $files = [];
        foreach ($tempFiles as $fileName => $bookmark) {
            $files[] = $fileName;
        }

        $out = sys_get_temp_dir().'/'.$processo->getNUP().'.pdf';
        $cmd = 'gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE='.$out.' -dBATCH '.implode(' ', $files);
        shell_exec($cmd);

        $conteudo = file_get_contents($out);

        // Apaga os arquivos temporários
        $files[] = $out;
        foreach ($files as $file) {
            unlink($file);
        }

        if (!$conteudo) {
            $this->logger->info('Erro ao gerar PDF do NUP: '.$processo->getNUP().' no processo de download');
            throw new RuntimeException('Erro ao gerar PDF do NUP: '.$processo->getNUP().' no processo de download');
        }

        $componenteDigitalDto = (new ComponenteDigitalDTO())
            ->setConteudo($conteudo)
            ->setHash(hash('SHA256', $conteudo))
            ->setExtensao('pdf')
            ->setMimetype('application/pdf')
            ->setTamanho(strlen($conteudo))
            ->setFileName($processo->getNUP().($parte > 0 ? '_PARTE_'.$parte : '').'.pdf');

        return $this->componenteDigitalResource->create($componenteDigitalDto, $transactionId);
    }

    /**
     * @param Processo $processo
     * @param string|null $sequencial
     * @param int|null $parte
     * @param string $transactionId
     * @return ComponenteDigitalEntity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function downloadAsZip(
        Processo $processo,
        ?string $sequencial,
        ?int $parte,
        string $transactionId
    ): ComponenteDigitalEntity {
        $sequenciasId = [];
        if ('all' !== $sequencial) {
            $sequenciasId = $this->processoResource->processaDigitosExpressaoDownload($sequencial);
        }

        $zipStream = new ZipStream();
        $ok = false;

        foreach ($processo->getVolumes() as $volume) {
            foreach ($volume->getJuntadas() as $juntada) {
                if ($juntada->getVinculada()) {
                    continue;
                }

                if ($juntada->getAtivo() &&
                    $this->authorizationChecker->isGranted('VIEW', $juntada->getDocumento())
                ) {
                    $arrComponentesDigitais = [];

                    if (!count($sequenciasId) ||
                        in_array(
                            $juntada->getNumeracaoSequencial(),
                            $sequenciasId
                        )) {
                        $arrComponentesDigitais = $juntada->getDocumento()->getComponentesDigitais()->toArray();
                    }

                    foreach ($juntada->getDocumento()->getVinculacoesDocumentos() as $vinculacaoDocumento) {
                        if ($vinculacaoDocumento->getDocumentoVinculado()->getJuntadaAtual() &&
                            (count($sequenciasId) &&
                                !in_array(
                                    $vinculacaoDocumento->getDocumentoVinculado()->getJuntadaAtual()
                                        ->getNumeracaoSequencial(),
                                    $sequenciasId
                                )
                            )
                        ) {
                            continue;
                        }

                        if ($vinculacaoDocumento->getDocumentoVinculado()->getJuntadaAtual() &&
                            $vinculacaoDocumento->getDocumentoVinculado()->getJuntadaAtual()->getAtivo() &&
                            $this->authorizationChecker->isGranted(
                                'VIEW',
                                $vinculacaoDocumento->getDocumentoVinculado()
                            )
                        ) {
                            $arrComponentesDigitais = array_merge(
                                $arrComponentesDigitais,
                                $vinculacaoDocumento->getDocumentoVinculado()->getComponentesDigitais()->toArray()
                            );
                        }
                    }

                    foreach ($arrComponentesDigitais as $componenteDigital) {
                        $identificacao = 'SEQ '.str_pad(
                            (string) $juntada->getNumeracaoSequencial(),
                            3,
                            '0',
                            STR_PAD_LEFT
                        );
                        $identificacao .= ' - '.$componenteDigital->getCriadoEm()->format('d-m-Y H:i:s');
                        if (!$componenteDigital->getEditavel()) {
                            $identificacao .= ' - '.$componenteDigital->getDocumento()->getTipoDocumento()->getNome();
                        }
                        $identificacao .= ' - '.basename($componenteDigital->getFileName());

                        $conteudo = $this->componenteDigitalResource
                            ->download($componenteDigital->getId(), $transactionId)->getConteudo();

                        $zipStream->addFile(
                            $conteudo,
                            $identificacao
                        );
                        $ok = true;
                    }
                }
            }
        }

        if (!$ok || !$conteudo) {
            $this->logger->info('Eror ao gerar ZIP do NUP: '.$processo->getNUP());
            throw new RuntimeException('Erro! Não foi possível gerar o ZIP completo!');
        }

        $zipStream->setComment('Download de documentos do Sistema SUPP');
        $conteudo = $zipStream->getZipData();

        $componenteDigitalDto = (new ComponenteDigitalDTO())
            ->setConteudo($conteudo)
            ->setExtensao('zip')
            ->setHash(hash('SHA256', $conteudo))
            ->setMimetype('application/zip')
            ->setTamanho(strlen($conteudo))
            ->setFileName($processo->getNUP().($parte > 0 ? '_PARTE_'.$parte : '').'.zip');

        return $this->componenteDigitalResource->create($componenteDigitalDto, $transactionId);
    }
}
