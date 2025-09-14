<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseInicialEmpregoPublicoBeneficiario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialEmpregoPublicoBeneficiario extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
        $dataNascimentoCrianca = (new DateTime($dadosTipoSolicitacao['dataNascimentoCrianca']))->format('d/m/Y');

        return <<<EOT
            EMPREGO PÚBLICO.
            Passo 1: Se o documento for um dossiê previdenciário, você deve percorrê-lo e verificar os seguintes dados em 
            relação à beneficiária e ao número de benefício NB {$nb} e, depois, analisar e responder conforme instruções nos 
            passos seguintes:
            A) RELAÇÃO DE PROCESSOS MOVIDOS PELO AUTOR/CPF CONTRA O INSS; 
            B) RESUMO INICIAL – DADOS GERAIS DOS REQUERIMENTOS; 
            C) RELAÇÕES PREVIDENCIÁRIAS; 
            D) COMPETÊNCIAS DETALHADAS. 
            
            Passo 2: Percorra todos os sequenciais 'Seq' do campo 'RELAÇÕES PREVIDENCIÁRIAS' e faça as seguintes 
            análises:
            a) Verifique se há algum vínculo que atenda um dos requisitos abaixo:
            a.1) se há vínculo com 'TIPO DE FILIAÇÃO' com descrição que contenha quaisquer dos seguintes termos: ESTATUTÁRIO, 
            ESTATUTARIO, SERVIDOR, AGENTE PÚBLICO e AGENTE PUBLICO ou
            a.2) se existe, no campo 'ORIGEM VÍNCULO', qualquer um dos seguintes termos: UNIÃO, UNIAO, ESTADO, ESTADUAL, MUNICIPIO, 
            MUNICÍPIO, MUNICIPAL, SECRETARIA, FEDERAL, AUTARQUIA, AUTÁRQUICO, AUTARQUICO, AUTÁRQUICA, AUTARQUICO, FUNDAÇÃO, FUNDACAO; ou
            a.3) verifique se existe, no campo, 'INDICADORES' das 'RELAÇÕES PREVIDENCIÁRIAS' se há o termo PRPPS. 
            
            Passo 3: Se encontrar, no passo anteior, algum emprego público, passe para a análise do período desse emprego, 
            a fim de verificar se ele configura impedimento ou não. Para isso: 
            b) Considere o período que vai de 5 (cinco) anos antes da data {$dataNascimentoCrianca} até a data 
            {$dataNascimentoCrianca}. Verifique se o vínculo de emprego público encontrado coincide, ainda que parcialmente, com o 
            período calculado. Considere como fim do vínculo de emprego público a data indicada em 'DATA FIM' e, apenas se ela não 
            existir, considere a data indicada em 'ÚLTIMA REMUNERAÇÃO' como fim. Verifique, ainda, se a duração desse vínculo, 
            isolado ou somado vínculo de emprego público, supera 120 dias por ano. 
            c) Agora considere o período que vai da {$dataNascimentoCrianca} até 4 meses depois dessa data. 
            Verifique se o vínculo de emprego público coincide, ainda que parcialmente, com esse período. Neste caso, basta possuir 
            o início do vínculo, não precisa haver data fim ou data da última remuneração. 
            
            Passo 4: Encontrada alguma coincidência de período indicada em "b" ou "c" do Passo 3, considere que há impeditivo e
            retorne booleano false em 'passou_analise'. Se não encontrar vínculo de empregado ou agente público ou esse vínculo não 
            conincidir com os períodos espcificados para impedimento, retorne booleano true em 'passou_analise'
        EOT;
    }
}
