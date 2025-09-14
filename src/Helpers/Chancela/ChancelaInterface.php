<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\Chancela;

use SuppCore\AdministrativoBackend\Entity\Documento;
use SuppCore\AdministrativoBackend\Entity\Processo;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 *
 */
#[AutoconfigureTag('supp_backend.chancela')]
interface ChancelaInterface
{
    public function supports(Processo $processo, Documento $documento): bool;

    public function getQRCodeAvaliacao(Processo $processo, Documento $documento): string;
}
