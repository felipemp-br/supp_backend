<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaGestaoConhecimento\Prompts;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\BaseTrilhaTriagemPrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInput;

/**
 * Prompt0001.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Prompt0001 extends BaseTrilhaTriagemPrompt
{
    /**
     * Retorna o texto a ser enviado para a IA.
     *
     * @return string
     */
    public function getText(): string
    {
        //@codingStandardsIgnoreStart
        return json_encode(
            [
                'sentenca_indicador' => <<<EOT
                    insira aqui o valor booleano true se o texto for de um documento do tipo 'SENTENÇA' e, caso contrário (else), insira o valor booleano false. 
                    A sentença é o documento com o julgamento do processo pelo juiz federal ou juiz de direito no primeiro grau
                EOT
            ],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
        //@codingStandardsIgnoreEnd
    }

    /**
     * Retorna se o prompt suporta a trilha de triagem.
     *
     * @param TrilhaTriagemInput $input
     * @param array              $triagemData
     * @return bool
     */
    public function suppports(TrilhaTriagemInput $input, array $triagemData = []): bool
    {
        return true;
    }
}
