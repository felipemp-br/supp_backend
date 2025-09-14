<?php

declare(strict_types=1);
/**
 * /src/NUP/NumeroUnicoProtocoloInterface.php.
 */

namespace SuppCore\AdministrativoBackend\NUP;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;

/**
 * Class NumeroUnicoProtocoloInterface.
 */
interface NumeroUnicoProtocoloInterface
{
    public function gerarNumeroUnicoProtocolo(ProcessoDTO $processo): string;

    public function validarNumeroUnicoProtocolo(ProcessoDTO $processo, string &$errorMessage = null): bool;

    public function formatarNumeroUnicoProtocolo(string $nup): string;

    public function getNome(): string;

    public function getDescricao(): string;

    public function getSigla(): string;

    public function getOrder(): int;

    public function getDataHoraInicioVigencia(): DateTime;

    public function getDataHoraFimVigencia(): ?DateTime;

    public function getDigitos(): int;
}
