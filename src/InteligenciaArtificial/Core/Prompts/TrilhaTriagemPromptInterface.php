<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Prompts;

use SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas\TrilhaTriagemInput;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * TrilhaTriagemPromptInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AutoconfigureTag('supp_core.administrativo_backend.inteligencia_artificial.trilha_triagem.prompt')]
interface TrilhaTriagemPromptInterface extends PromptInterface
{
    /**
     * Retorna se o prompt suporta a trilha de triagem.
     *
     * @param TrilhaTriagemInput $input
     * @param array              $triagemData
     *
     * @return bool
     */
    public function suppports(TrilhaTriagemInput $input, array $triagemData = []): bool;

    /**
     * Seta os dados da triagem para passagem de contexto.
     *
     * @param array $triagemData
     *
     * @return self
     */
    public function setDadosFormulario(array $triagemData): self;

    /**
     * Seta o input da trilha de triagem para caso o prompt precise da informação.
     *
     * @param TrilhaTriagemInput $input
     *
     * @return self
     */
    public function setTrilhaTriagemInput(TrilhaTriagemInput $input): self;
}
