<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Barramento\Service;

use stdClass;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Entity\Assinatura;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\DocumentoIdentificador;
use SuppCore\AdministrativoBackend\Entity\Endereco;
use SuppCore\AdministrativoBackend\Entity\Interessado;
use SuppCore\AdministrativoBackend\Entity\Municipio;
use SuppCore\AdministrativoBackend\Entity\Nome;
use SuppCore\AdministrativoBackend\Entity\Pessoa;
use SuppCore\AdministrativoBackend\Entity\Processo;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Classe responsável por converter tramitação em processo e enviar ao barramento.
 *
 */
class BarramentoEnvioManager extends AbstractBarramentoManager
{
    private ComponenteDigitalResource $componenteDigitalResource;

    private ProcessoResource $processoResource;

    private OrigemDadosResource $origemDadosResource;

    protected array $config;

    /**
     * BarramentoEnvioManager constructor.
     * @param BarramentoLogger $logger
     * @param ParameterBagInterface $parameterBag
     * @param BarramentoClient $barramentoClient
     * @param ProcessoResource $processoResource
     * @param OrigemDadosResource $origemDadosResource
     * @param ComponenteDigitalResource $componenteDigitalResource
     */
    public function __construct(
        BarramentoLogger $logger,
        ParameterBagInterface $parameterBag,
        BarramentoClient $barramentoClient,
        ProcessoResource $processoResource,
        OrigemDadosResource $origemDadosResource,
        ComponenteDigitalResource $componenteDigitalResource,
    ) {
        $this->logger = $logger;
        $this->config = $parameterBag->get('integracao_barramento');
        $this->mapeamentos = $parameterBag->get('integracao_barramento')['mapeamentos'];
        $this->client = $barramentoClient;
        parent::__construct($logger, $this->config, $barramentoClient, $processoResource, $origemDadosResource);
        $this->componenteDigitalResource = $componenteDigitalResource;
    }

    /**
     * Retorna espécie do documento no formato esperado pelo barramento.
     *
     * @param Documento $documento
     *
     * @return stdClass
     */
    public function getEspecieDocumento(Documento $documento): stdClass
    {
        $indiceMapeamento = null;

        // especie
        if ('OUTROS' == $documento->getTipoDocumento()->getNome()) {
            $nomeTipoDocumento = $documento->getDescricaoOutros() ?
                explode("_", $documento->getDescricaoOutros())[1] :
                $documento->getTipoDocumento()->getNome();
            $indiceMapeamento = $documento->getDescricaoOutros() ?
                 explode("_", $documento->getDescricaoOutros())[0] : null;
        } else {
            $nomeTipoDocumento = $documento->getTipoDocumento()->getNome();
        }

        $mapeamentoTipoDocumento = $this->mapeamentos['tipo_documento_barramento'];
        if (!$indiceMapeamento) {
            $indiceMapeamento = array_search(
                mb_strtoupper($nomeTipoDocumento),
                array_map('mb_strtoupper', $mapeamentoTipoDocumento)
            );
        }

        if ($indiceMapeamento) {
            $especie = new stdClass();
            $especie->codigo = $indiceMapeamento;
            $especie->nomeNoProdutor = $mapeamentoTipoDocumento[$indiceMapeamento];
        } else {
            $especie = new stdClass();
            $especie->codigo = $documento->getDescricaoOutros() ?
                explode("_", $documento->getDescricaoOutros())[0] : 180;
            $especie->nomeNoProdutor = $documento->getDescricaoOutros() ?
                explode("_", $documento->getDescricaoOutros())[1] : 'Documento';
        }

        if(!$especie->codigo){
            $especie->codigo = 180;
        }

        return $especie;
    }

