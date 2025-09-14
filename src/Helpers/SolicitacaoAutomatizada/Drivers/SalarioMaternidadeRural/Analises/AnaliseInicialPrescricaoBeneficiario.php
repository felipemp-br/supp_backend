<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Drivers\SalarioMaternidadeRural\Analises;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises\AnalisaDossiesInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * AnaliseInicialPrescricaoBeneficiario.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AnaliseInicialPrescricaoBeneficiario extends AbstractAnaliseIA implements AnalisaDossiesInterface
{
    private ProcessoResource $processoResource;

    #[Required]
    public function setDependencies(
        ProcessoResource $processoResource
    ): void {
        $this->processoResource = $processoResource;
    }
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
        $processo = $this->processoResource->findOneBy(['NUP' => $dadosTipoSolicitacao['nup']]);
        $dataSolicitacao = $processo->getCriadoEm()->format('d/m/Y');

        return <<<EOT
            PRESCRIÇÃO.
            Passo 1: Se o documento for um dossiê previdenciário, você deve percorrê-lo e verificar os seguintes dados em 
            relação à beneficiária e ao número de benefício NB {$nb} e, depois, analisar e responder conforme instruções nos 
            passos seguintes:
            A) RELAÇÃO DE PROCESSOS MOVIDOS PELO AUTOR/CPF CONTRA O INSS; 
            B) RESUMO INICIAL – DADOS GERAIS DOS REQUERIMENTOS; 
            C) RELAÇÕES PREVIDENCIÁRIAS; 
            D) COMPETÊNCIAS DETALHADAS.
            Passo 2: Para verificar se houve prescrição, você deve: 
            (a) Calcule o número de anos, meses e dias decorrido entre a data {$dataNascimentoCrianca} e a data {$dataSolicitacao}; 
            (d) Verifique se o o período encontrado é igual ou superior a 05 (cinco) anos. Se for, responda que há impeditivo caracterizado 
            pela PRESCRIÇÃO. Se menor de 5 (cinco) anos, não há impeditivo da prescrição.
        EOT;
    }

}
