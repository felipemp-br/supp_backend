<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseInicialLitispendenciaBeneficiario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialLitispendenciaBeneficiario extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
            LITISPENDÊNCIA. 
            Passo 1: Se o documento for um dossiê previdenciário, você deve percorrê-lo e verificar os seguintes dados em 
            relação à beneficiária e ao número de benefício NB {$nb} e, depois, analisar e responder conforme instruções nos 
            passos seguintes:
            A) RELAÇÃO DE PROCESSOS MOVIDOS PELO AUTOR/CPF CONTRA O INSS; 
            B) RESUMO INICIAL – DADOS GERAIS DOS REQUERIMENTOS; 
            C) RELAÇÕES PREVIDENCIÁRIAS; 
            D) COMPETÊNCIAS DETALHADAS.
            Passo 2: Neste passo, você deve identificar a existência de algum 'PROCESSO JUDICIAL' no 
            campo 'RELAÇÃO DE PROCESSOS MOVIDOS PELO AUTOR/CPF CONTRA O INSS'. A análise deve dar booleano false se, nesse campo, 
            existir algum processo cuja data indicada no campo 'AJUIZAMENTO' seja igual ou posterior à data 
            {$dataNascimentoCrianca} e, cumulativamente, o assunto desse mesmo processo seja 'SALÁRIO-MATERNIDADE'.
        EOT;
    }

}
