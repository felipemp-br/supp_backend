<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta;

use SuppCore\AdministrativoBackend\Entity\Acao;
use SuppCore\AdministrativoBackend\Entity\Interfaces\VinculacaoEtiquetaInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Traversable;

/**
 * AcoesEtiquetaManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class AcoesEtiquetaManager
{
    /**
     * @var AcoesEtiquetaHandlerInterface[] handlers
     */
    private readonly array $handlers;

    /**
     * Constructor.
     *
     * @param Traversable $handlers
     */
    public function __construct(
        #[TaggedIterator('supp_core.administrativo_backend.acoes_etiqueta.handler')]
        Traversable $handlers
    ) {
        $this->handlers = iterator_to_array($handlers);
    }

    /**
     * @param VinculacaoEtiquetaInterface $vinculacaoEtiqueta
     * @param string                      $transcationId
     *
     * @return void
     */
    public function handle(
        VinculacaoEtiquetaInterface $vinculacaoEtiqueta,
        string $transcationId
    ): void {
        $jsonAcoesSugestao = [];
        if (json_validate($vinculacaoEtiqueta->getAcoesExecucaoSugestao() ?? '')) {
            $jsonAcoesSugestao = json_decode($vinculacaoEtiqueta->getAcoesExecucaoSugestao() ?? '', true);
        }
        /** @var Acao[] $acoes */
        $acoes = array_filter(
            $vinculacaoEtiqueta->getEtiqueta()->getAcoes()->toArray(),
            function (Acao $acao) use ($jsonAcoesSugestao, $vinculacaoEtiqueta) {
                if ($vinculacaoEtiqueta->getSugestao()
                    && $vinculacaoEtiqueta->getDataHoraAprovacaoSugestao()
                    && in_array($acao->getId(), $jsonAcoesSugestao)) {
                    return true;
                }
                if (!$vinculacaoEtiqueta->getSugestao() && !$vinculacaoEtiqueta->getDataHoraAprovacaoSugestao()) {
                    return true;
                }

                return false;
            }
        );

        foreach ($acoes as $acao) {
            foreach ($this->handlers as $handler) {
                if ($handler->support($acao, $vinculacaoEtiqueta)) {
                    $handler->handle($acao, $vinculacaoEtiqueta, $transcationId);
                }
            }
        }
    }
}
