<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseProvaMaterialPrevidenciarioSeguradoEspecialConjuge.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseProvaMaterialPrevidenciarioSeguradoEspecialConjuge extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
            Passo 1: Verifique se consta no dossiê previdenciário se houve o recebimento de algum benefício como segurado especial
            no período entre 12 meses anteriores a {$dataNascimentoCrianca} e {$dataNascimentoCrianca};
            Passo 2: Verifique se houve algum benefício deferido como segurado especial no período entre {$dataNascimentoCrianca} e 12 
            (doze) meses posteriores a {$dataNascimentoCrianca};
            Passo 3: Identificadas as informações anteriores, retorne um json com os seguintes campos:
                'cpf_analisado': preencha com o cpf do titular do dossiê analisado. 
                'nome_analise': preencha com 'AnaliseSeguradoEspecialConjuge'.
                'passou_analise': preencha com booleano true se identificar resposta postiva no passo 1 ou no passo 2.
                 Responda com booleano false caso contrário.
                'observacao': null
                'resultado_analise': indique aqui com a data de atualização do dossiê e a informação que levou ao resultado true, 
                se for o caso. 
                Faça o json de resposta sem usar a palavra json e sem colocar ```
        EOT;
    }
}
