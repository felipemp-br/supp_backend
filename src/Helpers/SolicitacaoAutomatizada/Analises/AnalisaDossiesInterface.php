<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Analises;

use SuppCore\AdministrativoBackend\Entity\TipoSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Exceptions\AnalisaDossieException;
use SuppCore\AdministrativoBackend\Helpers\SolicitacaoAutomatizada\Models\AnaliseDossies;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * AnalisaDossiesInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AutoconfigureTag('supp_core.administrativo_backend.solicitacao_automatizada.analise_dossies')]
interface AnalisaDossiesInterface
{
    /**
     * Realiza a analise dos dossies.
     *
     * @param AnaliseDossies              $analiseDossies
     * @param TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada
     * @param array                       $dadosTipoSolicitacao
     *
     * @return AnaliseDossies
     *
     * @throws AnalisaDossieException
     */
    public function analisar(
        AnaliseDossies $analiseDossies,
        TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada,
        array $dadosTipoSolicitacao = [],
    ): AnaliseDossies;

    /**
     * Verifica se o handler suporta o tipo de analise de dossie.
     *
     * @param AnaliseDossies              $analiseDossies
     * @param TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada
     * @param array                       $dadosTipoSolicitacao
     *
     * @return bool
     */
    public function supports(
        AnaliseDossies $analiseDossies,
        TipoSolicitacaoAutomatizada $tipoSolicitacaoAutomatizada,
        array $dadosTipoSolicitacao = [],
    ): bool;
}
