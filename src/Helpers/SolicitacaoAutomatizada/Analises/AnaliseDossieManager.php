<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises;

use SuppCore\AdministrativoBackend\Entity\TipoSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\AnalisaDossieException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\UnsupportedTipoAnaliseDossiesException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models\AnaliseDossies;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

/**
 * AnaliseDossieManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class AnaliseDossieManager
{
    /** @var AnalisaDossiesInterface[] */
    private array $analises;

    public function __construct(
        #[AutowireIterator('supp_core.administrativo_backend.solicitacao_automatizada.analise_dossies')]
        iterable $analises
    ) {
        $this->analises = iterator_to_array($analises);
    }

    /**
     * Analisa o(s) dossie(s) informado(s).
     *
     * @param AnaliseDossies              $analiseDossies
     * @param TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada
     * @param array                       $dadosTipoSolicitacao
     *
     * @return AnaliseDossies
     *
     * @throws AnalisaDossieException
     * @throws UnsupportedTipoAnaliseDossiesException
     */
    public function analisar(
        AnaliseDossies $analiseDossies,
        TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada,
        array $dadosTipoSolicitacao = [],
    ): AnaliseDossies {
        foreach ($this->analises as $analise) {
            if ($analise->supports($analiseDossies, $tipoSolicitacaoAutomatizada, $dadosTipoSolicitacao)) {
                return $analise->analisar($analiseDossies, $tipoSolicitacaoAutomatizada, $dadosTipoSolicitacao);
            }
        }

        throw new UnsupportedTipoAnaliseDossiesException(
            $analiseDossies->getAnalise(),
            $tipoSolicitacaoAutomatizada->getSigla(),
        );
    }
}
