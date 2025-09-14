<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseProvaMaterialDossieLabraBeneficiario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseProvaMaterialDossieLabraBeneficiario extends AbstractAnaliseIA implements AnalisaDossiesInterface
{
    /**
     * Retorna a sigla dos tipos de dossies suportados.
     *
     * @return string[]
     */
    protected function getSiglasTipoDossiesSuportados(): array
    {
        return [
            'DOSLABRA'
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
            Passo 1: Verifique, no campo 'Endereços Encontrados' se consta algum endereço rural como endreço da titular do dossiê 
            com data de 'Atualização' anterior a {$dataNascimentoCrianca}. Exemplo de endereços considerados como rurais: ZONA RURAL, 
            POVOADO, COLÔNIA ou RURAL.
            Passo 2: Identificada a resposta para o passo 1, retorne um json com os seguintes campos:
                'cpf_analisado': preencha com o cpf do titular do dossiê analisado. 
                'nome_analise': preencha com 'AnaliseEnderecoRuralSislabra'.
                'passou_analise': preencha com booleano true se identificar um endereço rural no passo 1 e responda com booleano false caso 
                contrário.
                'observacao': null
                'resultado_analise': indique aqui a data de atualização do dossiê e a informação que levou ao resultado true, se for o 
                caso. 
        EOT;
    }
}
