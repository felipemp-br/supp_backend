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
use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieDocumento as EspecieDocumentoDTO;
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
use SuppCore\AdministrativoBackend\Entity\EspecieDocumento as EspecieDocumentoEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TipoDocumento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/especie_documento/{id}',
    jsonLDType: 'EspecieDocumento',
    jsonLDContext: '/api/doc/#model-EspecieDocumento'
)]
#[Form\Form]
#[Form\Cacheable(expire: 86400)]
class TipoDocumento extends RestDto
{
    use IdUuid;
    use Nome;
    use Sigla;
    use Descricao;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieDocumento',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: EspecieDocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieDocumento')]
    protected ?EntityInterface $especieDocumento = null;

    /**
     * @return EntityInterface|EspecieDocumentoEntity|EspecieDocumentoDTO|int|null
     */
    public function getEspecieDocumento(): ?EntityInterface
    {
        return $this->especieDocumento;
    }

    /**
     * @param EntityInterface|EspecieDocumentoEntity|EspecieDocumentoDTO|int|null $especieDocumento
     */
    public function setEspecieDocumento(?EntityInterface $especieDocumento): self
    {
        $this->setVisited('especieDocumento');

        $this->especieDocumento = $especieDocumento;

        return $this;
    }
}
