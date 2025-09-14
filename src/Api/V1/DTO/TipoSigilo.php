<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/TipoSigilo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TipoSigilo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\TipoSigilo',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/tipo_sigilo/{id}',
    jsonLDType: 'TipoSigilo',
    jsonLDContext: '/api/doc/#model-TipoSigilo'
)]
#[Form\Form]
#[Form\Cacheable(expire: 86400)]
class TipoSigilo extends RestDto
{
    use IdUuid;
    use Nome;
    use Descricao;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Range(notInRangeMessage: 'Campo ser entre {{ min }} e {{ max }}', min: 0, max: 4)]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $nivelAcesso = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Range(notInRangeMessage: 'Campo ser entre {{ min }} e {{ max }}', min: 0, max: 100)]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $prazoAnos = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $leiAcessoInformacao = false;

    public function getNivelAcesso(): ?int
    {
        return $this->nivelAcesso;
    }

    public function setNivelAcesso(?int $nivelAcesso): self
    {
        $this->setVisited('nivelAcesso');

        $this->nivelAcesso = $nivelAcesso;

        return $this;
    }

    public function getPrazoAnos(): ?int
    {
        return $this->prazoAnos;
    }

    public function setPrazoAnos(?int $prazoAnos): self
    {
        $this->setVisited('prazoAnos');

        $this->prazoAnos = $prazoAnos;

        return $this;
    }

    public function getLeiAcessoInformacao(): ?bool
    {
        return $this->leiAcessoInformacao;
    }

    public function setLeiAcessoInformacao(?bool $leiAcessoInformacao): self
    {
        $this->setVisited('leiAcessoInformacao');

        $this->leiAcessoInformacao = $leiAcessoInformacao;

        return $this;
    }
}
