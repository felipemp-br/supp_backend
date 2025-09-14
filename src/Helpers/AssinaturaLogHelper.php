<?php

declare(strict_types=1);

/**
 * /src/Helpers/AssinaturaLogHelper.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Helpers;

use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Entity\Assinatura;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use Throwable;

use function count;
use function is_array;
use function is_string;

/**
 *
 */
readonly class AssinaturaLogHelper
{
    /**
     * @param LoggerInterface $statisticsLogger
     */
    public function __construct(
        private LoggerInterface $statisticsLogger
    ) {
    }

    /**
     * @param string $mensagem
     * @param Usuario|string|null $usuario
     * @param string|null $protocol
     * @param bool|null $pades
     * @param bool|null $anexo
     * @param bool|null $sincrono
     * @param array|null $documentosIds
     * @param ComponenteDigital[]|int []|null $componentesDigitais
     * @param Assinatura[]|null $assinaturas
     * @return void
     */
    public function info(
        string $mensagem,
        Usuario|string|null $usuario = null,
        ?string $protocol = null,
        ?bool $pades = null,
        ?bool $anexo = null,
        ?bool $sincrono = null,
        ?array $documentosIds = null,
        ?array $componentesDigitais = null,
        ?array $assinaturas = null,
    ):void {
        $this->statisticsLogger->info(
            $this->getMessage(
                $mensagem,
                $usuario,
                $protocol,
                $pades,
                $anexo,
                $sincrono,
                $documentosIds,
                $componentesDigitais,
                $assinaturas
            )
        );
    }

    /**
     * @param string $mensagem
     * @param Usuario|string|null $usuario
     * @param string|null $protocol
     * @param bool|null $pades
     * @param bool|null $anexo
     * @param bool|null $sincrono
     * @param array|null $documentosIds
     * @param ComponenteDigital[]|int[]|null $componentesDigitais
     * @param Assinatura[]|null $assinaturas
     * @param string|null $exception
     * @param string|null $stackTrace
     * @return void
     */
    public function critical(
        string $mensagem,
        Usuario|string|null $usuario = null,
        ?string $protocol = null,
        ?bool $pades = null,
        ?bool $anexo = null,
        ?bool $sincrono = null,
        ?array $documentosIds = null,
        ?array $componentesDigitais = null,
        ?array $assinaturas = null,
        ?string $exception = null,
        ?string $stackTrace = null,
    ):void {
        $this->statisticsLogger->critical(
            $this->getMessage(
                $mensagem,
                $usuario,
                $protocol,
                $pades,
                $anexo,
                $sincrono,
                $documentosIds,
                $componentesDigitais,
                $assinaturas
            )
            .(!empty($exception) ? "\nException: ".$exception : '')
            .(!empty($stackTrace) ? "\nStack trace:\n".$stackTrace : '')
        );
    }

    /**
     * @param string $mensagem
     * @param Usuario|string|null $usuario
     * @param string|null $protocol
     * @param bool|null $pades
     * @param bool|null $anexo
     * @param bool|null $sincrono
     * @param array|null $documentosIds
     * @param ComponenteDigital[]|int[]|null $componentesDigitais
     * @param Assinatura[]|null $assinaturas
     * @param string|null $exception
     * @param string|null $stackTrace
     * @return void
     */
    public function error(
        string $mensagem,
        Usuario|string|null $usuario = null,
        ?string $protocol = null,
        ?bool $pades = null,
        ?bool $anexo = null,
        ?bool $sincrono = null,
        ?array $documentosIds = null,
        ?array $componentesDigitais = null,
        ?array $assinaturas = null,
        ?string $exception = null,
        ?string $stackTrace = null,
    ):void {
        $this->critical(
            $mensagem,
            $usuario,
            $protocol,
            $pades,
            $anexo,
            $sincrono,
            $documentosIds,
            $componentesDigitais,
            $assinaturas,
            $exception,
            $stackTrace
        );
    }

    /**
     * @param string $mensagem
     * @param Usuario|string|null $usuario
     * @param string|null $protocol
     * @param bool|null $pades
     * @param bool|null $anexo
     * @param bool|null $sincrono
     * @param array|null $documentosIds
     * @param ComponenteDigital[]|int[]|null $componentesDigitais
     * @param Assinatura[]|null $assinaturas
     * @return string
     */
    public function getMessage(
        string $mensagem,
        Usuario|string|null $usuario = null,
        ?string $protocol = null,
        ?bool $pades = null,
        ?bool $anexo = null,
        ?bool $sincrono = null,
        ?array $documentosIds = null,
        ?array $componentesDigitais = null,
        ?array $assinaturas = null,
    ):string {
        if (empty($componentesDigitais)) {
            $componentesDigitais = [];
        }
        if (!empty($componentesDigitais)
            && !is_array($componentesDigitais)
        ) {
            $componentesDigitais = [$componentesDigitais];
        }

        try {
            if (is_array($componentesDigitais)) {
                // retira null   [null,4]
                $componentesDigitais = array_filter(
                    $componentesDigitais,
                    static fn($componenteDigital) => null !== $componenteDigital
                );
            }

            if (empty($documentosIds)) {
                $documentosIds = [];
            }

            if (!empty($documentosIds)
                && !is_array($documentosIds)
            ) {
                $documentosIds = [$documentosIds];
            }

            if (is_array($documentosIds)) {
                // retira null   [null,4]
                $documentosIds = array_filter(
                    $documentosIds,
                    static fn($documentoId) => null !== $documentoId
                );
            }

            if (!empty($protocol)) {
                $protocol = match (trim($protocol)) {
                    'a1' => 'a1 (Assinador Interno)',
                    'a3' => 'a3 (Assinador Externo)',
                    'neoid' => 'neoid (Nuvem SERPRO)',
                    default => ''
                };
            }

            return $mensagem
                .(!empty($documentosIds) ? "\nDocumento ID: ".implode(',', $documentosIds) : '')
                .(!empty($documentosIds) && count($documentosIds) > 1 ? "\nQtd Documentos: ".count($documentosIds) : '')
                .(!empty($usuario) && !is_string($usuario) ? "\nNome: ".$usuario->getNome() : '')
                .(!empty($usuario) ? "\nUsuário: ".(is_string($usuario) ? $usuario : $usuario->getUsername()) : '')
                .(!empty($protocol) ? "\nProtocolo: ".$protocol : '')
                .(null !== $pades ? ("\nPadrão: ".(true === $pades ? 'PAdES' : 'CAdES')) : '')
                .(null !== $anexo ? ("\nAnexo: ".(true === $anexo ? "sim" : 'não')) : '')
                .(!empty($componentesDigitais) ? "\nComponente Digital ID: ".implode(
                    ',',
                    array_map(
                        static fn($componenteDigital
                        ) => ($componenteDigital instanceof ComponenteDigital ? $componenteDigital->getId(
                        ) : $componenteDigital),
                        $componentesDigitais
                    )
                ) : '')
                .(!empty($componentesDigitais)
                    && count($componentesDigitais) > 1
                    ? "\nQtd Componentes Digitais: ".count($componentesDigitais) : '')
                .(!empty($assinaturas) ? "\nAssinatura ID: ".implode(
                    ',',
                    array_map(static fn($assinatura) => $assinatura->getId(), $assinaturas)
                ) : '')
                .(null === $sincrono ? '' : ($sincrono ? "\nSíncrono" : "\nAssíncrono"))
                ."\nAssinaturaLog";
        } catch (Throwable $throwable) {
            return 'Erro no LOG da assinatura: '.$throwable->getMessage();
        }
    }
}