    /**
     * Retorna identificação do documento no formato para envio ao barramento.
     *
     * @param Documento $documento
     *
     * @return mixed
     */
    public function getIdentificacacaoDocumento(Documento $documento)
    {
        /* @var ComponenteDigital $componenteDigital */
        $componenteDigital = $documento->getComponentesDigitais()[0];

        if (!$componenteDigital || !$componenteDigital->getDocumento()->getNumeroUnicoDocumento()) {
            return false;
        }

        $numeroUnicoDocumento = $componenteDigital->getDocumento()->getNumeroUnicoDocumento();

        $identificacao = new stdClass();
        $identificacao->numero = $numeroUnicoDocumento->getSequencia();
        $identificacao->ano = $numeroUnicoDocumento->getAno();
        $identificacao->siglaDaUnidadeProdutora = $numeroUnicoDocumento->getSetor()->getSigla();
        $identificacao->complemento = strlen($documento->getTipoDocumento()?->getDescricao()) > 100 ?
            substr($documento->getTipoDocumento()?->getDescricao(), 0, 100) : $documento->getTipoDocumento()?->getDescricao();

        return $identificacao;
    }

    /**
     * Retorna componentes digitais no formato esperado para envio ao barramento.
     *
     * @param Documento $documento
     *
     * @return array
     */
    public function getComponentesDigitais(Documento $documento, $transactionId = null): ?array
    {
        $componentesRetorno = null;

        $componentesDigitaisSupp = $documento->getComponentesDigitais();

        /* @var ComponenteDigital $componenteDigitalSupp */
        foreach ($componentesDigitaisSupp as $k => $componenteDigitalSupp) {
            $componenteDigitalEnvio = new stdClass();

            $extensao = strtolower(pathinfo($componenteDigitalSupp->getFileName(), PATHINFO_EXTENSION));

            if(!empty($extensao)) {
                $componenteDigitalEnvio->nome = $componenteDigitalSupp->getFileName();
            } else if(!str_contains($componenteDigitalSupp->getExtensao(), '/')) {
                $componenteDigitalEnvio->nome =
                    $componenteDigitalSupp->getFileName() . '.' . $componenteDigitalSupp->getExtensao();
            } else {
                $nomeMimetype = $componenteDigitalSupp->getMimetype();
                $mapExtensoes = $this->mapeamentos['extensoes'];
                $extensao = $mapExtensoes[mb_strtolower($nomeMimetype)];
                $componenteDigitalEnvio->nome =
                    $componenteDigitalSupp->getFileName() . '.' . $extensao;
            }

            $mapeamentoExtensao = [
                'html' => 'txt',
                'htm' => 'txt',
                'pdf' => 'txt',
                'jpg' => 'img',
                'jpeg' => 'img',
                'ogg' => 'aud',
                'mp4' => 'vid',
            ];

            $nomeTipoConteudo = mb_strtolower($componenteDigitalSupp->getExtensao());

            if (isset($mapeamentoExtensao[$nomeTipoConteudo])) {
                $nomeTipoConteudo = $mapeamentoExtensao[$nomeTipoConteudo];
            }

            $mapTipoConteudo = $this->mapeamentos['tipo_conteudo'];
            $indiceTipoConteudo = array_search(
                mb_strtoupper($nomeTipoConteudo),
                array_map('mb_strtoupper', $mapTipoConteudo)
            );

            if ($indiceTipoConteudo) {
                $tipoConteudo = $nomeTipoConteudo;
            } else {
                $tipoConteudo = 'out';
                // $this->logger->critical("Tipo de conteúdo [{$nomeTipoConteudo}] não mapeado.");
            }

            $nomeMimetype = $componenteDigitalSupp->getMimetype();
            $mapMimetype = $this->mapeamentos['mimetype'];
            $indiceMimetype = array_search(
                mb_strtoupper($nomeMimetype),
                array_map('mb_strtoupper', $mapMimetype)
            );

            if ($indiceMimetype && $nomeMimetype !== 'outro') {
                $mimetype = $nomeMimetype;
            } else {
                $mimetype = 'outro';
                $componenteDigitalEnvio->dadosComplementaresDoTipoDeArquivo = 'formato desconhecido';
                // $this->logger->critical("Mimetype [{$nomeMimetype}] não mapeado.");
            }

            $componenteDigitalSupp->setConteudo(
                $this->componenteDigitalResource->download(
                    $componenteDigitalSupp->getId(),
                    $transactionId
                )->getConteudo()
            );

            $componenteDigitalEnvio->tipoDeConteudo = $tipoConteudo;
            $componenteDigitalEnvio->mimeType = $mimetype;
            $componenteDigitalEnvio->tamanhoEmBytes = $documento->getOrigemDados() ?
                $componenteDigitalSupp->getTamanho() : strlen((string) $componenteDigitalSupp->getConteudo());
            $componenteDigitalEnvio->ordem = $componenteDigitalSupp->getNumeracaoSequencial();

            if ($componentesDigitaisSupp->count() > 1 && $k > 0) {
                $componenteDigitalEnvio->ordem =
                    $componenteDigitalSupp->getNumeracaoSequencial() ===
                    $componentesDigitaisSupp[$k-1]->getNumeracaoSequencial() ?
                        $componenteDigitalSupp->getNumeracaoSequencial() + $k :
                        $componenteDigitalSupp->getNumeracaoSequencial();

                if ($componenteDigitalSupp->getHash() === $componentesDigitaisSupp[$k-1]->getHash() &&
                    $componenteDigitalSupp->getFileName() === $componentesDigitaisSupp[$k-1]->getFileName() &&
                    $componenteDigitalSupp->getTamanho() ===  $componentesDigitaisSupp[$k-1]->getTamanho() &&
                    $componenteDigitalSupp->getOrigemDados() &&
                    $componenteDigitalSupp->getOrigemDados()->getFonteDados() === 'BARRAMENTO_PEN') {
                    continue;
                }
            }

            if (!$documento->getOrigemDados()) {
                $hash = new stdClass();
                $hash->algoritmo = 'SHA256';
                $hash->_ = base64_encode(hash('SHA256', (string)$componenteDigitalSupp->getConteudo(), true));
                $componenteDigitalEnvio->hash = $hash;
            } elseif ($documento->getOrigemDados() &&
                $documento->getOrigemDados()->getFonteDados() === 'BARRAMENTO_PEN') {
                $hash = new stdClass();
                $hash->algoritmo = 'SHA256';
                if ($componenteDigitalSupp->getOrigemDados()->getIdExterno() ===
                    $documento->getOrigemDados()->getIdExterno()) {
                    $hash->_ = $componenteDigitalSupp->getHash();
                    $componenteDigitalEnvio->hash = $hash;
                } else {
                    $hash->_ = $componenteDigitalSupp->getOrigemDados()->getIdExterno();
                    $componenteDigitalEnvio->hash = $hash;
                }
            }

            /** @var Assinatura $assinaturaSupp */
            foreach ($componenteDigitalSupp->getAssinaturas() as $assinaturaSupp) {
                $assinaturaDigital = new stdClass();
                $assinaturaDigital->dataHora = $assinaturaSupp->getCriadoEm()->format('Y-m-d\TH:i:s.000P');

                $cadeiaDoCertificao = new stdClass();
                $cadeiaDoCertificao->formato = 'PEM';
                $cadeiaDoCertificao->_ = base64_encode($assinaturaSupp->getCadeiaCertificadoPEM());
                $assinaturaDigital->cadeiaDoCertificado = $cadeiaDoCertificao;

                $hashDeAssinatura = new stdClass();
                $hashDeAssinatura->algoritmo = 'SHA1withRSA';
                $hashDeAssinatura->_ = $assinaturaSupp->getAssinatura();
                $assinaturaDigital->hash = $hashDeAssinatura;

                $componenteDigitalEnvio->assinaturaDigital[] = $assinaturaDigital;
            }

            $componentesRetorno[] = $componenteDigitalEnvio;
        }

        return $componentesRetorno;
    }

