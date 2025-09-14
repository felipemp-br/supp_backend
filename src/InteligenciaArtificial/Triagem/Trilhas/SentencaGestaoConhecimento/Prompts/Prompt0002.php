<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaGestaoConhecimento\Prompts;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\BaseTrilhaTriagemPrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaGestaoConhecimento\TrilhaSentencaGestaoConhecimento;
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
                'resumo_relatorio' => 'insira aqui a sumarização do relatório da sentença com indicação dos principais fatos, provas, teses, alegações e decisões judiciais do processo em até 4000 caracteres',
                'palavras_chave' => 'insira aqui as palavras-chave da tese jurídica julgada pelo juiz, separadas por ponto final, como \'MILITAR. ANISTIADO. PROMOÇÃO\', \'SERVIDOR PÚBLICO. GREVE. DESCONTO\'',
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
                'julgador' => 'insira aqui o nome do juiz que assinou a sentença',
                'data_sentenca' => 'insira aqui a data da sentença, no tipo string e formato date do JSON SCHEMA, por exemplo: \'2020-02-14\'',
                'municipio_orgao_julgador' => 'extraia do final ou da última página da sentença, se houver, o local ou o município onde a sentença foi datada e assinada e insira aqui. Por exemplo: se constar \'Jaraguá-GO, data do sistema\', insira \'JARAGUÁ\'; se constar \'Aracaju, 10/04/2022\', insira \'ARACAJU\'',
                'estado' => 'insira aqui o nome do estado da federação onde fica o município constante da chave \'municipio_orgao_julgador\', como \'GOIÁS\', \'SERGIPE\'',
                'preliminar' => [
                    'insira aqui, se houver, a primeira preliminar ou prejudicial decidida pelo juiz na sentença',
                    'insira aqui, se houver, a segunda preliminar ou prejudicial decidida pelo juiz na sentença',
                    'e assim por diante'
                ],
                'fundamentos_juridicos' => [
                    'insira aqui o primeiro fundamento jurídico da sentença',
                    'insira aqui o segundo fundamento jurídico da sentença',
                    'e assim por diante'
                ],
                'dispositivo' => [
                    'dispositivo_da_sentenca' => 'insira aqui o dispositivo da sentença com todas as questões preliminares e de mérito julgadas pelo juiz, como:         
                        \'3. Dispositivo
                        Ante o exposto:
                        a) extingo o feito sem resolução de mérito quanto aos pedidos "1" e "2" da inicial do evento 12, pela ausência de interesse de agir, fulcro no art. 485, VI, do CPC;
                        b) reconheço a prescrição quinquenal das parcelas anteriores ao ajuizamento da ação; 
                        c) julgo parcialmente procedente o pedido (art. 487, inciso I do NCPC) da parte autora, para condenar o INSS a:
                        c.1) computar o lapso de 01/01/2007 a 18/05/2018 de tempo comum, como laborado pela parte autora na qualidade de segurado empregado, somando-o ao tempo já reconhecido administrativamente;
                        c.2) computar os lapsos de 01/01/2020 a 30/03/2021 como laborados pela autora como contribuinte individual, somando-os ao tempo já reconhecido administrativamente;
                        c.3) conceder o benefício de aposentadoria por tempo de contribuição, com data de início do benefício (DIB) na data do requerimento administrativo (DER), calculado na forma do art. 20 da EC 103/2019, nos termos da fundamentação;
                        c.4) pagar o valor correspondente às diferenças apuradas, com as prestações devidamente corrigidas desde a data em que eram devidas, calculadas conforme a fundamentação;
                        c.5) pagar indenização a título de danos morais no valor de R$ 5.000,00 (cinco mil reais) nos termos da fundamentação.
                        Defiro a antecipação dos efeitos da tutela, no sentido de determinar que o Instituto Nacional do Seguro Social implemente o benefício no prazo de 20 (vinte) dias, conforme evento 6 do Anexo I da Resolução nº 173/2022, de 15/06/2022, do TRF4.
                        \'',
                    'valor_condenacao' => [
                        [
                            'condenacao' => 1,
                            'valor_economico' => 'insira aqui o primeiro valor da condenação no formato number, exemplo: 5000.00',
                            'fundamento_juridico' => 'insira aqui o primeiro fundamento jurídico da condenação, como \'INDENIZAÇÃO POR DANOS MORAIS\', \'INDENIZAÇÃO DA TERRA NUA\', \'INDENIZAÇÃO POR DANOS MATERIAIS\', \'REPETIÇÃO DE INDÉBITO\'',
                            'data_calculo' => 'insira aqui a data do cálculo do valor da primeira condenação no tipo string e formato date do JSON SCHEMA, por exemplo: \'2022-04-27\''
                        ],
                        [
                            'condenacao' => 2,
                            'valor_economico' => 'insira aqui o segundo valor da condenação no formato number, exemplo: 5000.00',
                            'fundamento_juridico' => 'insira aqui o segundo fundamento jurídico da condenação, como \'INDENIZAÇÃO POR DANOS MORAIS\', \'INDENIZAÇÃO DA TERRA NUA\', \'INDENIZAÇÃO POR DANOS MATERIAIS\', \'REPETIÇÃO DE INDÉBITO\'',
                            'data_calculo' => 'insira aqui a data do cálculo do valor da primeira condenação no tipo string e formato date do JSON SCHEMA, por exemplo: \'2022-04-27\''
                        ]
                    ],
                    'atualizacao' => 'insira aqui, se houver, os critérios utilizados para a correção monetária (atualização) e incidência de juros, como \'juros de acordo com a remuneração básica da caderneta de poupança (TR) a contar do fato danoso (27.04.2022) e correção monetária pelo IPCA-E a partir do arbitramento (Súmula 362 do STJ)\', \'juros pela variação da taxa SELIC\'',
                    'honorarios' => 'insira aqui, se houver, o valor da condenação em honorários advocatícios e seu fundamento jurídico, como 
                                        \'Condeno ao pagamento dos honorários sucumbenciais no valor correspondente a 5% sobre o valor da condenação, nos termos do disposto no art. 27, §1º, do Decreto-Lei n. 3.365/1941\' ou 
                                        \'Dada a sucumbência mínima da parte autora, nos termos do artigo 86, parágrafo único, do Novo Código de Processo Civil, condeno o INSS ao pagamento integral dos honorários sucumbência fixados no percentual mínimo de cada faixa estipulada pelo artigo 85, §3°, do Novo Código de Processo Civil, dependendo da apuração do montante em eventual cumprimento de sentença, sempre observando o §5° do artigo 85 do CPC. A base de cálculo será o valor da condenação, limitado ao valor das parcelas vencidas até a sentença (Súmula 111, STJ; Súmula 76, TRF4)\'
                                    '
                ],
                'resultado_sentenca' => 'insira aqui o principal resultado do julgamento, que consta na parte dispositiva da sentença, como \'PROCEDENTE\', \'IMPROCEDENTE\', \'PARCIALMENTE PROCEDENTE\', \'HOMOLOGATÓRIA\', \'SEM RESOLUÇÃO DE MÉRITO\'',
                'multa' => 'insira aqui, se houver, a aplicação de multa, seu valor e seu fundamento no formato string ou null, como: \'MULTA (ASTREINTE) NO VALOR DE R$ 150,00 POR DIA POR DESCUMPRIMENTO DA DECISÃO\', \'MULTA DE 2% DO VALOR CORRIGIDO DA CAUSA POR LITIGÂNCIA DE MÁ-FÉ\'',
                'tutela' => 'insira aqui \'TUTELA CONCEDIDA\' apenas se houve o deferimento, ratificação, confirmação, manutenção ou concessão de \'liminar\', \'tutela antecipada\', \'medida cautelar\', \'tutela provisória\' ou \'tutela de evidência\' no dispositivo da sentença. Insira apenas \'TUTELA REVOGADA\' se houve revogação da tutela no dispositivo da sentença. Insira apenas \'TUTELA INDEFERIDA\' se houve indeferimento da tutela no dispositivo da sentença. Insira \'null\' se não houver informação sobre tutela no dispositivo da sentença'
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
        $data = $triagemData[TrilhaSentencaGestaoConhecimento::getSiglaFormulario()];
        return isset($data['sentenca_indicador'])
            && filter_var($data['sentenca_indicador'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === true;
    }
}
