<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\DigitoDistribuicao;

use SuppCore\AdministrativoBackend\Entity\Processo;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 *
 */
#[AutoconfigureTag('supp_backend.digito_distribuicao')]
interface DigitoDistribuicaoInterface
{
    public function supports(Processo $processo): bool;

    public function getCentena(Processo $processo): string;

    public function getDezena(Processo $processo): string;
}
