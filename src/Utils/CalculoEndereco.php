<?php
/** @noinspection PhpUnused */
declare(strict_types=1);
/**
 * /src/Helper/Managers/Traits/EnderecoFormatado.php.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use SuppCore\AdministrativoBackend\Entity\Endereco;
use SuppCore\AdministrativoBackend\Entity\Municipio;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;

/**
 * Trait EnderecoFormatado.
 *
 * @author  Advocacia-Geral da União <supp@agu.gov.br>
 */
trait CalculoEndereco
{
    protected function enderecoComMunicipio(PessoaEntity $pessoa): ?Endereco
    {
        // endereco principal com municipio
        /** @var Endereco $endereco */
        $endereco = $pessoa
            ->getEnderecos()
            ->filter(
                function ($p) {
                    return $p->getPrincipal() && $p->getMunicipio();
                }
            )->first();

        // qualquer endereco com muncipio
        if (!$endereco) {
            return $pessoa
                ->getEnderecos()
                ->filter(
                    function ($item) {
                        return $item->getMunicipio();
                    }
                )->first() ?: null;
        }
        return $endereco;
    }

    protected function municipioEndereco(PessoaEntity $pessoa): ?Municipio
    {
        $endereco = $this->enderecoComMunicipio($pessoa);

        return $endereco?->getMunicipio();
    }

    /**
     * @param PessoaEntity $pessoa
     *
     * @return string|null
     */
    protected function enderecoFormatado(PessoaEntity $pessoa): ?string
    {
        $endereco = $this->enderecoComMunicipio($pessoa);

        if ($endereco) {
            $result = "{$endereco->getLogradouro()}, {$endereco->getNumero()}/{$endereco->getComplemento()}.";
            $result .= $endereco->getCep() ? " CEP {$endereco->getCep()}." : '';
            $result .= $endereco->getBairro() ? " BAIRRO {$endereco->getBairro()}." : '';
            $result .= " {$endereco->getMunicipio()->getNome()}";

            if ($endereco->getMunicipio()->getEstado()) {
                $result .= "/{$endereco->getMunicipio()->getEstado()->getUf()}.";
                $result .= $endereco->getMunicipio()->getEstado()->getPais() ?
                    " {$endereco->getMunicipio()->getEstado()->getPais()->getNome()}. " : '';
            }

            return $result;
        }

        return null;
    }
}
