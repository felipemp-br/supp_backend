<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Triagem\Trilhas;

use SuppCore\AdministrativoBackend\Entity\Formulario;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\ClientRateLimitExeededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\EmptyDocumentContentException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificalException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\InteligenciaArtificialRequestErrorException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\MaximumInputTokensExceededException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\TokenBalanceInsufficientException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnauthorizedDocumentAccessException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedComponenteDigitalMimeTypeException;
use SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Exceptions\UnsupportedUriException;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * TrilhaTriagemInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AutoconfigureTag('supp_core.administrativo_backend.inteligencia_artificial.trilha_triagem.trilha')]
interface TrilhaTriagemInterface
{
    /**
     * Verifica se a triagem suporta o input fornecido.
     *
     * @param TrilhaTriagemInput $input
     * @return bool
     */
    public function supports(TrilhaTriagemInput $input): bool;

    /**
     * Executa a trilha.
     *
     * @param TrilhaTriagemInput $input
     * @param string             $transactionId
     *
     * @return void
     *
     * @throws ClientRateLimitExeededException
     * @throws EmptyDocumentContentException
     * @throws InteligenciaArtificalException
     * @throws InteligenciaArtificialRequestErrorException
     * @throws MaximumInputTokensExceededException
     * @throws TokenBalanceInsufficientException
     * @throws UnauthorizedDocumentAccessException
     * @throws UnsupportedComponenteDigitalMimeTypeException
     * @throws UnsupportedUriException
     */
    public function handle(TrilhaTriagemInput $input, string $transactionId): void;

    /**
     * Retorna o formulário da trilha de triagem.
     *
     * @return Formulario
     */
    public function getFormulario(): Formulario;

    /**
     * Retorna o nome da trilha.
     *
     * @return string
     */
    public function getNomeTrilha(): string;

    /**
     * Retorna se a trilha suporta o tipo de documento.
     *
     * @param string $siglaTipoDocumento
     *
     * @return bool
     */
    public function suportaTipoDocumento(string $siglaTipoDocumento): bool;

    /**
     * Indica se a trilha está ativa.
     *
     * @return bool
     */
    public function isAtiva(): bool;

    /**
     * Retorna a lista de trilhas de triagem que precisam ser executadas antes da trilha atual.
     *
     * @return string[]
     */
    public function getDeppendsOn(): array;
}
