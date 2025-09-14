<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseInicialEmpresa.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialEmpresa extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
            EMPRESA.
            Passo 1: Se documento for um dossiê sislabra, você deve percorrê-lo e verificar o teor dos campos 'composição 
            societária e administrativa' e 'Empresas em que possui participação societária'.
            Passo 2: Feito isso, considere que a existência de qualquer informação nos respectivos campos que seja diferente 
            de “Nenhum dado encontrado” configura impeditivo. 
            Passo 3: Se encontrado impeditivo, responda o 'passou_analise' com o valor booleano false e indique, no campo 
            'resultado_analise', um resumo contendo os dados das empresas ou participações societárias encontradas.
        EOT;
    }

}
