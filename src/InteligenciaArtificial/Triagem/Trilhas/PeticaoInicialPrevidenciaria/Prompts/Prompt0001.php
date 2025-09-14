<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\PeticaoInicialPrevidenciaria\Prompts;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\BaseTrilhaTriagemPrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInput;

/**
 * Prompt0001.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Prompt0001 extends BaseTrilhaTriagemPrompt
{
    /**
     * Retorna o texto a ser enviado para a IA.
     *
     * @return string
     */
    public function getText(): string
    {
        return json_encode(
            [
                'tipo_pedido_previdenciario' => <<<EOT
                    'insira aqui: 
                    1)'CONCESSÃO', se houve pedido de concessão de benefício previdenciário ou assistencial (prestação continuada, LOAS ou BPC);
                    2)'RESTABELECIMENTO', se houve pedido de restabelecimento de benefício cessado;
                    3)'REVISÃO', se houve pedido de alteração do valor da renda mensal inicial - RMI ou alteração do valor do benefício; ou
                    4)'OUTRO', se o pedido de condenação do INSS não puder ser classificado nas três categorias anteriores.
                EOT,
                'especie_beneficio' => 'insira aqui, se houver, a espécie de benefício requerida nos pedidos da petição inicial, como \'APOSENTADORIA POR IDADE\', \'APOSENTADORIA POR IDADE RURAL\', \'PENSÃO POR MORTE\', \'AUXÍLIO-DOENÇA\', \'AUXÍLIO-DOENÇA OU APOSENTADORIA POR INVALIDEZ\', \'AUXÍLIO POR INCAPACIDADE TEMPORÁRIA\', \'LOAS\', \'APOSENTADORIA POR INVALIDEZ OU BPC\'',
                'rural_urbano_indicador' => 'insira aqui \'RURAL\' ou \'URBANO\'. O benefício será \'RURAL\' se os fatos, os fundamentos jurídicos do pedido, a causa de pedir ou os pedidos indicarem que a ação trata da comprovação de efetivo exercício de \'atividade rural\', \'agrícola\', \'pastoril\' ou \'hortifrutigranjeira\' do \'segurado especial\', assim como da concessão de benefícios a \'produtor rural\', \'lavrador\', \'posseiro\', \'assentado\', \'parceiro\', \'meeiro\', \'seringueiro\', \'rurícola\', \'pescador artesanal\' ou assemelhado, desde que exerça a efetiva atividade rural individualmente ou em regime de economia familiar. Nos demais casos, o benefício será \'URBANO\'',
                'revisao_fundamento' => 'caso haja pedido de revisão da renda mensal inicial - RMI ou do valor do benefício previdenciário, insira aqui quais os fundamentos jurídicos do pedido de revisão, como \'TEMPO ESPECIAL\', \'TEMPO URBANO\', \'TEMPO RURAL\', \'AVERBAÇÃO DE TEMPO ESPECIAL\', \'RELAÇÃO DE EMPREGO RECONHECIDA EM RECLAMAÇÃO TRABALHISTA NA JUSTIÇA DO TRABALHO\', \'ÍNDICE DE REAJUSTE DO SALÁRIO MÍNIMO - IRSM\', \'REVISÃO DA VIDA TODA\'',
                'nb_dados' => [
                    [
                        'processo_administrativo' => 1,
                        'nb' => 'insira aqui, se houver, o primeiro número de benefício (NB) mencionado pela petição inicial, como \'5081010592\'',
                        'beneficio' => 'insira aqui a espécie de benefício requerida no primeiro \'nb\', como \'AUXILIO DOENCA PREVIDENCIARIO\'',
                        'der' => 'insira aqui, se houver, a data de entrada do requerimento (DER) do primeiro \'nb\', no tipo string e formato date do JSON SCHEMA, por exemplo: \'2018-01-20\'',
                        'dib' => 'insira aqui, se houver, a data de início do benefício (DIB) do primeiro \'nb\', no tipo string e formato date do JSON SCHEMA, por exemplo: \'2017-09-20\'',
                        'dcb' => 'insira aqui, se houver, a data de cessação do benefício (DCB) do primeiro \'nb\', no tipo string e formato date do JSON SCHEMA, por exemplo: \'2018-03-20\''
                    ],
                    [
                        'processo_administrativo' => 2,
                        'nb' => 'insira aqui, se houver, o segundo número de benefício (NB) mencionado pela petição inicial, como \'1103472639\'',
                        'beneficio' => 'insira aqui a espécie de benefício requerida no segundo \'nb\', como \'APOSENTADORIA POR INVALIDEZ ACIDENTÁRIA\'',
                        'der' => 'insira aqui, se houver, a data de entrada do requerimento (DER) do segundo \'nb\', no tipo string e formato date do JSON SCHEMA, por exemplo: \'2021-10-20\'',
                        'dib' => 'insira aqui, se houver, a data de início do benefício (DIB) do segundo \'nb\', no tipo string e formato date do JSON SCHEMA, por exemplo: \'2019-09-20\'',
                        'dcb' => 'insira aqui, se houver, a data de cessação do benefício (DCB) do segundo \'nb\', no tipo string e formato date do JSON SCHEMA, por exemplo: \'2021-03-20\''
                    ]
                ],
                'vinculos_requeridos' => [
                    [
                        'vinculo' => 1,
                        'categoria_segurado' => 'insira aqui a primeira categoria de segurado (tipo de filiação) para o período requerido na petição inicial, como \'EMPREGADO\', \'EMPREGADO DOMÉSTICO\', \'TRABALHADOR AVULSO\', \'CONTRIBUINTE INDIVIDUAL\', \'SEGURADO ESPECIAL\' ou \'FACULTATIVO\'',
                        'origem_vinculo' => 'insira aqui, se existir, a \'origem do vínculo\' ou o nome do empregador constante do vínculo de trabalho ou emprego mencionado na petição inicial, como \'BOA PRAÇA LTDA\', \'POSTO XYZ\', \'PERÍODO DE ATIVIDADE DE SEGURADO ESPECIAL\'',
                        'ocupacao' => 'insira aqui o nome da função, ocupação ou atividade desempenhada, como \'MOTORISTA\',\'LAVRADOR\',\'AGRICULTOR\', \'COVEIRO\', \'VIGILANTE\', \'TRATORISTA\', \'ATENDENTE\', \'EMPRESÁRIO\', \'VENDEDOR\', \'BÓIA-FRIA\'',
                        'periodo_inicial' => 'insira aqui o dia, mês e ano (\'data início\') em que o segurado começou a trabalhar na \'ocupação\' indicada acima, no tipo string e formato date do JSON SCHEMA, por exemplo: \'2000-01-01\'',
                        'periodo_final' => 'insira aqui o dia, mês e ano (\'data fim\') em que o segurado parou de trabalhar na \'ocupação\' indicada acima, no tipo string e formato date do JSON SCHEMA, por exemplo: \'2019-03-05\'',
                        'agente_agressivo' => 'se houve pedido de reconhecimento ou averbação de \'tempo especial\', ou ainda de \'atividade especial\', insira aqui o agente agressivo prejudicial à saúde mencionado pela petição inicial, como \'RUÍDO\', \'ELETRICIDADE\', \'CALOR\', \'RADIAÇÃO IONIZANTE\', \'POEIRAS MINERAIS\', \'BENZENO\', \'SÍLICA\', \'FUNGOS\', \'BACTÉRIAS\', \'FRIO\''
                    ],
                    [
                        'vinculo' => 2,
                        'categoria_segurado' => 'insira aqui a segunda categoria de segurado (tipo de filiação) para o período requerido na petição inicial, como \'EMPREGADO\', \'EMPREGADO DOMÉSTICO\', \'TRABALHADOR AVULSO\', \'CONTRIBUINTE INDIVIDUAL\', \'SEGURADO ESPECIAL\' ou \'FACULTATIVO\'',
                        'origem_vinculo' => 'insira aqui, se existir, a \'origem do vínculo\' ou o nome do empregador constante do vínculo de trabalho ou emprego mencionado na petição inicial, como \'CERÂMICA RIBEIRO LTDA.\', \'INDÚSTRIA EXTRATIVA SA\', \'PERÍODO DE ATIVIDADE DE SEGURADO ESPECIAL\'',
                        'ocupacao' => 'insira aqui o nome da função, ocupação ou atividade desempenhada, como \'MOTORISTA\',\'LAVRADOR\',\'AGRICULTOR\', \'COVEIRO\', \'VIGILANTE\', \'TRATORISTA\', \'ATENDENTE\', \'EMPRESÁRIO\', \'VENDEDOR\', \'BÓIA-FRIA\'',
                        'periodo_inicial' => 'insira aqui o dia, mês e ano (\'data início\') em que o segurado começou a trabalhar na \'ocupação\' indicada acima, no tipo string e formato date do JSON SCHEMA, por exemplo: \'2021-04-01\'. Caso não exista não preencha esta chave e valor.',
                        'periodo_final' => 'insira aqui o dia, mês e ano (\'data fim\') em que o segurado parou de trabalhar na \'ocupação\' indicada acima, no tipo string e formato date do JSON SCHEMA, por exemplo: \'2022-02-05\'. Caso não exista não preencha esta chave e valor.',
                        'agente_agressivo' => 'se houve pedido de reconhecimento ou averbação de \'tempo especial\', ou ainda de \'atividade especial\', insira aqui o agente agressivo prejudicial à saúde mencionado pela petição inicial, como \'RUÍDO\', \'ELETRICIDADE\', \'CALOR\', \'RADIAÇÃO IONIZANTE\', \'POEIRAS MINERAIS\', \'BENZENO\', \'SÍLICA\', \'FUNGOS\', \'BACTÉRIAS\', \'FRIO\''
                    ]
                ]
            ],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
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
        return true;
    }
}
