<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseProvaMaterialPrevidenciarioSeguradoEspecialBeneficiario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseProvaMaterialPrevidenciarioSeguradoEspecialBeneficiario extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
        $dataNascimentoCrianca = (new DateTime($dadosTipoSolicitacao['dataNascimentoCrianca']))->format('d/m/Y');

        return <<<EOT
            Passo 1: Verifique no dossiê previdenciário se consta o recebimento de algum benefício como segurada especial
            anterior a {$dataNascimentoCrianca};
            Passo 2: Identificadas as informações anteriores, retorne um json com os seguintes campos:
                'cpf_analisado': preencha com o cpf do titular do dossiê analisado. 
                'nome_analise': preencha com 'AnaliseBenefSeguradaEspecial'.
                'passou_analise': preencha com 'True' se encontrou resposta positiva no passo 1.
                 Responda com 'False' caso contrário.
                'observacao': null
                'resultado_analise': indique aqui a data de atualização do dossiê e a informação que levou ao resultado true, se for o 
                caso. 
                Faça o json de resposta sem usar a palavra json e sem colocar ```
        EOT;
    }
}