    /**
     * Retorna todos os interessados do processo no formato esperado para envio ao barramento.
     *
     * @param Processo $processo
     *
     * @return array
     */
    public function getInteressados(Processo $processo): array
    {
        $mapModalidadeQualificacaoPessoa = $this->mapeamentos['modalidade_qualificacao_pessoa'];
        $mapModalidadeInteressado = $this->mapeamentos['modalidade_interessado_envio'];

        $interessados = [];

        /* @var Interessado $interessadoSupp */
        foreach ($processo->getInteressados() as $interessadoSupp) {
            $interessado = new stdClass();
            $interessado->nome = substr($interessadoSupp->getPessoa()->getNome(), 0, 150);

            // tipo
            $valor = $interessadoSupp->getPessoa()->getModalidadeQualificacaoPessoa()->getValor();
            $tipo = array_search($valor, $mapModalidadeQualificacaoPessoa);
            if ($tipo) {
                $interessado->tipo = $tipo;
            } else {
                $this->logger->critical('Não foi possível localizar o mapeamento para a modalidade '.
                    "de qualificação da pessoa: [{$valor}]");
            }

            // numeroDeIdentificacao
            $interessado->numeroDeIdentificacao = $interessadoSupp->getPessoa()->getNumeroDocumentoPrincipal();

            // polo
            $valor = $interessadoSupp->getModalidadeInteressado()->getValor();

            $polo = $this->getValorMapeado($mapModalidadeInteressado, $valor);
            if ($polo) {
                $interessado->polo = $polo;
            } else {
                $this->logger->critical('Não foi possível localizar o mapeamento para a modalidade '.
                    "do interessado: [{$valor}]");
            }

            // outroNome
            $outrosNomes = $interessadoSupp->getPessoa()->getOutrosNomes();
            $interessado->outroNome = [];
            /* @var Nome $outroNome */
            foreach ($outrosNomes as $outroNome) {
                if (!in_array($outroNome->getValor(), $interessado->outroNome)) {
                    $interessado->outroNome[] = $outroNome->getValor();
                }
            }

            // documentoDeIdentificacao
            $documentoDeIdentificacao = $this->getDocumentosDeIdentificacao($interessadoSupp->getPessoa());
            if ($documentoDeIdentificacao) {
                $interessado->documentoDeIdentificacao = $documentoDeIdentificacao;
            }

            // endereco
            $enderecos = $this->getEnderecos($interessadoSupp->getPessoa());
            if ($enderecos) {
                $interessado->endereco = $enderecos;
            }

            //nomeDoGenitor
            $interessado = $this->setAtributoObjeto(
                $interessado,
                'nomeDoGenitor',
                $interessadoSupp->getPessoa()->getNomeGenitor()
            );

            //nomeDaGenitora
            $interessado = $this->setAtributoObjeto(
                $interessado,
                'nomeDaGenitora',
                $interessadoSupp->getPessoa()->getNomeGenitora()
            );

            // dataDeNascimento - dataDeObito
            $interessado = $this->bindDataNascimentoObito($interessado, $interessadoSupp);

            // cidadeNatural - estadoNatural - nacionalidade
            $interessado = $this->bindCidadeEstadoPaisNatural($interessado, $interessadoSupp);

            $interessados[] = $interessado;
        }

        return $interessados;
    }

