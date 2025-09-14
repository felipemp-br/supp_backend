<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado as InteressadoDto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados as OrigemDadosDto;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\InteressadoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeInteressadoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource as OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Barramento\Traits\BarramentoUtil;
use SuppCore\AdministrativoBackend\Entity\Interessado;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Repository\InteressadoRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeInteressadoRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Classe responsável por sincronizar objetos relacionados ao interessado.
 */
class BarramentoInteressadoManager
{
    use BarramentoUtil;

    /**
     * Serviço de manuseio de logs.
     */
    private BarramentoLogger $logger;

    /**
     * Mapeamento das modalidades do interessado.
     */
    private array $mapModalidadeInteressado;

    /**
     * Mapeamento interessado Resource.
     */
    private InteressadoResource $interessadoResource;

    /**
     * Mapeamento interessado Resource.
     */
    private InteressadoRepository $interessadoRepository;

    private ModalidadeInteressadoRepository $modalidadeInteressadoRepository;

    /**
     * Utilizado para alocar a processo dos interessados durante a sincronização.
     */
    private Processo $processo;

    private string $transactionId;

    /**
     * Service utilizada para criar e sincronizar pessoas.
     */
    private BarramentoPessoaManager $pessoaManager;

    private OrigemDadosDto $origemDadosDto;

    private OrigemDadosResource $origemDadosResource;

    private PessoaResource $pessoaResource;

    protected array $config;

    private PessoaDTO $pessoaDTO;

    private ModalidadeInteressadoResource $modalidadeInteressadoResource;

    /**
     * BarramentoInteressadoManager constructor.
     */
    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        BarramentoPessoaManager $pessoaManager,
        InteressadoResource $interessadoResource,
        InteressadoRepository $interessadoRepository,
        PessoaResource $pessoaResource,
        OrigemDadosResource $origemDadosResource,
        ModalidadeInteressadoResource $modalidadeInteressadoResource
    ) {
        $this->logger = $logger;
        $this->config = $parameterBag->get('integracao_barramento');
        $this->mapModalidadeInteressado =
            $this->config['mapeamentos']['modalidade_interessado'];
        $this->pessoaManager = $pessoaManager;
        $this->interessadoResource = $interessadoResource;
        $this->interessadoRepository = $interessadoRepository;
        $this->pessoaResource = $pessoaResource;
        $this->origemDadosResource = $origemDadosResource;
        $this->modalidadeInteressadoResource = $modalidadeInteressadoResource;
    }

    /** Sincroniza interessados a partir de metadados de um processo recebido pelo barramento.
     *
     * @param $metadadosProcesso
     * @param Processo $processo
     * @param string $transactionId
     * @param string $nre
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function sincronizaInteressados(
        $metadadosProcesso,
        Processo $processo,
        string $transactionId,
        string $nre
    ): void {
        $this->processo = $processo;
        $this->transactionId = $transactionId;

        if (!is_array($metadadosProcesso->interessado)) {
            $metadadosProcesso->interessado = [$metadadosProcesso->interessado];
        }

        /** @var Interessado[] $interessadosSupp */
        $interessadosSupp = [];
        if ($processo->getId()) {
            $interessadosSupp = $processo->getInteressados()->toArray();
        }

        $this->acrescentaInteressados($metadadosProcesso, $interessadosSupp, $nre);
        $this->removeInteressados($metadadosProcesso, $interessadosSupp);
    }

    /**
     * Acrescenta interessado e objetos associados.
     *
     * @param $metadadosProcesso
     * @param Interessado[] $interessadosSupp
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    private function acrescentaInteressados($metadadosProcesso, array $interessadosSupp, string $nre): void
    {
        foreach ($metadadosProcesso->interessado as $interessadoTramitacao) {
            $existe = false;
            $pessoa = null;

            foreach ($interessadosSupp as $interessadoSupp) {
                $nomeSupp = $this->upperUtf8($interessadoSupp->getPessoa()->getNome());

                if ($this->upperUtf8($interessadoTramitacao->nome) == $nomeSupp) {
                    $existe = true;
                    $pessoa = $interessadoSupp->getPessoa();
                }
            }

            $pessoa = $this->pessoaManager
                ->sincronizaPessoa($interessadoTramitacao, $pessoa, $this->transactionId, $nre);

            // Ocorreu algum erro durante a sincronização da pessoa
            if (!$pessoa) {
                continue;
            }

            if (!$existe) {
                if (!isset($interessadoTramitacao->polo)) {
                    $interessadoTramitacao->polo = 'ativo';
                }

                if (!$this->getValorMapeado($this->mapModalidadeInteressado, $interessadoTramitacao->polo)) {
                    $this->logger->critical('Modalidade de Interessado não '.
                        'encontrada: '.$interessadoTramitacao->polo);
                    continue;
                }

                $this->criaInteressado($interessadoTramitacao, $pessoa, $this->processo, $nre);
            }
        }
    }

    /**
     * Remove interessados que não estão mais na processo.
     *
     * @param $metadadosProcesso
     * @param $interessadosSupp
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function removeInteressados($metadadosProcesso, array $interessadosSupp): void
    {
        foreach ($interessadosSupp as $interessadoSupp) {
            $existe = false;

            foreach ($metadadosProcesso->interessado as $interessadoTramitacao) {
                if ($this->upperUtf8($interessadoTramitacao->nome) ==
                    $this->upperUtf8($interessadoSupp->getPessoa()->getNome())) {
                    $existe = true;
                }
            }

            if (!$existe) {
                $this->interessadoResource->delete($interessadoSupp->getId(), $this->transactionId);
            }
        }
    }

    /**
     * Cria interessado para a processo.
     *
     * @param $interessadoTramitacao
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function criaInteressado(
        $interessadoTramitacao,
        Pessoa $pessoaEntity,
        Processo $processo,
        string $nre
    ): void {
        $interessado = new InteressadoDto();

        $interessado->setPessoa($pessoaEntity);

        $modalidadeInteressado = $this->modalidadeInteressadoResource->findOneBy([
            'valor' => $this->mapModalidadeInteressado[$interessadoTramitacao->polo],
        ]);

        $interessado->setModalidadeInteressado($modalidadeInteressado);
        $interessado->setProcesso($processo);

        // Homologar com área de Negócio sobre este Factory
        $origemDados = $this->origemDadosFactory();
        $origemDados->setIdExterno($nre);
        $origemDados->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);
        $origemDados = $this->origemDadosResource->create($origemDados, $this->transactionId);

        $interessado->setOrigemDados($origemDados);

        $this->interessadoResource->create($interessado, $this->transactionId);
    }
}
