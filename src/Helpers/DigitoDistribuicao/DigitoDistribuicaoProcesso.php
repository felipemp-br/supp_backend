<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Helpers\DigitoDistribuicao;

use SuppCore\AdministrativoBackend\Entity\Processo;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
/**
 *
 */

class DigitoDistribuicaoProcesso implements DigitoDistribuicaoInterface
{
    // 17 digitos
    // substr($nup, 0, 5).'.'.
    // substr($nup, 5, 6).'/'.
    // substr($nup, 11, 4).'-'.
    // substr($nup, 15, 2);

    // 15 digitos
    // substr($nup, 0, 5).'.'.
    // substr($nup, 5, 6).'/'.
    // substr($nup, 11, 2).'-'.
    // substr($nup, 13, 2);


    public function __construct(protected readonly ParameterBagInterface $parameterBag)
    {
    }

    public function getCentena(Processo $processo): string
    {
        return substr($processo->getNUP(), 9, 2);
    }

    public function getDezena(Processo $processo): string
    {
        return substr($processo->getNUP(), 10, 1);
    }

    public function supports(Processo $processo): bool
    {
        return
            $processo->getEspecieProcesso()->getGeneroProcesso()->getNome() ===
            $this->parameterBag->get('constantes.entidades.genero_processo.const_1.default');
    }
}
