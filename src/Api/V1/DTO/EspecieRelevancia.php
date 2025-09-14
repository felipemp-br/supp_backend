<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/EspecieRelevancia.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroRelevancia as GeneroRelevanciaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\GeneroRelevancia as GeneroRelevanciaEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EspecieRelevancia.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
        'generoRelevancia' => 'generoRelevancia',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\EspecieRelevancia',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/especie_relevancia/{id}',
    jsonLDType: 'EspecieRelevancia',
    jsonLDContext: '/api/doc/#model-EspecieRelevancia'
)]
#[Form\Form]
#[Form\Cacheable(expire: 86400)]
class EspecieRelevancia extends RestDto
{
    use IdUuid;
    use Nome;
    use Descricao;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\GeneroRelevancia',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: GeneroRelevanciaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroRelevancia')]
    protected ?EntityInterface $generoRelevancia = null;

    /**
     * @return EntityInterface|GeneroRelevanciaEntity|GeneroRelevanciaDTO|int|null
     */
    public function getGeneroRelevancia(): ?EntityInterface
    {
        return $this->generoRelevancia;
    }

    /**
     * @param EntityInterface|GeneroRelevanciaEntity|GeneroRelevanciaDTO|int|null $generoRelevancia
     */
    public function setGeneroRelevancia(?EntityInterface $generoRelevancia): self
    {
        $this->setVisited('generoRelevancia');

        $this->generoRelevancia = $generoRelevancia;

        return $this;
    }
}
