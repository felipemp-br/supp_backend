<?php

declare(strict_types=1);
/**
 * /src/Entity/Traits/Sigla.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity\Traits;

use DMS\Filter\Rules as Filter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait Sigla.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait Sigla
{
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Regex(
        pattern: '/[A-Z0-9]+/',
        message: 'A sigla do template deve possuir apenas letras maiúsculas ou números.'
    )]
    #[Assert\Length(
        min: 3,
        max: 25,
        minMessage: 'A sigla deve ter no mínimo {{ limit }} letras ou números',
        maxMessage: 'A sigla deve ter no máximo {{ limit }} letras ou números'
    )]
    #[ORM\Column(type: 'string', length: 25, nullable: false)]
    protected string $sigla = '';

    public function setSigla(string $sigla): self
    {
        $this->sigla = $sigla;

        return $this;
    }

    public function getSigla(): string
    {
        return $this->sigla;
    }
}