    /**
     * Retorna todos os documentos identificadores da pessoa no formato esperado para enviar ao barramento.
     *
     * @param Pessoa $pessoa
     *
     * @return array|null
     */
    private function getDocumentosDeIdentificacao(Pessoa $pessoa): ?array
    {
        $documentosRetorno = [];
        $documentosIdentificadores = $pessoa->getDocumentosIdentificadores();

        $mapModalidadeDocIdent = $this->mapeamentos['modalidade_doc_identificador'];

        /* @var DocumentoIdentificador $docIdentSupp */
        foreach ($documentosIdentificadores as $docIdentSupp) {
            $numDocumento = preg_replace('/\D/', '', $docIdentSupp->getCodigoDocumento());
            if (!$this->inArrayField($numDocumento, 'codigo', $documentosRetorno)) {
                $documentoDeIdentificacao = new stdClass();
                $documentoDeIdentificacao = $this
                    ->setAtributoObjeto($documentoDeIdentificacao, 'codigo', $numDocumento);
                $documentoDeIdentificacao = $this
                    ->setAtributoObjeto($documentoDeIdentificacao, 'emissor', $docIdentSupp->getEmissorDocumento());

                // tipo
                $modalidade = $docIdentSupp->getModalidadeDocumentoIdentificador();
                if ($modalidade) {
                    $valor = $modalidade->getValor();

                    $tipo = array_search($valor, $mapModalidadeDocIdent);
                    if ($tipo) {
                        $documentoDeIdentificacao->tipo = $tipo;
                    }
                    if (!$tipo) {
                        $this->logger->critical("Mapeamento da modalidade de documento identificador [{$valor}] ".
                            "não localizada. O documento identificador Id [{$docIdentSupp->getId()}] ".
                            'não será enviado.');
                        continue;
                    }
                }

                if (!$modalidade) {
                    $this->logger->critical('Mapeamento da modalidade de documento identificador '.
                        "não localizada. O documento identificador Id [{$docIdentSupp->getId()}] ".
                        'não será enviado.');
                    continue;
                }

                $documentoDeIdentificacao = $this
                    ->setAtributoObjeto($documentoDeIdentificacao, 'nomeNoDocumento', $docIdentSupp->getNome());

                $documentosRetorno[] = $documentoDeIdentificacao;
            }
        }

        return $documentosRetorno;
    }

