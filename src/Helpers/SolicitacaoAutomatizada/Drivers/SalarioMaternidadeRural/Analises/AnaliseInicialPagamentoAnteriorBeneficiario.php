<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseInicialPagamentoAnteriorBeneficiario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialPagamentoAnteriorBeneficiario extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
            PAGAMENTO ANTERIOR.
            Passo 1: Se o documento for um dossiê previdenciário, você deve percorrê-lo e verificar os seguintes dados em 
            relação à beneficiária e ao número de benefício NB {$nb} e, depois, analisar e responder conforme instruções nos 
            passos seguintes:
            A) RELAÇÃO DE PROCESSOS MOVIDOS PELO AUTOR/CPF CONTRA O INSS; 
            B) RESUMO INICIAL – DADOS GERAIS DOS REQUERIMENTOS; 
            C) RELAÇÕES PREVIDENCIÁRIAS; 
            D) COMPETÊNCIAS DETALHADAS.
            Passo 2: Você deve identificar no campo 'Dados do Benefício', dentro de 'COMPETÊNCIAS DETALHADAS', 
            se consta algum '80 - AUXILIO SALARIO MATERNIDADE' em que, necessariamente, haja uma data indicada em 'Data Início (DIB)' 
            e ela esteja dentro do intervalo entre os 28 dias anteriores a data de nascimento {$dataNascimentoCrianca} e a própria data 
            de nascimento {$dataNascimentoCrianca}. Não considere impeditivo benefício com o mesmo NB {$nb} que conste no campo Situação 
            dos Dados do Benefício como INDEFERIDO.
        EOT;
    }

}
