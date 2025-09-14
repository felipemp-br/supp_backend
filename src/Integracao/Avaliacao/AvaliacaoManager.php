<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Integracao\Avaliacao;

use Exception;

/**
 *
 */
class AvaliacaoManager
{
    /** @var AvaliacaoInterface[] */
    private array $avaliacoesInterfaces = [];

    /** @noinspection PhpUnused */
    public function addAvaliacao(AvaliacaoInterface $avaliacaoInterface)
    {
        $this->avaliacoesInterfaces[] = $avaliacaoInterface;
    }

    /**
     * @throws Exception
     * @noinspection PhpUnused
     */
    public function getAvaliacao(string $classe, array $interfacesAvaliacaoExcluidas = []): AvaliacaoInterface
    {
        $avaliacoesSuportadas = [];
        foreach ($this->avaliacoesInterfaces as $a) {
            if (!in_array($a::class, $interfacesAvaliacaoExcluidas) && $a->supports($classe)) {
                $avaliacoesSuportadas[$a->getOrder($classe)] = $a;
            }
        }
        ksort($avaliacoesSuportadas);

        return $avaliacoesSuportadas ?
            reset($avaliacoesSuportadas) :
            throw new Exception('');
    }
}
