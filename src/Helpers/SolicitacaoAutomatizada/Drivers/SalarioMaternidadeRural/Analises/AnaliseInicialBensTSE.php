<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseInicialBensTSE.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialBensTSE extends AbstractAnaliseIA implements AnalisaDossiesInterface
{
    /**
     * Retorna a sigla dos tipos de dossies suportados.
     *
     * @return string[]
     */
    protected function getSiglasTipoDossiesSuportados(): array
    {
        return [
            'DOSLABRA',
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
            BENS TSE.
            Passo 1: Se documento for um dossiê sislabra, você deve percorrê-lo e verificar o teor do campo 'Bens Declarados 
            ao TSE (Candidato)'.
            Passo 2: Feito isso, considere que a existência de qualquer informação nos respectivos campos que seja diferente 
            de “Nenhum dado encontrado” configura impeditivo. 
            Passo 3: Se encontrado impeditivo, responda o 'passou_analise' com o valor booleano false e indique, no campo 
            'resultado_analise', um resumo contendo os dados dos impeditivos encontrados.
        EOT;
    }

}
