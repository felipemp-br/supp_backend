<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/TipoSolicitacaoAutomatizada.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Formulario as FormularioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Sigla;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Formulario as FormularioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;

/**
 * Class TipoSolicitacaoAutomatizada.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'sigla' => 'sigla',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\TipoSolicitacaoAutomatizada',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/tipo_solicitacao_automatizada/{id}',
    jsonLDType: 'TipoSolicitacaoAutomatizada',
    jsonLDContext: '/api/doc/#model-TipoSolicitacaoAutomatizada'
)]
#[Form\Form]
#[Form\Cacheable(expire: 86400)]
class TipoSolicitacaoAutomatizada extends RestDto
{
    use IdUuid;
    use Sigla;
    use Descricao;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    /**
     * @var FormularioEntity|FormularioDTO|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Formulario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: FormularioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Formulario')]
    protected ?EntityInterface $formulario = null;

    /**
     * @return EntityInterface|null
     */
    public function getFormulario(): ?EntityInterface
    {
        return $this->formulario;
    }

    /**
     * @param EntityInterface|null $formulario
     * @return $this
     */
    public function setFormulario(?EntityInterface $formulario): self
    {
        $this->setVisited('formulario');
        $this->formulario = $formulario;

        return $this;
    }
}
