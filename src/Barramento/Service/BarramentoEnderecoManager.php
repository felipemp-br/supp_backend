<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Endereco as EnderecoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EnderecoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\MunicipioResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PaisResource;
use SuppCore\AdministrativoBackend\Entity\Endereco;
use SuppCore\AdministrativoBackend\Entity\Municipio;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Repository\EnderecoRepository as EnderecoRepository;
use SuppCore\AdministrativoBackend\Barramento\Traits\BarramentoUtil;

/**
 * Classe responsável por sincronizar objetos relacionados ao endereço.
 */
class BarramentoEnderecoManager
{
    use BarramentoUtil;

    /**
     * Serviço de manuseio de logs.
     */
    private BarramentoLogger $logger;

    /**
     * Serviço de manuseio de logs.
     */
    private string $transactionId;

    private EnderecoResource $enderecoResource;

    private EnderecoRepository $enderecoRepository;

    private MunicipioResource $municipioResource;

    private PaisResource $paisResource;

    private OrigemDadosResource $origemDadosResource;

    /**
     * @param BarramentoLogger $logger
     * @param EnderecoResource $enderecoResource
     * @param MunicipioResource $municipioResource
     * @param PaisResource $paisResource
     * @param EnderecoRepository $enderecoRepository
     * @param OrigemDadosResource $origemDadosResource
     */
    public function __construct(
        BarramentoLogger $logger,
        EnderecoResource $enderecoResource,
        MunicipioResource $municipioResource,
        PaisResource $paisResource,
        EnderecoRepository $enderecoRepository,
        OrigemDadosResource $origemDadosResource
    ) {
        $this->logger = $logger;
        $this->enderecoResource = $enderecoResource;
        $this->municipioResource = $municipioResource;
        $this->paisResource = $paisResource;
        $this->enderecoRepository = $enderecoRepository;
        $this->origemDadosResource = $origemDadosResource;
    }

    /**
     * Sincroniza endereços da pessoa.
     *
     * @param $interessadoTramitacao
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function sincronizaEndereco($interessadoTramitacao, Pessoa $pessoa, string $transactionId): Pessoa
    {
        $this->transactionId = $transactionId;
        if (isset($interessadoTramitacao->endereco)) {
            if (!is_array($interessadoTramitacao->endereco)) {
                $interessadoTramitacao->endereco = [$interessadoTramitacao->endereco];
            }

            $enderecosSupp = $this->enderecoRepository->findByPessoaAndFonteDados(
                $pessoa->getId(),
                $this->fonteDeDados
            );

            $pessoa = $this->acrescentaEndereco($interessadoTramitacao, $enderecosSupp, $pessoa, $transactionId);
            $pessoa = $this->removeEndereco($interessadoTramitacao, $enderecosSupp, $pessoa);
        }

        return $pessoa;
    }

    /** Acrescenta endereço ao objeto pessoa.
     * @param $interessadoTramitacao
     * @param $enderecosSupp
     * @param Pessoa $pessoa
     * @return Pessoa
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function acrescentaEndereco($interessadoTramitacao, $enderecosSupp, Pessoa $pessoa, $transactionId): Pessoa
    {
        foreach ($interessadoTramitacao->endereco as $enderecoTramitacao) {
            $existe = false;

            if (!isset($enderecoTramitacao->cep) ||
                (8 != mb_strlen($enderecoTramitacao->cep, 'UTF-8'))) {
                $enderecoTramitacao->cep = null;
            }

            foreach ($enderecosSupp as $enderecoSupp) {
                if ($this->comparaEndereco($enderecoTramitacao, $enderecoSupp)) {
                    $existe = true;
                }
            }

            if (false == $existe) {
                $enderecoNovoDTO = new EnderecoDTO();

                $enderecoNovoDTO->setLogradouro(
                    $this->upperUtf8($this->getValueIfExist($enderecoTramitacao, 'logradouro'))
                );
                $enderecoNovoDTO->setNumero($this->getValueIfExist($enderecoTramitacao, 'numero'));
                $enderecoNovoDTO->setComplemento(
                    $this->upperUtf8($this->getValueIfExist($enderecoTramitacao, 'complemento'))
                );
                $enderecoNovoDTO->setBairro($this->upperUtf8($this->getValueIfExist($enderecoTramitacao, 'bairro')));
                $enderecoNovoDTO->setCep($this->getValueIfExist($enderecoTramitacao, 'cep'));

                if (count($pessoa->getEnderecos()) > 0) {
                    $enderecoNovoDTO->setPrincipal(false);
                }

                $this->bindMunicipio($enderecoTramitacao, $enderecoNovoDTO);
                $this->bindPais($enderecoTramitacao, $enderecoNovoDTO);

                $origemDadosDTO = $this->origemDadosFactory();
                $origemDadosDTO->setIdExterno('BARRAMENTO_PEN');
                $origemDadosEntity = $this->origemDadosResource->create($origemDadosDTO, $transactionId);

                $enderecoNovoDTO->setOrigemDados($origemDadosEntity);
                $enderecoNovoDTO->getOrigemDados()->setStatus(AbstractBarramentoManager::SINCRONIZACAO_SUCESSO);

                $enderecoNovoDTO->setPessoa($pessoa);

                //DTO Cria um novo endereço para objeto Pessoa.
                $this->enderecoResource->create($enderecoNovoDTO, $this->transactionId);
            }
        }

        return $pessoa;
    }

    /**
     * @param $interessadoTramitacao
     * @param $enderecosSupp
     * @param $pessoa
     *
     * @return mixed
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    private function removeEndereco($interessadoTramitacao, $enderecosSupp, $pessoa): mixed
    {
        foreach ($enderecosSupp as $enderecoSupp) {
            $existe = false;

            foreach ($interessadoTramitacao->endereco as $endereco) {
                if ($this->comparaEndereco($endereco, $enderecoSupp)) {
                    $existe = true;
                }
            }

            if (false == $existe) {
                if ($enderecoSupp->getPrincipal()) {
                    foreach ($enderecoSupp->getPessoa()->getEnderecos() as $outroEndereco) {
                        if ($outroEndereco->getId() != $enderecoSupp->getId()) {
                            //Criar DTO
                            $otherEnderecoDTO = $this->enderecoResource->getDtoForEntity(
                                $outroEndereco->getId(),
                                EnderecoDTO::class
                            );
                            $otherEnderecoDTO->setPrincipal(true);

                            $this->enderecoResource->update(
                                $outroEndereco->getId(),
                                $otherEnderecoDTO,
                                $this->transactionId
                            );

                            break;
                        }
                    }
                }

                //Criar DTO
                $this->enderecoResource->delete($enderecoSupp->getId(), $this->transactionId);
            }
        }

        return $pessoa;
    }

    /**
     * Compara documento endereço do barramento com o do Supp.
     *
     * @param $enderecoBarramento
     * @param Endereco $enderecoSupp
     * @return bool
     */
    private function comparaEndereco($enderecoBarramento, Endereco $enderecoSupp): bool
    {
        if (($enderecoSupp->getMunicipio() &&
                $this->upperUtf8($enderecoBarramento->cidade) == $enderecoSupp->getMunicipio()->getNome()) &&
            $this->upperUtf8($enderecoBarramento->logradouro) == $enderecoSupp->getLogradouro() &&
            $this->upperUtf8($enderecoBarramento->numero) == $enderecoSupp->getNumero() &&
            $this->upperUtf8($enderecoBarramento->complemento) == $enderecoSupp->getComplemento() &&
            $this->upperUtf8($enderecoBarramento->bairro) == $enderecoSupp->getBairro() &&
            $this->upperUtf8($enderecoBarramento->cep) == $enderecoSupp->getCep()
        ) {
            return true;
        }

        return false;
    }

