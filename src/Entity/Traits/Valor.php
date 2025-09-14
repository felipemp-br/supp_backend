<?php

declare(strict_types=1);
/**
 * /src/Entity/Traits/Valor.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity\Traits;

use DMS\Filter\Rules as Filter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait Valor.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait Valor
{
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'O campo deve ter no mínimo 3 caracteres!',
        maxMessage: 'O campo deve ter no máximo 255 caracteres!'
    )]
    #[ORM\Column(type: 'string', unique: true, nullable: false)]
    protected string $valor = '';

    public function setValor(string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getValor(): string
    {
        return $this->valor;
    }
}
