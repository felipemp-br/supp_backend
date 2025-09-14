<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Integracao\Avaliacao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Avaliacao as AvaliacaoDTO;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;

/**
 *
 */
interface AvaliacaoInterface
{
    public function supports(string $class): bool|array;

    public function getOrder(string $class): int;

    public function getValorInicial(EntityInterface $objetoAvaliado, array $fabricasVisitadas): float;

    public function getValorAvaliacaoResultante(EntityInterface $objetoAvaliado, AvaliacaoDTO $avaliacao, array $fabricasVisitadas): float;
}
