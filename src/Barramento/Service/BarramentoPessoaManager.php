<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use ReflectionException;
use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Nome as NomeDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeGeneroPessoaResource as ModalidadeGeneroPessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeQualificacaoPessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\MunicipioResource as MunicipioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NomeResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource as OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PaisResource as PaisResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource as PessoaResource;
use SuppCore\AdministrativoBackend\Barramento\Traits\BarramentoUtil;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Repository\NomeRepository as NomeRepository;
use SuppCore\AdministrativoBackend\Repository\PessoaRepository as PessoaRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Classe responsável por sincronizar objetos relacionados a pessoa.
 */
class BarramentoPessoaManager
{
    use BarramentoUtil;

    /**
     * Serviço de manuseio de logs.
     */
    private BarramentoLogger $logger;

    private OrigemDadosResource $origemDadosResource;

    /**
     * Mapeamento da modalidade de qualificação da pessoa.
     */
    private array $mapModalidadeQualificacaoPessoa;

    /**
     * Mapeamento da modalidade do genero da pessoa.
     */
    private array $mapModalidadeGeneroPessoa;

    /**
     * Array de cadastro identificadores tratados durante a sincronização.
     */
    private array $cadastrosIdentificadores = [];

    /**
     * Service utilizada para criar e sincronizar endereços.
     */
    private BarramentoEnderecoManager $enderecoManager;

    /**
     * Service utilizada para criar e sincronizar endereços.
     */
    private BarramentoDocumentoIdentificadorManager $documentoIdentificadorManager;

    private string $transactionId;

    /**
     * Service utiliza para criar e sincronizar outros nomes da pessoa.
     */
    private BarramentoOutroNomeManager $outroNomeManager;

    private PessoaRepository $pessoaRepository;

    private NomeRepository $nomeRepository;

    private ModalidadeQualificacaoPessoaResource $modalidadeQualificacaoPessoaResource;

    private ModalidadeGeneroPessoaResource $modalidadeGeneroPessoaResource;

    private MunicipioResource $municipioResource;

    private PaisResource $paisResource;

    private PessoaResource $pessoaResource;

    private NomeResource $nomeResource;

