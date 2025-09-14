<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaGestaoConhecimento;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Attributes\Trilha;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\BaseTrilhaTriagem;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaGestaoConhecimento\Prompts\Prompt0001;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaGestaoConhecimento\Prompts\Prompt0002;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\SentencaGestaoConhecimento\Prompts\Prompt0003;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInput;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInterface;

/**
 * TrilhaSentencaGestaoConhecimento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Trilha(
    nome: 'Trilha de Gestão de Conhecimento de Sentença',
    prompts: [
        Prompt0001::class => [
            Prompt0002::class,
            Prompt0003::class,
        ]
    ],
    dependsOn: []
)]
class TrilhaSentencaGestaoConhecimento extends BaseTrilhaTriagem implements TrilhaTriagemInterface
{
    public const string CONFIG_MODULO_KEY = 'ADMINISTRATIVO_TRILHA_GESTAO_CONHECIMENTO_SENTENCA';
    public array $config = [
        'ativo' => false,
        'tipos_documentos_suportados' => [''],
    ];

    /**
     * Função que é chamada após o constructor.
     * Serve para ser sobrescrita e realizar operações iniciais.
     *
     * @return void
     */
    protected function initialize(): void
    {
        if ($this->suppParameterBag->hasBySigla(self::CONFIG_MODULO_KEY)) {
            $this->config = $this->suppParameterBag->get(self::CONFIG_MODULO_KEY);
        }
    }

    /**
     * Indica se a trilha está ativa.
     *
     * @return bool
     */
    public function isAtiva(): bool
    {
        return $this->config['ativo'];
    }

    /**
     * Retorna se a trilha suporta o tipo de documento.
     *
     * @param string $siglaTipoDocumento
     *
     * @return bool
     */
    public function suportaTipoDocumento(string $siglaTipoDocumento): bool
    {
        return empty($this->config['tipos_documentos_suportados'])
            || in_array(
                $siglaTipoDocumento,
                $this->config['tipos_documentos_suportados']
            );
    }

    /**
     * Retorna a sigla do formulário da trilha de triagem.
     *
     * @return string
     */
    public static function getSiglaFormulario(): string
    {
        return 'ia_gestao_conhecimento_sentenca_1';
    }

    /**
     * Verifica se a trilha suporta o input.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return bool
     */
    public function supports(TrilhaTriagemInput $input): bool
    {
        if ($this->isAtiva()) {
            $siglaTipoDoc = $input->documento?->getDocumentoIAMetadata()?->getTipoDocumentoPredito()?->getSigla()
                ?? $input->documento->getTipoDocumento()->getSigla();

            return $this->suportaTipoDocumento($siglaTipoDoc);
        }

        return false;
    }

    /**
     * Indica se a trilha deve manter o contexto entre os prompts.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return bool
     */
    protected function keepContext(TrilhaTriagemInput $input): bool
    {
        return false;
    }
}
