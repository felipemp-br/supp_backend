<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseInicialEmpregoBeneficiario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialEmpregoBeneficiario extends AbstractAnaliseIA implements AnalisaDossiesInterface
{
    /**
     * Retorna a sigla dos tipos de dossies suportados.
     *
     * @return string[]
     */
    protected function getSiglasTipoDossiesSuportados(): array
    {
        return [
            'DOSPREV'
        ];
    }

    /**
     * Retorna a instrução da análise.
     *
     * @param string $cpfAnalisado
     * @param array  $dadosTipoSolicitacao
     *
     * @return string
     */
    protected function getInstrucaoAnalise(
        string $cpfAnalisado,
        array $dadosTipoSolicitacao,
    ): string {
        $nb = $dadosTipoSolicitacao['nb'];
        $dataNascimentoCrianca = (new DateTime($dadosTipoSolicitacao['dataNascimentoCrianca']))->format('d/m/Y');

        return <<<EOT
            EMPREGO.
            Instruções para o Assistente:
            Você é um especialista em análise de benefícios previdenciários. Receba a data de nascimento da criança e um dossiê 
            previdenciário que especifica os vínculos previdenciários da requerente. Com base nessas informações, realize a seguinte 
            análise.
            
            Análise dos 5 anos ANTERIORES ao nascimento da criança:
            
            Objetivo 1: Identificar se existe vínculo de emprego, contribuinte facultativo ou contribuinte individual da requerente 
            nesse período desconsiderando os vinculos do tipo de filiação benefício.
            Critérios:
            Verificar se o(s) vínculo(s), isoladamente ou somados, superam 120 dias por ano.
            O termo final da contagem será a DATA DO FIM do vínculo.
            Se não houver DATA DO FIM, considerar a ÚLTIMA REMUNERAÇÃO.
            Fonte de dados: Relações previdenciárias no dossiê da requerente.
            
            Análise dos 4 meses POSTERIORES ao nascimento da criança:
            
            Objetivo 2: Identificar se existe vínculo de emprego, contribuinte facultativo ou contribuinte individual da requerente nesse período desconsiderando os vinculos do tipo de filiação benefício.
            Critério:
            Basta possuir início do vínculo; não é necessária a data do fim ou data da última remuneração.
            Fonte de dados: Relações previdenciárias no dossiê da requerente.
            
            Análise de Exceção (se houver resultado positivo em "1" ou "2"):
            
            Objetivo 3: Antes de dar a resposta final, verifique se a duração do vínculo encontrado está totalmente fora do período 
            que vai de 12 meses antes da data {$dataNascimentoCrianca} até 4 meses depois dessa data.
            Critérios:
            O vínculo deve ser de tipo empregado, contribuinte facultativo ou contribuinte individual.
            Considere como fim do vínculo a DATA FIM indicada.
            Se não houver DATA FIM, considere a ÚLTIMA REMUNERAÇÃO como fim.
            Inclua o resultado dessa análise no campo 'resultado_analise'.
            
            Resultado Final:
            
                Se o resultado for negativo em ambas as análises "1" e "2":
                    Retorne 'passou_analise': True.
                    Preencha 'observação' com null.
            
                Se o resultado for positivo em "1" ou "2":
                    Inclua o resultado da Análise de Exceção no campo 'resultado_analise'.
                    Se a Análise de Exceção for positiva:
                        Retorne 'passou_analise': True.
                        Preencha 'observação' com 'EMPREGO'.
                    Se a Análise de Exceção for negativa:
                        Retorne 'passou_analise': False.
                        Se a remuneração/salário de contribuição for de até 02 (dois) salários-mínimos da época, preencha 'observação' 
                        com 'ATÉ DOIS SAL MIN'; caso contrário, preencha 'observação' com null.
        EOT;
    }

}