    /**
     * BarramentoPessoaManager constructor.
     */
    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        BarramentoEnderecoManager $enderecoManager,
        BarramentoDocumentoIdentificadorManager $documentoIdentificadorManager,
        BarramentoOutroNomeManager $outroNomeManager,
        PessoaResource $pessoaResource,
        PessoaRepository $pessoaRepository,
        NomeRepository $nomeRepository,
        OrigemDadosResource $origemDadosResource,
        MunicipioResource $municipioResource,
        NomeResource $nomeResource,
        ModalidadeQualificacaoPessoaResource $modalidadeQualificacaoPessoaResource,
        ModalidadeGeneroPessoaResource $modalidadeGeneroPessoaResource
    ) {
        $this->logger = $logger;
        $this->mapModalidadeQualificacaoPessoa =
            $parameterBag->get('integracao_barramento')['mapeamentos']['modalidade_qualificacao_pessoa'];
        $this->mapModalidadeGeneroPessoa =
            $parameterBag->get('integracao_barramento')['mapeamentos']['modalidade_genero_pessoa'];
        $this->enderecoManager = $enderecoManager;
        $this->documentoIdentificadorManager = $documentoIdentificadorManager;
        $this->outroNomeManager = $outroNomeManager;
        $this->pessoaResource = $pessoaResource;
        $this->pessoaRepository = $pessoaRepository;
        $this->nomeRepository = $nomeRepository;
        $this->origemDadosResource = $origemDadosResource;
        $this->municipioResource = $municipioResource;
        $this->nomeResource = $nomeResource;
        $this->modalidadeQualificacaoPessoaResource = $modalidadeQualificacaoPessoaResource;
        $this->modalidadeGeneroPessoaResource = $modalidadeGeneroPessoaResource;
    }

    /** Sincroniza todos os objetos relacionados ao objeto Pessoa.
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function sincronizaPessoa(
        stdClass $interessadoTramitacao,
        ?Pessoa $pessoaEntity,
        string $transactionId,
        string $nre
    ): ?Pessoa {
        $this->transactionId = $transactionId;

        if ($pessoaEntity) {
            return $pessoaEntity;
        } else {
            $pessoaDTO = new PessoaDTO();
            if ($this->isCpfOuCnpj($this->getValueIfExist($interessadoTramitacao, 'numeroDeIdentificacao'))) {
                $pessoaEntity = $this->pessoaResource
                    ->findOneBy(['numeroDocumentoPrincipal' => $interessadoTramitacao->numeroDeIdentificacao]);
                if ($pessoaEntity) {
                    if ($pessoaEntity->getPessoaValidada()) {
                        return $pessoaEntity;
                    }
                    if ($this->upperUtf8($interessadoTramitacao->nome) != $pessoaEntity->getNome()) {
                        $outroNome = $this->nomeRepository
                            ->findByValor(
                                $this->upperUtf8($interessadoTramitacao->nome),
                                $pessoaEntity->getId()
                            );
                        if (!$outroNome && !empty($interessadoTramitacao->nome)
                            && strlen($interessadoTramitacao->nome) > 3) {
                            $outroNomeDTO = new NomeDTO();
                            $outroNomeDTO->setValor($this->upperUtf8($interessadoTramitacao->nome));

                            $origemDadosDTO = $this->origemDadosFactory();
                            $origemDadosDTO->setIdExterno($nre);
                            $origemDadosDTO->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);
                            $origemDadosEntity = $this->origemDadosResource
                                ->create($origemDadosDTO, $this->transactionId);

                            $outroNomeDTO->setOrigemDados($origemDadosEntity);
                            $outroNomeDTO->setPessoa($pessoaEntity);

                            $this->nomeResource->create(
                                $outroNomeDTO,
                                $transactionId
                            );
                        }
                    }
                } else {
                    if (isset($interessadoTramitacao->numeroDeIdentificacao)) {
                        $pessoaDTO->setNumeroDocumentoPrincipal($interessadoTramitacao->numeroDeIdentificacao);
                    }
                    $pessoaDTO->setNome($interessadoTramitacao->nome);
                }
            } else {
                if (isset($interessadoTramitacao->numeroDeIdentificacao)) {
                    $pessoaDTO->setNumeroDocumentoPrincipal($interessadoTramitacao->numeroDeIdentificacao);
                }
                $pessoaDTO->setNome($interessadoTramitacao->nome);
            }
        }

        $pessoaDTO = $this->bindPessoa($interessadoTramitacao, $pessoaDTO);

        $origemDadosDTO = $this->origemDadosFactory();
        $origemDadosDTO->setIdExterno($nre);
        $origemDadosDTO->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);
        $origemDadosEntity = $this->origemDadosResource
            ->create($origemDadosDTO, $this->transactionId);

        $pessoaDTO->setOrigemDados($origemDadosEntity);

        if ($pessoaEntity) {
            $pessoaEntity = $this->pessoaResource->update(
                $pessoaEntity->getId(),
                $pessoaDTO,
                $transactionId
            );
        } else {
            $pessoaEntity = $this->pessoaResource->create(
                $pessoaDTO,
                $transactionId
            );
        }

        $this->documentoIdentificadorManager
            ->sincronizaDocumentoIdentificador($interessadoTramitacao, $pessoaEntity, $nre, $this->transactionId);

        $this->outroNomeManager
            ->sincronizaOutroNome($interessadoTramitacao, $pessoaEntity, $this->transactionId);

        $this->enderecoManager
            ->sincronizaEndereco($interessadoTramitacao, $pessoaEntity, $this->transactionId);

        return $pessoaEntity;
    }

    /**
     * Seta atributos do objeto Pessoa.
     *
     * @return bool|PessoaDTO
     *
     * @throws Exception
     */
    private function bindPessoa(stdClass $interessadoTramitacao, PessoaDTO $pessoaDTO)
    {
        if (!isset($interessadoTramitacao->tipo)) {
            $interessadoTramitacao->tipo = 'fisica';
        }

        if (!isset($interessadoTramitacao->sexo)) {
            $interessadoTramitacao->sexo = 'D';
        }

        if (!$this->getValorMapeado($this->mapModalidadeQualificacaoPessoa, $interessadoTramitacao->tipo)) {
            $this->logger
                ->critical('Modalidade de Qualificação Pessoa não encontrada: '.$interessadoTramitacao->tipo);

            return false;
        }

        if (!$this->getValorMapeado($this->mapModalidadeGeneroPessoa, $interessadoTramitacao->sexo)) {
            $this->logger->critical('Modalidade de Gênero Pessoa não encontrada: '.$interessadoTramitacao->sexo);

            return false;
        }

        $modalidadeQualificacaoPessoa = $this->modalidadeQualificacaoPessoaResource
            ->findOneBy(['valor' => $this->mapModalidadeQualificacaoPessoa[$interessadoTramitacao->tipo]]);
        $pessoaDTO->setModalidadeQualificacaoPessoa($modalidadeQualificacaoPessoa);

        $modalidadeQualidadeGeneroPessoa = $this->modalidadeGeneroPessoaResource
            ->findOneBy(['valor' => $this->mapModalidadeGeneroPessoa[$interessadoTramitacao->sexo]]);
        $pessoaDTO->setModalidadeGeneroPessoa($modalidadeQualidadeGeneroPessoa);

        $dataDeNascimento = $this->getValueIfExist($interessadoTramitacao, 'dataDeNascimento');
        $dataDeNascimento ?? $pessoaDTO->setDataNascimento($this->converteDataBarramento($dataDeNascimento));

        $dataDeObito = $this->getValueIfExist($interessadoTramitacao, 'dataDeObito');
        $dataDeObito ?? $pessoaDTO->setDataObito($this->converteDataBarramento($dataDeObito));

        $nomePai = $this->getValueIfExist($interessadoTramitacao, 'nomeDoGenitor');
        $nomePai ?? $pessoaDTO->setNomeGenitor($this->upperUtf8($nomePai));
        $nomeMae = $this->getValueIfExist($interessadoTramitacao, 'nomeDaGenitora');
        $nomeMae ?? $pessoaDTO->setNomeGenitora($this->upperUtf8($nomeMae));

        $pessoaDTO = $this->bindNaturalidade($interessadoTramitacao, $pessoaDTO);
        $pessoaDTO = $this->bindNacionalidade($interessadoTramitacao, $pessoaDTO);

        return $pessoaDTO;
    }

    /**
     * Verifica se pessoa é pessoa protegida dentro das regras do Supp.
     */
    private function isPessoaProtegida(Pessoa $pessoa): bool
    {
        if ($pessoa && $pessoa->getPessoaValidada()) {
            return true;
        }

        return false;
    }

    /**
     * Informa se valor informado possui tamanho de CPF ou CNPJ.
     */
    private function isCpfOuCnpj(?string $numeroDeIdentificacao): bool
    {
        if (isset($numeroDeIdentificacao) &&
            (11 == mb_strlen($numeroDeIdentificacao, 'UTF-8')
                || 14 == mb_strlen($numeroDeIdentificacao, 'UTF-8'))
        ) {
            return true;
        }

        return false;
    }

    /**
     * Seta atributos município, estado e país natural no objeto Pessoa.
     */
    private function bindNaturalidade(stdClass $interessadoTramitacao, PessoaDTO $pessoa): PessoaDTO
    {
        $municipio = $this->getValueIfExist($interessadoTramitacao, 'cidadeNatural');

        if ($municipio) {
            $municipio = $this->municipioResource->find(['nome' => $municipio]);

            if ($municipio && 1 == count($municipio)) {
                $pessoa->setNaturalidade($municipio[0]);
                $pessoa->setNacionalidade($municipio[0]->getEstado()->getPais());
            }

            if ($municipio && (count($municipio) > 1) && isset($interessadoTramitacao->estadoNatural)) {
                foreach ($municipio as $m) {
                    if ($m->getEstado()->getUf() == $this->upperUtf8($interessadoTramitacao->estadoNatural)) {
                        $pessoa->setNaturalidade($m);
                        $pessoa->setNacionalidade($m->getEstado()->getPais());
                        break;
                    }
                }
            }
        }

        return $pessoa;
    }

    /**
     * Seta atributo nacionalidade ao objeto Pessoa.
     */
    private function bindNacionalidade(stdClass $interessadoTramitacao, PessoaDTO $pessoa): PessoaDTO
    {
        $pais = $this->getValueIfExist($interessadoTramitacao, 'nacionalidade');
        if ($pais && 'BR' != $this->upperUtf8($pais)) {
            $pais = $this->paisResource->findOneBy(['codigo' => $this->upperUtf8($pais)]);

            if ($pais) {
                $pessoa->setNacionalidade($pais);
            } else {
                $this->logger->
                critical('Nacionalidade deixou de ser cadastrada: '.$this->upperUtf8((string) $pais));
            }
        }

        return $pessoa;
    }
}
