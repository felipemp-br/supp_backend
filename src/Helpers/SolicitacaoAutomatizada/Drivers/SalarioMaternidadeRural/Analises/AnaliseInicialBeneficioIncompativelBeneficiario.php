<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseInicialBeneficioIncompativelBeneficiario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialBeneficioIncompativelBeneficiario extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
            BENEFÍCIO INCOMPATÍVEL. 
            Passo 1: Se o documento for um dossiê previdenciário, você deve percorrê-lo e verificar os seguintes dados em 
            relação à beneficiária e ao número de benefício NB {$nb} e, depois, analisar e responder conforme instruções nos 
            passos seguintes:
            A) RELAÇÃO DE PROCESSOS MOVIDOS PELO AUTOR/CPF CONTRA O INSS; 
            B) RESUMO INICIAL – DADOS GERAIS DOS REQUERIMENTOS; 
            C) RELAÇÕES PREVIDENCIÁRIAS; 
            D) COMPETÊNCIAS DETALHADAS.
            Passo 2: Você deve identificar se há percepção de benefício incompatível com 
            salário-maternidade. Para isso, verifique o teor dos diferentes sequenciais do campo 'COMPETÊNCIAS DETALHADAS' e:
            a) desconsidere todos os benefícios em que aparece o campo 'Situação' com o valor 'INDEFERIDO'; 
            b) desconsidere todos os benefícios da Espécie 'PENSÃO POR MORTE DE SEGURADO ESPECIAL'; 
            c) Verifique se há algum benefício cuja 'Situação' conste como 'ATIVO' e, cumulativamente, a 'Data Início (DIB)' desse 
            benefício 'ATIVO' seja anterior à data {$dataNascimentoCrianca}. Se houver considere e responda que há percepção de benefício 
            incompatível;
            d) Verifique se há benefício cuja 'Situação' tenha o valor de 'CESSADO' ou 'SUSPENSO'. Verifique se há sobreposição, 
            ainda que parcial, entre os seguintes intervalos: o primeiro intervalo que vai da 'Data Início (DIB)' até a 'Data Fim 
            (DCB)' do benefício analisado; e o segundo intervalo que se inicia 12 (doze) meses antes da data {$dataNascimentoCrianca} e termina 04 (quatro) 
            meses após a data {$dataNascimentoCrianca}. Se houver sobreposição entre esses períodos, ainda que parcial, considere e responda que há percepção de benefício 
            incompatível. 
            Encontrando alguma resposta positiva em c) ou em d), responda que há impeditivo de BENEFÍCIO INCOMPATÍVEL.
        EOT;
    }

}
