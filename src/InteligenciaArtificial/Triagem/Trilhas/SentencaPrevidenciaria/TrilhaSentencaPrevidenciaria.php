<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaPrevidenciaria;

use SuppCore\AdministrativoBackend\Entity\Interessado;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Attributes\Trilha;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\BaseTrilhaTriagem;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaGestaoConhecimento\TrilhaSentencaGestaoConhecimento;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaPrevidenciaria\Prompts\Prompt0001;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInput;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInterface;

/**
 * TrilhaSentencaGestaoConhecimento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Trilha(
    nome: 'Trilha de Sentença Previdenciaria',
    prompts: [
        Prompt0001::class
    ],
    dependsOn: [
        TrilhaSentencaGestaoConhecimento::class
    ]
)]
class TrilhaSentencaPrevidenciaria extends BaseTrilhaTriagem implements TrilhaTriagemInterface
{
    public const CONFIG_MODULO_KEY = 'ADMINISTRATIVO_TRILHA_SENTENCA_PREVIDENCIARIA';    

    public array $config = [
        'ativo' => false,
        'tipos_documentos_suportados' => [''],
    ];

    /**
     * Função que é chamada após o constructor.
     * Serve para ser sobrescrita e realizar operações iniciais.
     *
     * @return void
     */
    protected function initialize(): void
    {
        if ($this->suppParameterBag->hasBySigla(self::CONFIG_MODULO_KEY)) {
            $this->config = $this->suppParameterBag->get(self::CONFIG_MODULO_KEY);
        }
    }

    /**
     * Indica se a trilha está ativa.
     *
     * @return bool
     */
    public function isAtiva(): bool
    {
        return $this->config['ativo'];
    }

    /**
     * Retorna se a trilha suporta o tipo de documento.
     *
     * @param string $siglaTipoDocumento
     *
     * @return bool
     */
    public function suportaTipoDocumento(string $siglaTipoDocumento): bool
    {
        return empty($this->config['tipos_documentos_suportados'])
            || in_array(
                $siglaTipoDocumento,
                $this->config['tipos_documentos_suportados']
            );
    }

    protected function verificaSubstringsArray($variavel_array, $list): bool 
    {
        foreach ($variavel_array as $variavel) {
            foreach ($list as $substring) {
                if (mb_strpos(mb_strtolower($variavel), mb_strtolower($substring)) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Verifica se os dipositivos legais possui uma parte do string da lei 
     * e se não contem palavras_chave proibidas. Exemplo não pode conter nas palavras_chave: servidor público e desconto 
     * @param array $dispositivos_legais
     * @param string $palavras_chave
     * @return bool
     */
    protected function verificaRegra(array $dispositivos_legais, string $palavras_chave): bool 
    {
        // define o conjunto de valores permitidos
        $valores_permitidos = $this->config['contem_string_dispositivos_legais'];
        $nao_deve_constar_palavras_chave = $this->config['nao_deve_constar_palavras_chave'];

        // verifica se dispositivo legal contém '8.213', '8213' e se nas palavras_chave NÃO contém os palavras proibidos
        // if (in_array($dispositivos_legais, $valores_permitidos)) {
        if ($this->verificaSubstringsArray($dispositivos_legais, $valores_permitidos)) {
            foreach ($nao_deve_constar_palavras_chave as $palavra_nao_deve_constar) {
                if (mb_strpos(mb_strtolower($palavras_chave), mb_strtolower($palavra_nao_deve_constar)) !== false) {
                    return false;    // encontrou uma palavra proibida
                }
            }  
            return true;
        }

        return false;
    }
    /**
     * Verifica se o Nome do Interessado consta INSS na modalidade 'requerido'
     * @param  Interessado[] $interessados
     * @return bool
     */
    protected function verificaNomeEModalidadeInteressado($interessados): bool 
    {
        foreach ($interessados as $interessado) {
            $modalidadeInteressado = mb_strtolower(
                $interessado->getModalidadeInteressado()->getValor()
            );
            $nomeInteressado = mb_strtolower($interessado->getPessoa()->getNome());

            if (
                $modalidadeInteressado === mb_strtolower($this->config['nome_modalidade_interessado']) 
                && $nomeInteressado === mb_strtolower($this->config['nome_interessado'])
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Retorna a sigla do formulário da trilha de triagem.
     *
     * @return string
     */
    public static function getSiglaFormulario(): string
    {
        return 'ia_sentenca_previdenciaria_1';
    }

    /**
     * Verifica se a trilha suporta o input.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return bool
     */
    public function supports(TrilhaTriagemInput $input): bool
    {
        if ($this->isAtiva()) {
            $siglaTipoDoc = $input->documento?->getDocumentoIAMetadata()?->getTipoDocumentoPredito()?->getSigla()
                ?? $input->documento->getTipoDocumento()->getSigla();
            
            if ($this->suportaTipoDocumento($siglaTipoDoc)) {
                
                /** @var Interessado[] $interessados */
                $interessados = $input->documento
                    ?->getJuntadaAtual()
                    ?->getVolume()
                    ?->getProcesso()
                    ?->getInteressados() ?? [];

                if ($this->verificaNomeEModalidadeInteressado($interessados)) {
                    $nomeEspecieSetor = $input->documento
                        ?->getJuntadaAtual()
                        ?->getVolume()
                        ?->getProcesso()
                        ?->getSetorAtual()
                        ?->getEspecieSetor()
                        ?->getNome() ?? '';

                    if (mb_strtolower($nomeEspecieSetor) === mb_strtolower($this->config['nome_especie_setor'])) {
                        return true;
                    } else {
                        $dadosFormulario = null;
                        $formularioGestaoConhecimento = $this->formularioRepository
                            ->findOneby(['sigla' => TrilhaSentencaGestaoConhecimento::getSiglaFormulario()]);
                        if($formularioGestaoConhecimento) {
                            $dadosFormulario = $this->dadosFormularioResource->findOneBy([
                                'documento' => $input->documento,
                                'formulario' => $formularioGestaoConhecimento
                            ]);
                        }
                        if ($dadosFormulario) {
                            $data = json_decode($dadosFormulario->getDataValue(), true) ?? [];
                            $dispositivos_legais = $data['dispositivos_legais'];
                            $palavras_chave = $data['palavras_chave'];
                        }

                        if (isset($dispositivos_legais) && isset($palavras_chave)) {
                            if($this->verificaRegra($dispositivos_legais, $palavras_chave)) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Indica se a trilha deve manter o contexto entre os prompts.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return bool
     */
    protected function keepContext(TrilhaTriagemInput $input): bool
    {
        return false;
    }

    
}