    /**
     * Insere cidade, estado e nacionalidade nos dados do barramento para envio quando houver.
     *
     * @param stdClass    $interessadoBarramento
     * @param Interessado $interessadoSupp
     *
     * @return stdClass
     */
    private function bindCidadeEstadoPaisNatural(
        stdClass $interessadoBarramento,
        Interessado $interessadoSupp
    ): stdClass {
        $municipio = $interessadoSupp->getPessoa()->getNaturalidade();
        if ($municipio) {
            /* @var Municipio $municipio */
            $interessadoBarramento->cidadeNatural = $municipio->getNome();
            $interessadoBarramento->estadoNatural = $municipio->getEstado()->getUf();
            $interessadoBarramento->nacionalidade = $municipio->getEstado()->getPais()->getCodigo();
        }

        return $interessadoBarramento;
    }

    /**
     * Insere datas de nascimento e óbito nos dados do barramento para envio quando houver.
     *
     * @param stdClass    $interessadoBarramento
     * @param Interessado $interessadoSupp
     *
     * @return stdClass
     */
    private function bindDataNascimentoObito(stdClass $interessadoBarramento, Interessado $interessadoSupp): stdClass
    {
        if ($interessadoSupp->getPessoa()->getDataNascimento()) {
            $interessadoBarramento->dataDeNascimento = $interessadoSupp->getPessoa()
                ->getDataNascimento()->format('Y-m-d');
        }

        if ($interessadoSupp->getPessoa()->getDataObito()) {
            $interessadoBarramento->dataDeObito = $interessadoSupp->getPessoa()
                ->getDataObito()->format('Y-m-d');
        }

        return $interessadoBarramento;
    }

    /**
     * Retorna endereços da pessoa no formato esperado para enviar ao barramento.
     *
     * @param Pessoa $pessoa
     *
     * @return array|null
     */
    private function getEnderecos(Pessoa $pessoa): ?array
    {
        $enderecosRetorno = null;
        $enderecos = $pessoa->getEnderecos();
        /* @var Endereco $endereco */
        foreach ($enderecos as $endereco) {
            if ($endereco->getPrincipal()) {
                $enderecoBarramento = new stdClass();
                $enderecoBarramento = $this
                    ->setAtributoObjeto($enderecoBarramento, 'logradouro', $endereco->getLogradouro());
                $enderecoBarramento = $this
                    ->setAtributoObjeto($enderecoBarramento, 'numero', $endereco->getNumero());
                $enderecoBarramento = $this
                    ->setAtributoObjeto($enderecoBarramento, 'complemento', $endereco->getComplemento());
                $enderecoBarramento = $this
                    ->setAtributoObjeto($enderecoBarramento, 'bairro', $endereco->getBairro());
                $enderecoBarramento = $this
                    ->setAtributoObjeto($enderecoBarramento, 'cep', $endereco->getCep());

                if ($endereco->getMunicipio()) {
                    $enderecoBarramento->cidade = $endereco->getMunicipio()->getNome();
                    $enderecoBarramento->estado = $endereco->getMunicipio()->getEstado()->getUf();
                    $enderecoBarramento->pais = $endereco->getMunicipio()->getEstado()->getPais()->getCodigo();
                }

                $enderecosRetorno[] = $enderecoBarramento;
            }
        }

        return $enderecosRetorno;
    }
}
