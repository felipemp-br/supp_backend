<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\EARQ;

use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;

/**
 * Interface para registro de eventos de metadados do EARQ em channel específico
 */
interface EARQEventoPreservacaoLoggerInterface
{

    /**
     * Registra log do evento de preservação (Compressão)
     * EPRES1. Registro da compressão de componentes digitais.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES1Compressao(RestDto|ComponenteDigital $componenteDigital): self;

    /**
     * Registra log do evento de preservação (Compressão)
     * EPRES1. Registro da descompressão de componentes digitais.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES1Descompressao(RestDto|ComponenteDigital $componenteDigital): self;

    /**
     * Registra log do evento de preservação (Decifração)
     * EPRES2. Registro da decifração de componentes digitais criptografados.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES2Decifracao(RestDto|ComponenteDigital $componenteDigital): self;

    /**
     * Registra log do evento de preservação (Validação de assinatura digital)
     * EPRES3. Registro  da  validação  da  assinatura  digital válida  de  um componente digital.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES3AssinaturaValida(RestDto|ComponenteDigital $componenteDigital): self;

    /**
     * Registra log do evento de preservação (Validação de assinatura digital)
     * EPRES3. Registro  da  validação  da  assinatura  digital inválida  de  um componente digital.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES3AssinaturaInvalida(RestDto|ComponenteDigital $componenteDigital): self;

    /**
     * Registra log do evento de preservação (Cálculo hash)
     * EPRES4. Registro do cálculo hash do arquivo, a ser armazenado no elemento de metadado do  componente  digital
     * earq.componente.fixidade,  que  serve  para  apoiar  a verificação de fixidade ao longo do tempo.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @param string $algoritmo
     * @return self
     */
    public function logEPRES4Checksum(RestDto|ComponenteDigital $componenteDigital, string $algoritmo): self;

    /**
     * Registra log do evento de preservação (Verificação de fixidade)
     * EPRES5. Registro da verificação da fixidade valida do componente digital.
     * @param string $recurso
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES5FixidadeValida(RestDto|ComponenteDigital $componenteDigital, string $recurso): self;

    /**
     * Registra log do evento de preservação (Verificação de fixidade)
     * EPRES5. Registro da verificação da fixidade invalida do componente digital.
     * @param string $recurso
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES5FixidadeInvalida(RestDto|ComponenteDigital $componenteDigital, string $recurso): self;

    /**
     * Registra log do evento de preservação (Migração)
     * EPRES6. Registro de procedimento de migração do componente digital.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES6Migracao(RestDto|ComponenteDigital $componenteDigital): self;

    /**
     * Registra log do evento de preservação (Replicação)
     * EPRES7. Registro de procedimento de replicação do componente digital.
     * @param RestDto|ComponenteDigital $componenteDigitalOrigem
     * @param RestDto|ComponenteDigital $componenteDigitalDestino
     * @return self
     */
    public function logEPRES7Replicacao(
        RestDto|ComponenteDigital $componenteDigitalOrigem,
        RestDto|ComponenteDigital $componenteDigitalDestino): self;

    /**
     * Registra log do evento de preservação (Verificação de vírus)
     * EPRES8. Registro de verificação de vírus no componente digital.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @param bool $secure
     * @param string|null $output
     * @return self
     */
    public function logEPRES8VerificacaoVirus(
        RestDto|ComponenteDigital $componenteDigital,
        bool $secure,
        ?string $output = null
    ): self;

    /**
     * Registra log do evento de preservação (Validação)
     * EPRES9. Registro da validação do documento.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES9Validado(RestDto|ComponenteDigital $componenteDigital): self;

}
