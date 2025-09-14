<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseInicialVeiculo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialVeiculo extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
            VEÍCULOS.
            Passo 1: Se o documento for um dossiê sislabra, você deve percorrê-lo e verificar o teor do campo 'Veículos 
            Vinculados'.
            Passo 2: Feito isso, considere a regra geral de que a existência de qualquer informação no respectivo campo que seja 
            diferente de “Nenhum dado encontrado” configura impeditivo.
            Passo 3: Faça a seguinte análise de exceção: 
            a) Exclua dos veículos encontrados até 02 (duas) motocicletas/motos, de menor valor. Se encontradas até duas motos 
            coloque no campo observação 'VEÍCULO SIMPLES';
            b) Exclua dos veículos encontrados os seguintes: Chevette; Opala; Monza; Brasília; Fusca; Corcel; Fiat 147; Del Rey; 
            Belina; Caravan; Kombi; Variant; Kadett; Escort; Santana; Corsa; ou Celta. Se encontrado qualquer um desses veículos 
            coloque no campo observação 'VEÍCULO SIMPLES';
            c) Feitas as exclusões em "a" e "b", caso reste algum veículo, considere que há impedimento. Coloque o valor booleano false em
            passou_analise e deixe o campo 'observação' com valor nulo.
            d) Feitas as exclusões em "a" e "b", caso não exista nenhum veículo restante, considere que não há um impedimento. 
            Coloque o valor booleano true em passou_analise e mantenha o valor do campo 'observação' como definido anteriormente.
        EOT;
    }

}
