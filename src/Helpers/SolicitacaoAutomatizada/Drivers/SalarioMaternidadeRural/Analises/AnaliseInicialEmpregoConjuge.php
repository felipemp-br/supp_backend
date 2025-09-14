<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;

/**
 * AnaliseInicialEmpregoConjuge.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialEmpregoConjuge extends AbstractAnaliseIA implements AnalisaDossiesInterface
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
            EMPREGO CÔNJUGE. 
            Objetivo: Analisar os vínculos previdenciários do cônjuge da requerente para determinar a elegibilidade ao benefício, 
            considerando os períodos anteriores e posteriores ao nascimento da criança, com foco em vínculos de emprego público.
            
            Instruções:
            
            Entrada de Dados:
            
            Data de Nascimento da Criança = {$dataNascimentoCrianca}.
            Dossiê Previdenciário do Cônjuge = documento demarcado por @@@
            
            Análise dos Vínculos Previdenciários:
            
            a) Exclusão de Benefícios:
            Excluir se o cônjuge recebe o benefício de segurado especial ou benefício assistencial nos 24 (vinte e quatro) meses 
            anteriores ao nascimento da criança {$dataNascimentoCrianca}.
            
            b) Período de 24 Meses Anteriores ao Nascimento da Criança:
            Identificar todos os vínculos de emprego, contribuições como contribuinte facultativo, contribuições como contribuinte 
            individual e benefícios previdenciários do cônjuge da requerente nos 24 meses anteriores à data de nascimento da criança 
            {$dataNascimentoCrianca}.
            
            Verificar se cada vínculo isolado ou somado a outro vínculo supera 120 dias por ano.
            Termo Final da Conta: Considerar a data do fim do vínculo.
            Sem Data do Fim: Considerar a data da última remuneração.
            
            c) Período de 4 Meses Posteriores ao Nascimento da Criança:
            Identificar todos os vínculos de emprego, contribuições como contribuinte facultativo, contribuições como contribuinte 
            individual e benefícios previdenciários do cônjuge da requerente nos 4 meses posteriores à data de nascimento da criança 
            {$dataNascimentoCrianca}.
            Basta possuir início do vínculo, não é necessário considerar a data do fim ou a data da última remuneração.
            
            Saída de Dados:
            
            Se o resultado der positivo em "b" ou "c", preencha os campos do JSON de resposta da seguinte forma:
            passou_analise: booleano false
            
            Se a remuneração/salário de contribuição do cônjuge até 02 (dois) salários-mínimos da época, colocar na observação: 
            'ATÉ DOIS SAL MIN';
        EOT;
    }

}
