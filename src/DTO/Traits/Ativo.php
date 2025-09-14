<?php

declare(strict_types=1);
/**
 * /src/DTO/Traits/Ativo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\DTO\Traits;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Trait Ativo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait Ativo
{
    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_ADMIN']),
            new Form\Method('updateMethod', roles: ['ROLE_ADMIN']),
            new Form\Method('patchMethod', roles: ['ROLE_ADMIN']),
        ]
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $ativo = true;

    public function setAtivo(?bool $ativo): self
    {
        $this->setVisited('ativo');

        $this->ativo = $ativo;

        return $this;
    }

    public function getAtivo(): ?bool
    {
        return $this->ativo;
    }
}
