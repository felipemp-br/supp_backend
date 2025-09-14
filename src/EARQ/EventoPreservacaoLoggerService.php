<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\EARQ;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;

/**
 * Serviço de log de eventos de preservação de componentes digitais do E-Arq do Arquivo Nacional
 */
class EventoPreservacaoLoggerService implements EARQEventoPreservacaoLoggerInterface
{

    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * Registra log do evento de preservação (Compressão)
     * EPRES1. Registro da compressão de componentes digitais.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES1Compressao(RestDto|ComponenteDigital $componenteDigital): self
    {
        $this->logger->info(
            sprintf(
                '%s. Comprimindo componente digital %s',
                'EPRES1',
                $componenteDigital->getUuid()
            ),
            [
                'componente_digital' => $componenteDigital->getUuid(),
                'evento_preservacao' => 'EPRES1',
                'resultado' => 'Compressão'
            ]
        );

        return $this;
    }

    /**
     * Registra log do evento de preservação (Compressão)
     * EPRES1. Registro da descompressão de componentes digitais.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES1Descompressao(RestDto|ComponenteDigital $componenteDigital): self
    {
        $this->logger->info(
            sprintf(
                '%s. Descomprimindo componente digital %s',
                'EPRES1',
                $componenteDigital->getUuid()
            ),
            [
                'componente_digital' => $componenteDigital->getUuid(),
                'evento_preservacao' => 'EPRES1',
                'resultado' => 'Descompressão'
            ]
        );

        return $this;
    }

    /**
     * Registra log do evento de preservação (Decifração)
     * EPRES2. Registro da decifração de componentes digitais criptografados.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES2Decifracao(RestDto|ComponenteDigital $componenteDigital): self
    {
        $this->logger->info(
            sprintf(
                '%s. Desifrando componente digital %s',
                'EPRES2',
                $componenteDigital->getUuid()
            ),
            [
                'componente_digital' => $componenteDigital->getUuid(),
                'evento_preservacao' => 'EPRES2',
                'resultado' => 'Desifração'
            ]
        );

        return $this;
    }

    /**
     * Registra log do evento de preservação (Validação de assinatura digital)
     * EPRES3. Registro  da  validação  da  assinatura  digital válida  de  um componente digital.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES3AssinaturaValida(RestDto|ComponenteDigital $componenteDigital): self
    {
        $this->logger->info(
            sprintf(
                '%s. Verificação de assinatura válida do componente digital %s',
                'EPRES3',
                $componenteDigital->getUuid()
            ),
            [
                'componente_digital' => $componenteDigital->getUuid(),
                'evento_preservacao' => 'EPRES3',
                'resultado' => 'Assinatura Válida'
            ]
        );

        return $this;
    }

    /**
     * Registra log do evento de preservação (Validação de assinatura digital)
     * EPRES3. Registro  da  validação  da  assinatura  digital inválida  de  um componente digital.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES3AssinaturaInvalida(RestDto|ComponenteDigital $componenteDigital): self
    {
        $this->logger->info(
            sprintf(
                '%s. Verificação de assinatura inválida do componente digital %s',
                'EPRES3',
                $componenteDigital->getUuid()
            ),
            [
                'componente_digital' => $componenteDigital->getUuid(),
                'evento_preservacao' => 'EPRES3',
                'resultado' => 'Assinatura Inálida'
            ]
        );

        return $this;
    }

    /**
     * Registra log do evento de preservação (Cálculo hash)
     * EPRES4. Registro do cálculo hash do arquivo, a ser armazenado no elemento de metadado do  componente  digital
     * earq.componente.fixidade,  que  serve  para  apoiar  a verificação de fixidade ao longo do tempo.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @param string $algoritmo
     * @return self
     */
    public function logEPRES4Checksum(RestDto|ComponenteDigital $componenteDigital, string $algoritmo): self
    {
        $this->logger->info(
            sprintf(
                '%s. Preservando componente digital %s com algoritmo %s e verificação checksum',
                'EPRES4',
                $componenteDigital->getUuid(),
                $algoritmo
            ),
            [
                'componente_digital' => $componenteDigital->getUuid(),
                'evento_preservacao' => 'EPRES4',
                'recurso' => 'checksum',
                'algoritmo' => $algoritmo,
                'hash' => $componenteDigital->getHash(),
                'resultado' => 'Cálculo Hash'
            ]
        );

        return $this;
    }

