<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Integracao\Avaliacao\Fabrica;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Avaliacao;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Integracao\Avaliacao\AvaliacaoInterface;

/**
 *
 */
class AvaliacaoDefault implements AvaliacaoInterface
{

    public function supports(string $class): bool
    {
        return true;
    }

    public function getOrder(string $class): int
    {
        return PHP_INT_MAX;
    }

    public function getValorInicial(EntityInterface $objetoAvaliado, array $fabricasVisitadas = []): float
    {
        return 50;
    }

    public function getValorAvaliacaoResultante(EntityInterface $objetoAvaliado, Avaliacao $avaliacao, array $fabricasVisitadas = []): float
    {

        // define a nova avaliacao do objeto
        return (
                ($objetoAvaliado->getAvaliacaoResultante() * $objetoAvaliado->getQuantidadeAvaliacoes()
                ) + $avaliacao->getAvaliacao())
            / ($objetoAvaliado->getQuantidadeAvaliacoes() + 1);
    }
}
