<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\PeticaoInicialGestaoConhecimento\Prompts;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\BaseTrilhaTriagemPrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\PeticaoInicialGestaoConhecimento\TrilhaPeticaoInicialGestaoConhecimento;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInput;

/**
 * Prompt0002.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Prompt0002 extends BaseTrilhaTriagemPrompt
{
    /**
     * Retorna o texto a ser enviado para a IA.
     *
     * @return string
     */
    public function getText(): string
    {
        //@codingStandardsIgnoreStart
        return json_encode(
            [
                'ramo_direito' => 'insira aqui o ramo do direito, como \'MILITARES\', \'SERVIDORES PÚBLICOS CIVIS\', \'DIREITO AMBIENTAL\', \'DIREITO PREVIDENCIÁRIO\', \'DIREITO ADMINISTRATIVO\'',
                'tema' => 'insira aqui a matéria ou assunto tratado pelo caso jurídico em discussão, como \'LICENCIAMENTO AMBIENTAL\', \'PENSÃO POR MORTE\', \'SEGURO DEFESO\', \'ABONOS\'',
                'tese_juridica_controvertida' => 'insira aqui a tese jurídica controvertida, como \'DIREITO DO MILITAR ANISTIADO A TODAS AS PROMOÇÕES A QUE TERIA DIREITO SE NA ATIVA ESTIVESSE, DESDE QUE DENTRO DA CARREIRA A QUE PERTENCIA À ÉPOCA DE SEU DESLIGAMENTO\', \'LEGITIMIDADE DO ATO DA ADMINISTRAÇÃO QUE PROMOVE O DESCONTO DOS DIAS NÃO TRABALHADOS DURANTE A GREVE\'',
                'palavras_chave' => 'insira aqui as palavras-chave da tese jurídica, separadas por ponto final, como \'MILITAR. ANISTIADO. PROMOÇÃO\', \'SERVIDOR PÚBLICO. GREVE. DESCONTO\'',
                'dispositivos_legais' => [
                    'insira aqui o primeiro dispositivo legal, usando o formato urn da lexml',
                    'insira aqui o segundo dispositivo legal, usando o formato urn da lexml',
                    'e assim por diante'
                ],
                'precedentes' => [
                    'insira aqui a primeira \'decisão judicial\', \'tema repetitivo\', \'tese firmada\', \'tema/IAC\', \'tese de repercussão geral \', \'tema representativo\', \'tema\' ou súmula da jurisprudência, usando o formato urn da lexml',
                    'insira aqui a segunda \'decisão judicial\', \'tema repetitivo\', \'tese firmada\', \'tema/IAC\', \'tese de repercussão geral \', \'tema representativo\', \'tema\' ou súmula da jurisprudência, usando o formato urn da lexml',
                    'e assim por diante'
                ],
                'fatos_narrados' => [
                    'insira aqui o primeiro fato relevante narrado',
                    'insira aqui o segundo fato relevante narrado',
                    'e assim por diante'
                ],
                'fundamentos_juridicos' => [
                    'insira aqui o primeiro fundamento jurídico do pedido',
                    'insira aqui o segundo fundamento jurídico do pedido',
                    'e assim por diante'
                ],
                'pedidos_formulados' => [
                    'insira aqui o primeiro pedido formulado',
                    'insira aqui o segundo pedido formulado',
                    'e assim por diante'
                ],
                'tutela_solicitada' => 'insira aqui \'TUTELA SOLICITADA\' se houver um pedido de \'liminar\', \'TUTELA ANTECIPADA\', \'MEDIDA CAUTELAR\', \'TUTELA PROVISÓRIA\' ou \'TUTELA DE EVIDÊNCIA\'',
                'tutela_coletiva' => 'insira aqui \'AÇÃO COLETIVA\' se o processo for do tipo \'AÇÃO POPULAR\', \'AÇÃO CIVIL PÚBLICA\', \'MANDADO DE SEGURANÇA COLETIVO\', \'MANDADO DE INJUNÇÃO COLETIVO\' ou ainda se a ação foi ajuizada por \'SINDICATO\' ou \'ASSOCIAÇÃO\'',
                'valor_da_causa' => 'insira aqui o valor da causa no formato number, se houver, por exemplo: 100000.00',
                'somatorio_valores_diversos' => 'insira aqui, se houver, o somatório de valores econômicos diferentes do valor da causa, que tenham sido mencionados ao longo dos fatos e fundamentos jurídicos da petição inicial no formato number, por exemplo: 200000000.00',
                'resumo_peticao' => 'insira aqui a sumarização da petição judicial em até 600 palavras'
            ],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
        //@codingStandardsIgnoreEnd
    }

    /**
     * Retorna se o prompt suporta a trilha de triagem.
     *
     * @param TrilhaTriagemInput $input
     * @param array              $triagemData
     * @return bool
     */
    public function suppports(TrilhaTriagemInput $input, array $triagemData = []): bool
    {
        $data = $triagemData[TrilhaPeticaoInicialGestaoConhecimento::getSiglaFormulario()];
        return isset($data['peticao_inicial_indicador'])
            && filter_var($data['peticao_inicial_indicador'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true;
    }
}
