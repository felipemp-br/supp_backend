<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/EspecieDocumento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroDocumento as GeneroDocumentoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Sigla;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\GeneroDocumento as GeneroDocumentoEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EspecieDocumento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
        'generoDocumento' => 'generoDocumento',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\EspecieDocumento',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/especie_documento/{id}',
    jsonLDType: 'EspecieDocumento',
    jsonLDContext: '/api/doc/#model-EspecieDocumento'
)]
#[Form\Form]
#[Form\Cacheable(expire: 86400)]
class EspecieDocumento extends RestDto
{
    use IdUuid;
    use Nome;
    use Descricao;
    use Ativo;
    use Sigla;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\GeneroDocumento',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: GeneroDocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\GeneroDocumento')]
    protected ?EntityInterface $generoDocumento = null;

    /**
     * @return EntityInterface|GeneroDocumentoEntity|GeneroDocumentoDTO|int|null
     */
    public function getGeneroDocumento(): ?EntityInterface
    {
        return $this->generoDocumento;
    }

    /**
     * @param EntityInterface|GeneroDocumentoEntity|GeneroDocumentoDTO|int|null $generoDocumento
     */
    public function setGeneroDocumento(?EntityInterface $generoDocumento): self
    {
        $this->setVisited('generoDocumento');

        $this->generoDocumento = $generoDocumento;

        return $this;
    }
}
