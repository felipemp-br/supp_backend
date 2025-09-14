<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Desentranhamento/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Desentranhamento;

use DateInterval;
use DateTime;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assunto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Desentranhamento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Representante;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AssuntoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieTarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\InteressadoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\RepresentanteResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TarefaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VolumeResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\EspecieProcessoRepository;
use SuppCore\AdministrativoBackend\Repository\LotacaoRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeMeioRepository;
use SuppCore\AdministrativoBackend\Repository\SetorRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Se o tipo for novo_processo ou arquivo, primeiro realiza o protocolo, e após faz a nova juntada em todos os casos!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private ModalidadeMeioRepository $modalidadeMeioRepository;

    private LotacaoRepository $lotacaoRepository;

    private EspecieProcessoRepository $especieProcessoRepository;

    private SetorRepository $setorRepository;

    private ProcessoResource $processoResource;

    private AssuntoResource $assuntoResource;

    private InteressadoResource $interessadoResource;

    private RepresentanteResource $representanteResource;

    private TarefaResource $tarefaResource;

    private EspecieTarefaResource $especieTarefaResource;

    private JuntadaResource $juntadaResource;

    private TokenStorageInterface $tokenStorage;

    private VolumeResource $volumeResource;


    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        ModalidadeMeioRepository $modalidadeMeioRepository,
        EspecieProcessoRepository $especieProcessoRepository,
        SetorRepository $setorRepository,
        TarefaResource $tarefaResource,
        EspecieTarefaResource $especieTarefaResource,
        ProcessoResource $processoResource,
        AssuntoResource $assuntoResource,
        JuntadaResource $juntadaResource,
        InteressadoResource $interessadoResource,
        RepresentanteResource $representanteResource,
        TokenStorageInterface $tokenStorage,
        VolumeResource $volumeResource,
        LotacaoRepository $lotacaoRepository,
        private ParameterBagInterface $parameterBag
    ) {
        $this->modalidadeMeioRepository = $modalidadeMeioRepository;
        $this->especieProcessoRepository = $especieProcessoRepository;
        $this->setorRepository = $setorRepository;
        $this->processoResource = $processoResource;
        $this->assuntoResource = $assuntoResource;
        $this->interessadoResource = $interessadoResource;
        $this->representanteResource = $representanteResource;
        $this->tarefaResource = $tarefaResource;
        $this->especieTarefaResource = $especieTarefaResource;
        $this->juntadaResource = $juntadaResource;
        $this->tokenStorage = $tokenStorage;
        $this->volumeResource = $volumeResource;
        $this->lotacaoRepository = $lotacaoRepository;
    }

    public function supports(): array
    {
        return [
            Desentranhamento::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @throws ORMException
     * @throws NonUniqueResultException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $dateTime = new DateTime();
        $processoAnterior = $restDto->getJuntada()->getVolume()->getProcesso();

        // nova juntada
        $juntadaDTO = new Juntada();
        $juntadaDTO->setDocumento($restDto->getJuntada()->getDocumento());
        $juntadaDTO->setAtivo(true);

        $responsavel = 'SISTEMA';
        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()) {
            $responsavel = $this->tokenStorage->getToken()->getUser()->getNome();
        }
        $juntadaDTO->setDescricao(
            'JUNTADA POR DESENTRANHAMENTO ORIUNDO DO NUP n. '.
            $processoAnterior->getNUPFormatado().' POR '.
            $responsavel.' EM '.
            $dateTime->format('d/m/Y H:i')
        );

        if ('novo_processo' === $restDto->getTipo() || 'arquivo' === $restDto->getTipo()) {
            $processoDTO = new Processo();
            $processoDTO->setUnidadeArquivistica(ProcessoEntity::UA_DOCUMENTO_AVULSO);
            $processoDTO->setTipoProtocolo(ProcessoEntity::TP_NOVO);
            $processoDTO->setClassificacao(
                $processoAnterior->getClassificacao()
            );
            $processoDTO->setEspecieProcesso(
                $this->especieProcessoRepository
                    ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.especie_processo.const_1')])
            );
            $processoDTO->setModalidadeMeio(
                $this->modalidadeMeioRepository
                    ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_meio.const_1')])
            );
            $processoDTO->setTitulo(
                'DESENTRANHAMENTO ORIGINADO DO NUP '.$processoAnterior->getNUPFormatado()
            );

            if ('novo_processo' === $restDto->getTipo()) {
                $processoDTO->setSetorAtual(
                    $processoAnterior->getSetorAtual()
                );
            } else {
                // arquivar
                $arquivo = $this->setorRepository->findArquivoInUnidade(
                    $processoAnterior->getSetorAtual()->getUnidade()->getId()
                );

                $processoDTO->setSetorAtual($arquivo);
            }

            $processoDestino = $this->processoResource->create($processoDTO, $transactionId);
            $volume = $processoDTO->getVolumes()[0];

            /** @var \SuppCore\AdministrativoBackend\Entity\Assunto $assuntoClonado */
            foreach ($processoAnterior->getAssuntos() as $assuntoClonado) {
                $assuntoDTO = (new Assunto())
                    ->setPrincipal($assuntoClonado->getPrincipal())
                    ->setAssuntoAdministrativo($assuntoClonado->getAssuntoAdministrativo())
                    ->setProcesso($processoDestino);

                $this->assuntoResource->create($assuntoDTO, $transactionId, true);
            }

            if ('arquivo' === $restDto->getTipo()) {
                // tarefa de arquivamento
                $inicioPrazo = new DateTime();
                $finalPrazo = new DateTime();
                $finalPrazo->add(new DateInterval('P5D'));
                $especieTarefa = $this->especieTarefaResource->findOneBy(
                    [
                        'nome' => $this->parameterBag->get('constantes.entidades.especie_tarefa.const_1'),
                    ]
                );

                $tarefaDTO = new Tarefa();
                $tarefaDTO->setProcesso($processoDestino);
                $tarefaDTO->setEspecieTarefa($especieTarefa);
                $tarefaDTO->setSetorResponsavel($processoDTO->getSetorAtual());
                $tarefaDTO->setDataHoraInicioPrazo($inicioPrazo);
                $tarefaDTO->setDataHoraFinalPrazo($finalPrazo);

                $this->tarefaResource->create($tarefaDTO, $transactionId);
            }

            if ($this->tokenStorage->getToken() &&
                $this->tokenStorage->getToken()->getUser() &&
                ('novo_processo' === $restDto->getTipo())) {
                if ($this->tokenStorage->getToken()->getUser()->getColaborador()) {
                    $lotacaoPrincipal = $this->lotacaoRepository->findLotacaoPrincipal(
                        $this->tokenStorage->getToken()->getUser()->getColaborador()->getId()
                    );

                    if ($lotacaoPrincipal) {
                        // tarefa de arquivamento
                        $inicioPrazo = new DateTime();
                        $finalPrazo = new DateTime();
                        $finalPrazo->add(new DateInterval('P5D'));
                        $especieTarefa = $this->especieTarefaResource->findOneBy(
                            [
                                'nome' => $this->parameterBag->get('constantes.entidades.especie_tarefa.const_2'),
                            ]
                        );

                        $tarefaDTO = new Tarefa();
                        $tarefaDTO->setProcesso($processoDestino);
                        $tarefaDTO->setEspecieTarefa($especieTarefa);
                        $tarefaDTO->setSetorResponsavel($lotacaoPrincipal->getSetor());
                        $tarefaDTO->setUsuarioResponsavel($this->tokenStorage->getToken()->getUser());
                        $tarefaDTO->setDataHoraInicioPrazo($inicioPrazo);
                        $tarefaDTO->setDataHoraFinalPrazo($finalPrazo);

                        $this->tarefaResource->create($tarefaDTO, $transactionId);
                    }
                }
            }

            /** @var \SuppCore\AdministrativoBackend\Entity\Assunto $assuntoClonado */
            foreach ($processoAnterior->getAssuntos() as $assuntoClonado) {
                $assuntoDTO = new Assunto();
                $assuntoDTO->setPrincipal($assuntoClonado->getPrincipal());
                $assuntoDTO->setAssuntoAdministrativo($assuntoClonado->getAssuntoAdministrativo());
                $assuntoDTO->setProcesso($processoDestino);

                $this->assuntoResource->create($assuntoDTO, $transactionId);
            }

            /** @var \SuppCore\AdministrativoBackend\Entity\Interessado $interessadoClonado */
            foreach ($processoAnterior->getInteressados() as $interessadoClonado) {
                $interessadoDTO = new Interessado();
                $interessadoDTO->setPessoa($interessadoClonado->getPessoa());
                $interessadoDTO->setModalidadeInteressado($interessadoClonado->getModalidadeInteressado());
                $interessadoDTO->setProcesso($processoDestino);

                $interessado = $this->interessadoResource->create($interessadoDTO, $transactionId);

                /** @var \SuppCore\AdministrativoBackend\Entity\Representante $representanteClonado */
                foreach ($interessadoClonado->getRepresentantes() as $representanteClonado) {
                    $representanteDTO = new Representante();
                    $representanteDTO->setNome($representanteClonado->getNome());
                    $representanteDTO->setInscricao($representanteClonado->getInscricao());
                    $representanteDTO->setModalidadeRepresentante($representanteClonado->getModalidadeRepresentante());
                    $representanteDTO->setInteressado($interessado);

                    $this->representanteResource->create($representanteDTO, $transactionId, true);
                }
            }

            $restDto->setProcessoDestino($processoDestino);
        } else {
            $processoDestino = $restDto->getProcessoDestino();
            $volume = $this->volumeResource->getRepository()->findVolumeAbertoByProcessoId(
                $processoDestino->getId()
            );
        }

        $juntadaDTO->setVolume($volume);

        $juntadaDTO->setJuntadaDesentranhada($restDto->getJuntada());

        $this->juntadaResource->create($juntadaDTO, $transactionId);
    }

    public function getOrder(): int
    {
        return 3;
    }
}
