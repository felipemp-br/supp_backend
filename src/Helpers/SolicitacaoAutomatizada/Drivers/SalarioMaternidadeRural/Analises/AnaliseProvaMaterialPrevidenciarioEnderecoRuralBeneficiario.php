<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseProvaMaterialPrevidenciarioEnderecoRuralBeneficiario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseProvaMaterialPrevidenciarioEnderecoRuralBeneficiario extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
        return <<<EOT
            Passo 1: Verifique se consta algum endereço rural como endreço da titular do dossiê. Exemplo de endereços considerados
            como rurais: ZONA RURAL, POVOADO, COLÔNIA ou RURAL.
            Passo 2: Identificadas as informações anteriores, retorne um json com os seguintes campos:
                {'cpf_analisado': preencha com o cpf do titular do dossiê analisado. 
                'nome_analise': preencha com 'AnaliseEnderecoRuralDosPrev'.
                'passou_analise': preencha com booleano true se resposta encontrou um endereço rural no passo 1.
                 Responda com booleano false caso contrário.
                'observacao': null
                'resultado_analise': indique aqui a data de atualização do dossiê e a informação que levou ao resultado true, se for o 
                caso. 
                }
                Faça o json de resposta sem usar a palavra json e sem colocar ```
        EOT;
    }
}
