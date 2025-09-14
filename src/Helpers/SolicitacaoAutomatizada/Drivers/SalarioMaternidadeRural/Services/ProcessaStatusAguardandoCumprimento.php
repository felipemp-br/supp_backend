<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Services;

use DateInterval;
use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DadosFormulario as DadosFormularioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso as DocumentoAvulsoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DadosFormularioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\DocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieDocumentoAvulsoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\FormularioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModeloResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as DocumentoAvulsoEntity;
use SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada as SolicitacaoAutomatizadaEntity;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Models\DadosTipoSolicitacaoSalarioMaternidadeRural;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\SalarioMaternidadeRuralDriver;
use SuppCore\AdministrativoBackend\Repository\VolumeRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;

/**
 * ProcessaStatusAguardandoCumprimento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ProcessaStatusAguardandoCumprimento
{
    /**
     * Constructor.
     *
     * @param TarefaResource                 $tarefaResource
     * @param SetorResource                  $setorResource
     * @param DocumentoAvulsoResource        $documentoAvulsoResource
     * @param EspecieTarefaResource          $especieTarefaResource
     * @param EspecieDocumentoAvulsoResource $especieDocumentoAvulsoResource
     * @param ModeloResource                 $modeloResource
     * @param FormularioResource             $formularioResource
     * @param DadosFormularioResource        $dadosFormularioResource
     * @param PessoaResource                 $pessoaResource
     * @param Environment                    $twig
     * @param TokenStorageInterface          $tokenStorage
     * @param JuntadaResource                $juntadaResource
     * @param VolumeRepository               $volumeRepository
     */
    public function __construct(
        protected readonly TarefaResource $tarefaResource,
        protected readonly SetorResource $setorResource,
        protected readonly DocumentoAvulsoResource $documentoAvulsoResource,
        protected readonly EspecieTarefaResource $especieTarefaResource,
        protected readonly EspecieDocumentoAvulsoResource $especieDocumentoAvulsoResource,
        protected readonly ModeloResource $modeloResource,
        protected readonly FormularioResource $formularioResource,
        protected readonly DadosFormularioResource $dadosFormularioResource,
        protected readonly PessoaResource $pessoaResource,
        protected readonly Environment $twig,
        protected readonly TokenStorageInterface $tokenStorage,
        protected readonly JuntadaResource $juntadaResource,
        protected readonly VolumeRepository $volumeRepository,
    ) {
    }

    /**
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver
     * @param string                        $transactionId
     *
     * @return void
     */
    public function handle(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        string $transactionId
    ): void {
        $configSalarioMaternidadeRural = $salarioMaternidadeRuralDriver->getConfigModulo();
        $dadosTipoSolicitacao = $salarioMaternidadeRuralDriver
            ->deserializeDadosTipoSolicitacaoSalarioMaternidadeRural(
                $solicitacaoDTO->getDadosTipoSolicitacao()
            );
        switch (true) {
            case !$dadosTipoSolicitacao->getDeteminadoCumprimentoSentenca():
                $this->determinaCumprimentoSentenca(
                    $solicitacaoEntity,
                    $solicitacaoDTO,
                    $salarioMaternidadeRuralDriver,
                    $transactionId,
                    $dadosTipoSolicitacao
                );
                break;
            case !$solicitacaoDTO->getTarefaAcompanhamentoCumprimento():
                $dataPrazoVerificacao = (new DateTime(
                    sprintf(
                        '-%s days',
                        $configSalarioMaternidadeRural['dias_prazo_verificacao_cumprimento']
                    )
                ))->setTime(0, 0);
                /** @var DocumentoAvulsoEntity $documentoAvulso */
                $documentoAvulso = $this->documentoAvulsoResource->findOneBy([
                    'tarefaOrigem' => $solicitacaoDTO->getTarefaDadosCumprimento()
                ]);
                if (!$documentoAvulso->getDataHoraResposta()
                    && $documentoAvulso->getCriadoEm() <= $dataPrazoVerificacao) {
                    $this->criaTarefaAcompanhamentoPrazoCumprimento(
                        $solicitacaoEntity,
                        $solicitacaoDTO,
                        $salarioMaternidadeRuralDriver,
                        $transactionId,
                        $configSalarioMaternidadeRural
                    );
                }
                break;
            default:
                /** @var DocumentoAvulsoEntity $documentoAvulso */
                $documentoAvulso = $this->documentoAvulsoResource->findOneBy([
                    'tarefaOrigem' => $solicitacaoDTO->getTarefaDadosCumprimento()
                ]);
                if ($documentoAvulso->getDataHoraResposta()) {
                    $this->processaRespostaOficioCumprimento(
                        $solicitacaoEntity,
                        $solicitacaoDTO,
                        $salarioMaternidadeRuralDriver,
                        $transactionId
                    );
                }
                break;
        }
    }

    /**
     * @param SolicitacaoAutomatizadaEntity               $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO                  $solicitacaoDTO
     * @param SalarioMaternidadeRuralDriver               $salarioMaternidadeRuralDriver
     * @param string                                      $transactionId
     * @param DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao
     *
     * @return void
     */
    protected function determinaCumprimentoSentenca(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        string $transactionId,
        DadosTipoSolicitacaoSalarioMaternidadeRural $dadosTipoSolicitacao
    ): void {
        $configSalarioMaternidadeRural = $salarioMaternidadeRuralDriver->getConfigModulo();

        // faz a juntada do termo de deferimento
        $template = 'Resources/SolicitacaoAutomatizada/SalarioMaternidadeRural/deferimento.html.twig';
        $dadosTemplate = [
            'processo' => $solicitacaoDTO->getProcesso(),
            'beneficiario' => $solicitacaoDTO->getBeneficiario(),
            'dadosSolicitacao' => $dadosTipoSolicitacao,
        ];
        $conteudo = "";
        if ($this->twig->getLoader()->exists($template)) {
            $conteudo = $this->twig->render(
                $template,
                $dadosTemplate
            );
        }
        if (!empty($conteudo)) {
            $documentoEntity = $salarioMaternidadeRuralDriver->criarDocumento(
                $conteudo,
                $transactionId
            );
            $juntadaDTO = (new Juntada())
                ->setDocumento($documentoEntity)
                ->setDescricao('Deferimento de solicitação')
                ->setVolume($this->volumeRepository->findVolumeAbertoByProcessoId(
                    $solicitacaoDTO->getProcesso()->getId())
                );
            $this->juntadaResource->create($juntadaDTO, $transactionId);
        }

        // faz a juntada do oficio enviado
        $especieDocumentoAvulsoEntity = $this->especieDocumentoAvulsoResource->findOneBy([
            'nome' => $configSalarioMaternidadeRural['especie_documento_avulso_cumprimento'],
        ]);
        $pessoaDestino = $this->pessoaResource->findOne(
            (int)$configSalarioMaternidadeRural['id_pessoa_cumprimento_destino']
        );
        $modeloEntity = $this->modeloResource->findOneBy([
            'nome' => $configSalarioMaternidadeRural['modelo_documento_avulso_cumprimento'],
        ]);
        //o- @todo quais os dados para o contexto específico do modelo??
        $modeloEntity->setContextoEspecifico([
            'solicitacaoEntity' => $solicitacaoEntity,
            'solicitacaoDTO' => $solicitacaoDTO,
        ]);
        $documentoAvulsoEntity = $this->documentoAvulsoResource->create(
            (new DocumentoAvulsoDTO())
                ->setDataHoraInicioPrazo(new DateTime())
                ->setDataHoraFinalPrazo((new DateTime())->add(new DateInterval('P5D')))
                ->setDataHoraRemessa(new DateTime())
                ->setUsuarioRemessa($this->tokenStorage->getToken()?->getUser())
                ->setProcesso($solicitacaoEntity->getProcesso())
                ->setSetorOrigem($solicitacaoDTO->getTarefaDadosCumprimento()->getSetorResponsavel())
                ->setModelo($modeloEntity)
                ->setTarefaOrigem($solicitacaoDTO->getTarefaDadosCumprimento())
                ->setPessoaDestino($pessoaDestino)
                ->setEspecieDocumentoAvulso($especieDocumentoAvulsoEntity)
                ->setMecanismoRemessa('externa'),
            $transactionId
        );

        $juntadaDTO = (new Juntada())
            ->setDocumento($documentoAvulsoEntity->getDocumentoRemessa())
            ->setDocumentoAvulso($documentoAvulsoEntity)
            ->setDescricao(
                $documentoAvulsoEntity->getEspecieDocumentoAvulso()->getNome().
                ' - REMESSA DE COMUNICAÇÃO (UUID '.$documentoAvulsoEntity->getUuid().')'
            )
            ->setVolume($this->volumeRepository->findVolumeAbertoByProcessoId(
                $documentoAvulsoEntity->getProcesso()->getId())
            );

        $this->juntadaResource->create($juntadaDTO, $transactionId);

        // fecha a tarefa de cumprimento
        /** @var TarefaDTO $tarefaCumprimentoDTO */
        $tarefaCumprimentoDTO = $this->tarefaResource->getDtoForEntity(
            $solicitacaoDTO->getTarefaDadosCumprimento()->getId(),
            TarefaDTO::class
        );
        $tarefaCumprimentoDTO->setUsuarioConclusaoPrazo($tarefaCumprimentoDTO->getUsuarioResponsavel());
        $tarefaCumprimentoDTO->setDataHoraConclusaoPrazo(new DateTime());
        $tarefaCumprimentoDTO->setFolder(null);
        $this->tarefaResource->update(
            $tarefaCumprimentoDTO->getId(),
            $tarefaCumprimentoDTO,
            $transactionId
        );
        // cria o formulario
        $formularioEntity = $this->formularioResource->findOneBy([
            'sigla' => $salarioMaternidadeRuralDriver::SIGLA_FORMULARIO_SOLICITACAO_AUTOMATIZADA,
        ]);
        $dadosFormularioDTO = (new DadosFormularioDTO())
            ->setFormulario($formularioEntity)
            ->setDocumento($documentoAvulsoEntity->getDocumentoRemessa())
            ->setComponenteDigital($documentoAvulsoEntity->getDocumentoRemessa()->getComponentesDigitais()[0])
            ->setDataValue($solicitacaoDTO->getDadosCumprimento());
        $this->dadosFormularioResource->create(
            $dadosFormularioDTO,
            $transactionId
        );
        $dadosTipoSolicitacao->setDeteminadoCumprimentoSentenca(true);
        $solicitacaoDTO
            ->setDadosTipoSolicitacao(
                $salarioMaternidadeRuralDriver->serializeDadosTipoSolicitacaoSalarioMaternidadeRural(
                    $dadosTipoSolicitacao
                )
            );
    }

    /**
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver
     * @param string                        $transactionId
     * @param array                         $configSalarioMaternidadeRural
     *
     * @return void
     */
    protected function criaTarefaAcompanhamentoPrazoCumprimento(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        string $transactionId,
        array $configSalarioMaternidadeRural
    ): void {
        $config = $salarioMaternidadeRuralDriver->getConfigModuloSolicitacaoAutomatizada();
        $setorEntity = $this->setorResource->findOne(
            (int) $configSalarioMaternidadeRural['setor_tarefa_acompanhamento_cumprimento']
        );
        $especieTarefaEntity = $this->especieTarefaResource->findOneBy([
            'nome' => $config['especie_tarefa_acompanhamento_cumprimento'],
        ]);
        $tarefaEntity = $salarioMaternidadeRuralDriver->createTarefa(
            (new TarefaDTO())
                ->setProcesso($solicitacaoEntity->getProcesso())
                ->setDataHoraInicioPrazo(new DateTime())
                ->setDataHoraFinalPrazo(
                    (new DateTime(
                        sprintf(
                            '+%d days',
                            $configSalarioMaternidadeRural['dias_final_prazo_tarefa_acompanhamento_cumprimento']
                        )
                    ))->setTime(12,0)
                )
                ->setSetorResponsavel($setorEntity)
                ->setEspecieTarefa($especieTarefaEntity),
            $transactionId
        );
        $solicitacaoDTO->setTarefaAcompanhamentoCumprimento($tarefaEntity);
    }

    /**
     * @param SolicitacaoAutomatizadaEntity $solicitacaoEntity
     * @param SolicitacaoAutomatizadaDTO    $solicitacaoDTO
     * @param SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver
     * @param string                        $transactionId
     *
     * @return void
     */
    protected function processaRespostaOficioCumprimento(
        SolicitacaoAutomatizadaEntity $solicitacaoEntity,
        SolicitacaoAutomatizadaDTO $solicitacaoDTO,
        SalarioMaternidadeRuralDriver $salarioMaternidadeRuralDriver,
        string $transactionId
    ): void {
        if ($solicitacaoDTO->getTarefaAcompanhamentoCumprimento() &&
            !$solicitacaoDTO->getTarefaAcompanhamentoCumprimento()->getDataHoraConclusaoPrazo()) {
            // fecha a tarefa de cumprimento
            /** @var TarefaDTO $tarefaAcompanhamentoCumprimentoDTO */
            $tarefaAcompanhamentoCumprimentoDTO = $this->tarefaResource->getDtoForEntity(
                $solicitacaoDTO->getTarefaAcompanhamentoCumprimento()->getId(),
                TarefaDTO::class
            );
            $tarefaAcompanhamentoCumprimentoDTO
                ->setUsuarioConclusaoPrazo($tarefaAcompanhamentoCumprimentoDTO->getUsuarioResponsavel())
                ->setDataHoraConclusaoPrazo(new DateTime())
                ->setFolder(null);
            $this->tarefaResource->update(
                $tarefaAcompanhamentoCumprimentoDTO->getId(),
                $tarefaAcompanhamentoCumprimentoDTO,
                $transactionId
            );
        }
        // @todo fazer verificação da resposta para alterar o status para:
        // @todo SOLICITAÇÃO ATENDIDA / SOLICITAÇÃO NÃO ATENDIDA
    }
}
