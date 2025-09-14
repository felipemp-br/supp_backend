<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseInicialEmpregoPublicoConjuge.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialEmpregoPublicoConjuge extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
            EMPREGO PÚBLICO CÔNJUGE. 
            Se o documento for um dossiê previdenciário, você deve percorrê-lo, analisar as COMPETÊNCIAS DETALHADAS 
            no campo RELAÇÕES PREVIDENCIÁRIAS e executar os seguintes passos: 
            a) Desconsidere a existência de benefício de segurado especial ou benefício assistencial nos 24 (vinte e quatro) meses 
            anteriores a {$dataNascimentoCrianca}. Isso não configura impeditivo. 
            b) Verifique se existe algum vínculo com tipo de filiação empregado ou contribuinte facultativo ou contribuinte 
            individual que coincida, ainda que parcialmente, com o período entre os 24 (vinte e quatro) meses ANTERIORES a 
            {$dataNascimentoCrianca} e {$dataNascimentoCrianca}. Verifique, ainda, se a duração do vínculo, isolado ou somado a outro 
            vínculo dentro do mesmo período supera 120 dias por ano. Considere como fim do vínculo a data indicada em 'DATA FIM' e, 
            apenas se ela não existir, considere a ÚLTIMA REMUNERAÇÃO com fim.
            c) Verifique se existe vínculo com tipo de filiação empregado ou contribuinte facultativo ou contribuinte individual nos 
            04 (quatro) meses POSTERIORES a {$dataNascimentoCrianca}. Neste caso, basta possuir início do vínculo, não precisa haver 
            data fim ou data da última remuneração.
            d) Se o resultado for falso para 'b' e 'c', retorne booleano true em passou_analise. Se der positivo para qualquer situação do 
            item "b" ou “c”, identifique se existe:
            d.1) na ORIGEM DO VÍNCULO os seguintes termos: UNIÃO, UNIAO, ESTADO, ESTADUAL, MUNICIPIO, MUNICÍPIO, MUNICIPAL, 
            SECRETARIA, FEDERAL, AUTARQUIA, AUTÁRQUICO, AUTARQUICO, AUTÁRQUICA, AUTARQUICO, FUNDAÇÃO, FUNDACAO;
            d.2) em INDICADORES os seguintes termos: PRPPS;
            d.3) no TIPO DE FILIAÇÃO os seguintes termos: ESTATUTÁRIO, ESTATUTARIO, SERVIDOR, AGENTE PÚBLICO e AGENTE 
            PUBLICO;
            d.4) amplie a pesquisa para os 05 (cinco) anos anteriores a {$dataNascimentoCrianca};
            d.5) desconsidere como impeditivo casos de vínculo com remuneração ou salário contribuição até 02 (dois) 
            salários-mínimos da época. Pesquise o salário mínimo da época para fazer essa análise.
            Se a busca der positivo em qualquer dos itens "d", considere que há impeditivo, retorne booleano false em passou_analise.
        EOT;
    }

}