    /** Seta atributo município ao objeto endereço.
     * @param $enderecoTramitacao
     * @param EnderecoDTO $endereco
     */
    private function bindMunicipio($enderecoTramitacao, EnderecoDTO $endereco): void
    {
        $cadastrado = false;

        if (isset($enderecoTramitacao->cidade)) {
            $enderecoTramitacao->cidade = $this->upperUtf8($enderecoTramitacao->cidade);

            $municipios = $this->municipioResource->find(['nome' => $enderecoTramitacao->cidade]);

            if ($municipios && 1 == count($municipios)) {
                $endereco->setMunicipio($municipios[0]);
                $endereco->setPais($municipios[0]->getEstado()->getPais());
                $cadastrado = true;
            }

            if ($this->hasMunicipioEstado($enderecoTramitacao, $municipios)) {
                foreach ($municipios as $municipio) {
                    if ($municipio->getEstado()->getUf() == $this->upperUtf8($enderecoTramitacao->estado)) {
                        $endereco->setMunicipio($municipio);
                        $endereco->setPais($municipio->getEstado()->getPais());
                        $cadastrado = true;
                        continue;
                    }
                }
            }

            if (!$cadastrado) {
                $this->logger->critical('Município deixou de ser cadastrado: '.$enderecoTramitacao->cidade);
            }
        }
    }

    /**
     * Seta atributo país ao endereço.
     *
     * @param $enderecoTramitacao
     * @param EnderecoDTO $endereco
     */
    private function bindPais($enderecoTramitacao, EnderecoDTO $endereco): void
    {
        if (isset($enderecoTramitacao->pais) && 'BR' != mb_strtoupper($enderecoTramitacao->pais, 'UTF-8')) {
            // é um estrangeiro, vamos marcar a pais
            $pais = $this->paisResource->findOneBy(['codigo' => $this->upperUtf8($enderecoTramitacao->pais)]);

            if ($pais) {
                $endereco->setPais($pais);
            } else {
                $this->logger->critical('País deixou de ser cadastrado: '.$enderecoTramitacao->pais);
            }
        }
    }

    /**
     * Verifica se foram encontrados municípios e se o estado vindo do barramento foi informado.
     *
     * @param $enderecoTramitacao
     * @param array|Municipio $municipios
     * @return bool
     */
    private function hasMunicipioEstado($enderecoTramitacao, array|Municipio $municipios): bool
    {
        if ($municipios && count($municipios) > 1 && isset($enderecoTramitacao->estado)) {
            return true;
        }

        return false;
    }
}
