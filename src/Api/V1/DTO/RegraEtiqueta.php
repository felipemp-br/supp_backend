<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/RegraEtiqueta.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta as EtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\MomentoDisparoRegraEtiqueta as MomentoDisparoRegraEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RegraEtiqueta.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/regra_etiqueta/{id}',
    jsonLDType: 'RegraEtiqueta',
    jsonLDContext: '/api/doc/#model-RegraEtiqueta'
)]
#[Form\Form]
class RegraEtiqueta extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    use Nome;
    use Descricao;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $criteria = null;

    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $regra = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Etiqueta',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: EtiquetaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Etiqueta')]
    protected ?EntityInterface $etiqueta = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\MomentoDisparoRegraEtiqueta',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: MomentoDisparoRegraEtiquetaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\MomentoDisparoRegraEtiqueta')]
    protected ?EntityInterface $momentoDisparoRegraEtiqueta = null;

    public function getEtiqueta(): ?EntityInterface
    {
        return $this->etiqueta;
    }

    public function setEtiqueta(?EntityInterface $etiqueta): self
    {
        $this->setVisited('etiqueta');

        $this->etiqueta = $etiqueta;

        return $this;
    }

    public function getCriteria(): ?string
    {
        return $this->criteria;
    }

    public function setCriteria(?string $criteria): self
    {
        $this->setVisited('criteria');

        $this->criteria = $criteria;

        return $this;
    }

    public function getRegra(): ?string
    {
        return $this->regra;
    }

    public function setRegra(?string $regra): self
    {
        $this->setVisited('regra');
        $this->regra = $regra;

        return $this;
    }

    public function getMomentoDisparoRegraEtiqueta(): ?EntityInterface
    {
        return $this->momentoDisparoRegraEtiqueta;
    }

    public function setMomentoDisparoRegraEtiqueta(?EntityInterface $momentoDisparoRegraEtiqueta): self
    {
        $this->setVisited('momentoDisparoRegraEtiqueta');

        $this->momentoDisparoRegraEtiqueta = $momentoDisparoRegraEtiqueta;

        return $this;
    }
}
