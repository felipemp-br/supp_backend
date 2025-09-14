<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseInicialAusenciaRequerimentoAdministrativo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialAusenciaRequerimentoAdministrativo extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
        return <<<EOT
            AUSÊNCIA DE REQUERIMENTO ADMINISTRATIVO.
            Passo 1: Se o documento for um dossiê previdenciário, você deve percorrê-lo e verificar os seguintes dados em 
            relação à beneficiária e ao número de benefício NB {$nb} e, depois, analisar e responder conforme instruções nos 
            passos seguintes:
            A) RELAÇÃO DE PROCESSOS MOVIDOS PELO AUTOR/CPF CONTRA O INSS; 
            B) RESUMO INICIAL – DADOS GERAIS DOS REQUERIMENTOS; 
            C) RELAÇÕES PREVIDENCIÁRIAS; 
            D) COMPETÊNCIAS DETALHADAS.
            Passo 2: AUSÊNCIA DE REQUERIMENTO ADMINISTRATIVO. Encontrar o 
            indeferimento de um pedido administrativo é requisito para dar booleano true na análise. Então, identifique se existe 
            requerimento administrativo indeferido com base no NB fornecido pela requerente e se o benefício requerido se refere 
            ao salário-maternidade e se foi indeferido. Esse dado estará em RESUMO INICIAL – DADOS GERAIS DOS REQUERIMENTOS, 
            no campo NB, com o seguinte formato xxx.xxx.xxx-x, que deve ser comparado com {$nb} desconsiderando-se caracteres 
            diferentes de dígitos numéricos. Haverá a indicação '80 - SALARIO MATERNIDADE', caso se refira a salário maternidade. 
            E deve haver o campo 'Situação' com o valor 'INDEFERIDO'. A ausência de pedido administrativo indeferido para o {$nb} 
            configura impeditivo e a resposta deve ser booleano false.
        EOT;
    }

}
