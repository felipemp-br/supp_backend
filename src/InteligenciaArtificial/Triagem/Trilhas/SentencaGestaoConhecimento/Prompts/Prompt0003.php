<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaGestaoConhecimento\Prompts;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\BaseTrilhaTriagemPrompt;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaGestaoConhecimento\TrilhaSentencaGestaoConhecimento;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInput;

/**
 * Prompt0003.php
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Prompt0003 extends BaseTrilhaTriagemPrompt
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
                'tipo_documento_correto' => <<<EOT
                    insira aqui a classificação correta do tipo de documento, escolhendo apenas um dentre os tipos de peça processual listados abaixo:
                    1) 'AGRAVO DE INSTRUMENTO';
                    2) 'APELAÇÃO';
                    3) 'CONTESTAÇÃO';
                    4) 'CONTRARRAZÕES';
                    5) 'EMBARGOS DE DECLARAÇÃO';
                    6) 'LAUDO';
                    7) 'RECURSO INOMINADO';
                    8) 'SENTENÇA';
                    9) 'OUTROS DOCUMENTOS', apenas se não for possível classificar o documento nos oito tipos acima.
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
        $data = $triagemData[TrilhaSentencaGestaoConhecimento::getSiglaFormulario()];
        return isset($data['sentenca_indicador'])
            && filter_var($data['sentenca_indicador'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === false;
    }
}
