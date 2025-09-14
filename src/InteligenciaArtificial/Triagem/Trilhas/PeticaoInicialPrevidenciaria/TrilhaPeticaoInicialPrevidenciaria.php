<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\PeticaoInicialPrevidenciaria;

use SuppCore\AdministrativoBackend\Entity\Interessado;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Attributes\Trilha;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\BaseTrilhaTriagem;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\PeticaoInicialGestaoConhecimento\TrilhaPeticaoInicialGestaoConhecimento;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\PeticaoInicialPrevidenciaria\Prompts\Prompt0001;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInput;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInterface;

/**
 * TrilhaTriagemGestaoConhecimento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Trilha(
    nome: 'Trilha de Petição Inicial Previdenciaria',
    prompts: [
        Prompt0001::class,
    ],
    dependsOn: [
        TrilhaPeticaoInicialGestaoConhecimento::class,
    ]
)]
class TrilhaPeticaoInicialPrevidenciaria extends BaseTrilhaTriagem implements TrilhaTriagemInterface
{
    public const CONFIG_MODULO_KEY = 'ADMINISTRATIVO_TRILHA_PETICAO_INICIAL_PREVIDENCIARIA';
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
        return 'ia_petini_previdenciaria_1';
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
            if ($this->suportaTipoDocumento($siglaTipoDoc)) {
                if (empty($this->config['ramos_direito'])) {
                    return true;
                }
                $dadosFormulario = null;
                $formularioGestaoConhecimento = $this->formularioRepository
                    ->findOneBy(['sigla' => TrilhaPeticaoInicialGestaoConhecimento::getSiglaFormulario()]);
                if ($formularioGestaoConhecimento) {
                    $dadosFormulario = $this->dadosFormularioResource->findOneBy([
                        'documento' => $input->documento,
                        'formulario' => $formularioGestaoConhecimento,
                    ]);
                }

                if ($dadosFormulario) {
                    $ramosDireito = array_map(
                        fn ($ramo) => mb_strtolower($ramo),
                        $this->config['ramos_direito']
                    );
                    $data = json_decode($dadosFormulario->getDataValue(), true) ?? [];
                    if (
                        isset($data['ramo_direito'])
                        && in_array(mb_strtolower($data['ramo_direito']), $ramosDireito)
                    ) {
                        /** @var Interessado[] $interessados */
                        $interessados = $input->documento
                            ?->getJuntadaAtual()
                            ?->getVolume()
                            ?->getProcesso()
                            ?->getInteressados()
                            ?->toArray() ?? [];
                        foreach ($interessados as $interessado) {
                            $modalidadeInteressado = mb_strtolower(
                                $interessado->getModalidadeInteressado()->getValor()
                            );
                            $nomeInteressado = mb_strtolower($interessado->getPessoa()->getNome());
                            if ($modalidadeInteressado === mb_strtolower($this->config['nome_modalidade_interessado'])
                                && $nomeInteressado === mb_strtolower($this->config['nome_interessado'])
                            ) {
                                return true;
                            }
                        }
                    }
                }
            }
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
