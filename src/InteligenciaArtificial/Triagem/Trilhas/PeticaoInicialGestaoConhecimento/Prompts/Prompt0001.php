<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\PeticaoInicialGestaoConhecimento\Prompts;

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
        return json_encode(
            [
                'peticao_inicial_indicador' => <<<EOT
                    insira aqui o valor booleano true se o texto for de um documento do tipo 'PETIÇÃO INICIAL' e, caso contrário (else), insira o valor booleano false.
                    A petição inicial é o documento que inicia o processo e contém o nome da ação judicial, os nomes do autor e do réu, a qualificação das partes, os fatos, os fundamentos jurídicos do pedido, os pedidos, as provas e o valor da causa
                EOT
            ],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
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
