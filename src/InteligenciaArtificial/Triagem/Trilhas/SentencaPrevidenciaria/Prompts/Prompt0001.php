<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaPrevidenciaria\Prompts;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\BaseTrilhaTriagemPrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaGestaoConhecimento\TrilhaSentencaGestaoConhecimento;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInput;

/**
 * Prompt0002.php
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
        //@codingStandardsIgnoreStart
        return json_encode(
            [
                'tipo_sentenca_previdenciario' => <<<EOT
                    insira aqui qual o tipo de sentença dentre as opções a seguir:
                    1) 'CONCESSÃO', se houve concessão de benefício previdenciário ou assistencial (prestação continuada, LOAS ou BPC);
                    2) 'RESTABELECIMENTO', se houve restabelecimento de benefício cessado;
                    3) 'REVISÃO', se houve condenação à alteração do valor da renda mensal inicial - RMI ou alteração do valor do benefício;
                    4) 'OUTRO', se a condenação do INSS não puder ser classificada nas três categorias acima.
                EOT,
                'nome' => [
                    'insira aqui, se houver, o nome completo do primeiro autor da ação que receberá o benefício objeto da condenação',
                    'insira aqui, se houver, o nome completo do segundo autor da ação que receberá o benefício objeto da condenação',
                    'e assim por diante'
                ],
                'revisao_fundamento' => 'caso haja condenação à revisão do valor da renda mensal inicial - RMI ou do valor do benefício previdenciário, insira aqui quais os fundamentos da revisão, como 
                    \'TEMPO ESPECIAL\', 
                    \'TEMPO URBANO\', 
                    \'TEMPO RURAL\', 
                    \'AVERBAÇÃO DE TEMPO ESPECIAL\', 
                    \'RELAÇÃO DE EMPREGO RECONHECIDA EM RECLAMAÇÃO TRABALHISTA NA JUSTIÇA DO TRABALHO\', 
                    \'ÍNDICE DE REAJUSTE DO SALÁRIO MÍNIMO - IRSM\', \'REVISÃO DA VIDA TODA\',\'BURACO NEGRO\'
                ',
                'incapacidade_indicador' => <<<EOT
                    caso haja reconhecimento de incapacidade ou de redução da capacidade para o trabalho, insira aqui qual o tipo dentre as opções a seguir:
                    1) 'INCAPACIDADE TEMPORÁRIA';
                    2) 'INCAPACIDADE TOTAL E PERMANENTE';
                    3) 'INCAPACIDADE PARCIAL';
                    4) 'INCAPACIDADE PERMANENTE PARA ATIVIDADE HABITUAL';
                    5) 'INCAPACIDADE PERMANENTE PARA TODA E QUALQUER ATIVIDADE';
                    6) 'INCAPACIDADE PRETÉRITA';
                    7) 'REDUÇÃO DA CAPACIDADE LABORATIVA'
                EOT,
                'acidente_indicador' => 'caso a incapacidade ou redução da capacidade laborativa tenha decorrido de acidente, insira aqui a data do acidente no tipo string e formato date do JSON SCHEMA, por exemplo: \'2022-05-17\'',
                'dependencia_economica_indicador' => 'caso haja o reconhecimento de dependência econômica na sentença, informe a existência de decisão judicial sobre a dependência econômica e seu fundamento, como 
                    \'DEPENDÊNCIA RECONHECIDA: FILHO MENOR\', 
                    \'DEPENDÊNCIA RECONHECIDA: MENOR SOB GUARDA\', 
                    \'DEPENDÊNCIA RECONHECIDA: COMPANHEIRA\', 
                    \'DEPENDÊNCIA RECONHECIDA: MÃE\'
                ',
                'dados_implantacao' => [
                    [
                        'nome' => 'insira aqui, se houver, o \'nome\' do primeiro segurado ou dependente a ser beneficiado pela implantação do benefício determinada pela sentença',
                        'nb' => 'insira aqui, se houver, o número de benefício (NB) em que haverá a implantação em favor do primeiro segurado ou dependente, como \'5081010592\'',
                        'especie_beneficio' => 'insira aqui a espécie de benefício a ser implantada em favor do primeiro segurado ou dependente, como \'AUXÍLIO POR INCAPACIDADE TEMPORÁRIA\', \'APOSENTADORIA POR IDADE RURAL\', \'PENSÃO POR MORTE\'',
                        'der' => 'insira aqui, se houver, a data de entrada do requerimento (DER) reconhecida pelo juiz na sentença em favor do primeiro segurado ou dependente no tipo string e formato date do JSON SCHEMA, por exemplo: \'2018-01-20\'',
                        'dib' => 'insira aqui, se houver, a data de início do benefício (DIB) indicada pelo juiz em favor do primeiro segurado ou dependente no tipo string e formato date do JSON SCHEMA, por exemplo: \'2018-01-20\'',
                        'data_restabelecimento' => 'insira aqui, se houver, a data de restabelecimento do benefício, no tipo string e formato date do JSON SCHEMA, por exemplo: \'2023-04-11\', ou insira a informação de que o restabelecimento foi fixado pelo juiz no dia seguinte à data de cessação do benefício no tipo string e formato date do JSON SCHEMA, por exemplo: \'2023-04-12\'',
                        'dcb' => 'insira aqui, se houver, a data de cessação do benefício (DCB) indicada pelo juiz para o primeiro segurado ou dependente no tipo string e formato date do JSON SCHEMA, por exemplo: \'2018-08-20\'',
                        'dip' => 'insira aqui, se houver, a data de início do pagamento (DIP) fixada pelo juiz na sentença para o primeiro segurado ou dependente no tipo string e formato date do JSON SCHEMA, por exemplo: \'20222-12-10\''
                    ],
                    [
                        'nome' => 'insira aqui, se houver, o \'nome\' do segundo segurado ou dependente a ser beneficiado pela implantação do benefício determinada pela sentença',
                        'nb' => 'insira aqui, se houver, o número de benefício (NB) em que haverá a implantação em favor do segundo segurado ou dependente, como \'5081010592\'',
                        'especie_beneficio' => 'insira aqui a espécie de benefício a ser implantada em favor do segundo segurado ou dependente, como \'AUXÍLIO POR INCAPACIDADE TEMPORÁRIA\', \'APOSENTADORIA POR IDADE RURAL\', \'PENSÃO POR MORTE\'',
                        'der' => 'insira aqui, se houver, a data de entrada do requerimento (DER) reconhecida pelo juiz na sentença em favor do segundo segurado ou dependente no tipo string e formato date do JSON SCHEMA, por exemplo: \'2018-01-20\'',
                        'dib' => 'insira aqui, se houver, a data de início do benefício (DIB) indicada pelo juiz em favor do segundo segurado ou dependente no tipo string e formato date do JSON SCHEMA, por exemplo: \'2018-01-20\'',
                        'data_restabelecimento' => 'insira aqui, se houver, a data de restabelecimento do benefício, no tipo string e formato date do JSON SCHEMA, por exemplo: \'2023-04-11\', ou insira a informação de que o restabelecimento foi fixado pelo juiz no dia seguinte à data de cessação do benefício no tipo string e formato date do JSON SCHEMA, por exemplo: \'2023-04-11\'',
                        'dcb' => 'insira aqui, se houver, a data de cessação do benefício (DCB) indicada pelo juiz para o segundo segurado ou dependente no tipo string e formato date do JSON SCHEMA, por exemplo: \'2018-03-20\'',
                        'dip' => 'insira aqui, se houver, a data de início do pagamento (DIP) fixada pelo juiz na sentença para o segundo segurado ou dependente no tipo string e formato date do JSON SCHEMA, por exemplo: \'2022-12-10\''
                    ],
                ],
                'vinculos_reconhecidos' => [
                    [
                        'vinculo' => 1,
                        'categoria_segurado' => 'insira aqui a primeira categoria de segurado (tipo de filiação) para o período reconhecido na sentença, como \'EMPREGADO\', \'EMPREGADO DOMÉSTICO\', \'TRABALHADOR AVULSO\', \'CONTRIBUINTE INDIVIDUAL\', \'SEGURADO ESPECIAL\' ou \'FACULTATIVO\'',
                        'origem_vinculo' => 'insira aqui, se existir, a \'origem do vínculo\' ou o nome do empregador constante do vínculo de trabalho ou emprego mencionado na sentença, como \'BOA PRAÇA LTDA\', \'POSTO XYZ\', \'PERÍODO DE ATIVIDADE DE SEGURADO ESPECIAL\'',
                        'ocupacao' => 'insira aqui o nome da função, ocupação ou atividade desempenhada, como \'MOTORISTA\',\'LAVRADOR\',\'AGRICULTOR\', \'COVEIRO\', \'VIGILANTE\', \'TRATORISTA\', \'ATENDENTE\', \'EMPRESÁRIO\', \'VENDEDOR\', \'BÓIA-FRIA\'',
                        'periodo_inicial' => 'insira aqui o dia, mês e ano (\'data início\') em que o segurado começou a trabalhar na \'ocupação\' indicada acima no tipo string, por exemplo: \'2000-01-01\'',
                        'periodo_final' => 'insira aqui o dia, mês e ano (\'data fim\') em que o segurado parou de trabalhar na \'ocupação\' indicada acima no tipo string, por exemplo: \'2019-03-05\'',
                        'agente_agressivo' => 'se houve pedido de reconhecimento ou averbação de \'tempo especial\', ou ainda de \'atividade especial\', insira aqui o agente agressivo prejudicial à saúde mencionado pela sentença, como \'RUÍDO\', \'ELETRICIDADE\', \'CALOR\', \'RADIAÇÃO IONIZANTE\', \'POEIRAS MINERAIS\', \'BENZENO\', \'SÍLICA\', \'FUNGOS\', \'BACTÉRIAS\', \'FRIO\''
                    ],
                    [
                        'vinculo' => 2,
                        'categoria_segurado' => 'insira aqui a segunda categoria de segurado (tipo de filiação) para o período reconhecido na sentença, como \'EMPREGADO\', \'EMPREGADO DOMÉSTICO\', \'TRABALHADOR AVULSO\', \'CONTRIBUINTE INDIVIDUAL\', \'SEGURADO ESPECIAL\' ou \'FACULTATIVO\'',
                        'origem_vinculo' => 'insira aqui, se existir, a \'origem do vínculo\' ou o nome do empregador constante do vínculo de trabalho ou emprego mencionado na sentença, como \'CERÂMICA RIBEIRO LTDA.\', \'INDÚSTRIA EXTRATIVA SA\', \'PERÍODO DE ATIVIDADE DE SEGURADO ESPECIAL\'',
                        'ocupacao' => 'insira aqui o nome da função, ocupação ou atividade desempenhada, como \'MOTORISTA\',\'LAVRADOR\',\'AGRICULTOR\', \'COVEIRO\', \'VIGILANTE\', \'TRATORISTA\', \'ATENDENTE\', \'EMPRESÁRIO\', \'VENDEDOR\', \'BÓIA-FRIA\'',
                        'periodo_inicial' => 'insira aqui o dia, mês e ano (\'data início\') em que o segurado começou a trabalhar na \'ocupação\' indicada acima no tipo string, por exemplo: \'01/04/2021\'',
                        'periodo_final' => 'insira aqui o dia, mês e ano (\'data fim\') em que o segurado parou de trabalhar na \'ocupação\' indicada acima no tipo string, por exemplo: \'05/02/2022\'',
                        'agente_agressivo' => 'se houve pedido de reconhecimento ou averbação de \'tempo especial\', ou ainda de \'atividade especial\', insira aqui o agente agressivo prejudicial à saúde mencionado pela sentença, como \'RUÍDO\', \'ELETRICIDADE\', \'CALOR\', \'RADIAÇÃO IONIZANTE\', \'POEIRAS MINERAIS\', \'BENZENO\', \'SÍLICA\', \'FUNGOS\', \'BACTÉRIAS\', \'FRIO\''
                    ]
                ]
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
        $resultadosSentenca = [
            'PROCEDENTE',
            'PARCIALMENTE PROCEDENTE',
            'HOMOLOGATÓRIA'
        ];
        $data = $triagemData[TrilhaSentencaGestaoConhecimento::getSiglaFormulario()];

        return isset($data['resultado_sentenca'])
            && in_array($data['resultado_sentenca'], $resultadosSentenca);
    }
}
