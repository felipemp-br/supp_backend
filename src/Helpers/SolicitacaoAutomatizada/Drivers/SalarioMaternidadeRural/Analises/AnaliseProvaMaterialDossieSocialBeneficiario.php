<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseProvaMaterialDossieSocialBeneficiario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseProvaMaterialDossieSocialBeneficiario extends AbstractAnaliseIA implements AnalisaDossiesInterface
{
    /**
     * Retorna a sigla dos tipos de dossies suportados.
     *
     * @return string[]
     */
    protected function getSiglasTipoDossiesSuportados(): array
    {
        return [
            'DOSOC'
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
        $dataNascimentoCrianca = (new DateTime($dadosTipoSolicitacao['dataNascimentoCrianca']))->format('d/m/Y');

        return <<<EOT
            Passo 1: Verifique se consta algum endereço rural como endreço do titular do dossiê. Exemplo de endereços considerados
            como rurais: ZONA RURAL, POVOADO, COLÔNIA ou RURAL. 
            Passo 2: Identificada resposta para o passo 1, retorne um json com os seguintes campos:
                'cpf_analisado': preencha com o cpf indicado no documento analisado. 
                'nome_analise': preencha com 'AnaliseEnderecoRuralCadunico'
                'passou_analise': preencha com booleano true se identificar endereço rural no passo 1 e responda com booleano false caso 
                contrário
                'observacao': Para preencher este campo, primeiro localize a data de atualização do dossiê, que estará no início do 
                documento, no seguinte formato *Base de dados atualizada em dd/mm/aaaa hh:mm:ss. Depois, verifique se essa data está 
                no intervalo de tempo de entre 12 (doze) meses anteriores a data {$dataNascimentoCrianca} e a data {$dataNascimentoCrianca}, 
                caso positivo, preencha com 'ENDEREÇO RECENTE'. Caso a data de atualização do dossiê seja anterior a esse período, 
                preencha com 'ENDEREÇO ANTIGO';
                'resultado_analise': preencha com a data de atualização do dossiê e a informação que levou ao resultado true, se for o 
                caso. 
                Faça o json de resposta sem usar a palavra json e sem colocar ```
        EOT;
    }
}