    /**
     * Registra log do evento de preservação (Verificação de fixidade)
     * EPRES5. Registro da verificação da fixidade valida do componente digital.
     * @param string $recurso
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES5FixidadeValida(RestDto|ComponenteDigital $componenteDigital, string $recurso): self
    {
        $this->logger->info(
            sprintf(
                '%s. Fixidade do componente digital %s verificada com sucesso com o recurso %s',
                'EPRES5',
                $componenteDigital->getUuid(),
                $recurso
            ),
            [
                'componente_digital' => $componenteDigital->getUuid(),
                'evento_preservacao' => 'EPRES5',
                'recurso' => $recurso,
                'resultado' => 'Fixidade Válida'
            ]
        );

        return $this;
    }

    /**
     * Registra log do evento de preservação (Verificação de fixidade)
     * EPRES5. Registro da verificação da fixidade invalida do componente digital.
     * @param string $recurso
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES5FixidadeInvalida(RestDto|ComponenteDigital $componenteDigital, string $recurso): self
    {
        $this->logger->info(
            sprintf(
                '%s. Falha ao verificar fixidade do componente digital %s com o recurso %s',
                'EPRES5',
                $componenteDigital->getUuid(),
                $recurso
            ),
            [
                'componente_digital' => $componenteDigital->getUuid(),
                'evento_preservacao' => 'EPRES5',
                'recurso' => $recurso,
                'resultado' => 'Fixidade Inválida'
            ]
        );

        return $this;
    }

    /**
     * Registra log do evento de preservação (Migração)
     * EPRES6. Registro de procedimento de migração do componente digital.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES6Migracao(RestDto|ComponenteDigital $componenteDigital): self
    {
        $this->logger->info(
            sprintf(
                '%s. Migrando do componente digital %s',
                'EPRES6',
                $componenteDigital->getUuid()
            ),
            [
                'componente_digital' => $componenteDigital->getUuid(),
                'evento_preservacao' => 'EPRES6',
                'resultado' => 'Migração'
            ]
        );

        return $this;
    }

    /**
     * Registra log do evento de preservação (Replicação)
     * EPRES7. Registro de procedimento de replicação do componente digital.
     * @param RestDto|ComponenteDigital $componenteDigitalOrigem
     * @param RestDto|ComponenteDigital $componenteDigitalDestino
     * @return self
     */
    public function logEPRES7Replicacao(
        RestDto|ComponenteDigital $componenteDigitalOrigem,
        RestDto|ComponenteDigital $componenteDigitalDestino
    ): self
    {
        $this->logger->info(
            sprintf(
                '%s. Replicando componente digital %s',
                'EPRES7',
                $componenteDigitalOrigem->getUuid()
            ),
            [
                'componente_digital' => $componenteDigitalOrigem->getUuid(),
                'evento_preservacao' => 'EPRES7',
                'copia' => $componenteDigitalDestino->getUuid(),
                'resultado' => 'Replicação'
            ]
        );

        return $this;
    }

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
    ): self
    {
        $this->logger->info(
            sprintf(
                '%s. %s no componente digital %s',
                'EPRES8',
                (!$secure ? 'Foi detectado vírus' : 'Não foi detectado vírus'),
                $componenteDigital->getUuid(),
            ),
            [
                'componente_digital' => $componenteDigital->getUuid(),
                'evento_preservacao' => 'EPRES8',
                'infectado' => $secure === false,
                'detalhe' => $output,
                'resultado' => 'EPRES8'
            ]
        );

        return $this;
    }

    /**
     * Registra log do evento de preservação (Validação)
     * EPRES9. Registro da validação do documento.
     * @param RestDto|ComponenteDigital $componenteDigital
     * @return self
     */
    public function logEPRES9Validado(RestDto|ComponenteDigital $componenteDigital): self
    {
        $this->logger->info(
            sprintf(
                '%s. Componente digital %s validado',
                'EPRES9',
                $componenteDigital->getUuid(),
            ),
            [
                'componente_digital' => $componenteDigital->getUuid(),
                'evento_preservacao' => 'EPRES9',
                'resultado' => 'Validado'
            ]
        );

        return $this;
    }
}
