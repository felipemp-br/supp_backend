<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/VinculacaoParametroAdministrativo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ParametroAdministrativo as ParametroAdministrativoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\ParametroAdministrativo as ParametroAdministrativoEntity;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoParametroAdministrativo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/vinculacao_parametro_administrativo/{id}',
    jsonLDType: 'VinculacaoParametroAdministrativo',
    jsonLDContext: '/api/doc/#model-VinculacaoParametroAdministrativo'
)]
#[Form\Form]
class VinculacaoParametroAdministrativo extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    /**
     * @var ParametroAdministrativoDTO|ParametroAdministrativoEntity|EntityInterface|int|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ParametroAdministrativo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ParametroAdministrativoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ParametroAdministrativo')]
    protected $parametroAdministrativo;

    /**
     * @var ProcessoDTO|ProcessoEntity|EntityInterface|int|null
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Processo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Processo')]
    protected $processo;

    public function __construct()
    {
    }

    /**
     * @return int|ParametroAdministrativo|EntityInterface|ParametroAdministrativoEntity|null
     */
    public function getParametroAdministrativo(): ParametroAdministrativo|EntityInterface|ParametroAdministrativoEntity|int|null
    {
        return $this->parametroAdministrativo;
    }

    /**
     * @param int|ParametroAdministrativo|EntityInterface|ParametroAdministrativoEntity|null $parametroAdministrativo
     *
     * @return VinculacaoParametroAdministrativo
     */
    public function setParametroAdministrativo($parametroAdministrativo): VinculacaoParametroAdministrativo
    {
        $this->setVisited('parametroAdministrativo');
        $this->parametroAdministrativo = $parametroAdministrativo;

        return $this;
    }

    /**
     * @return int|Processo|EntityInterface|ProcessoEntity|null
     */
    public function getProcesso(): Processo|EntityInterface|ProcessoEntity|int|null
    {
        return $this->processo;
    }

    /**
     * @param int|EntityInterface|Processo|ProcessoEntity|null $processo
     *
     * @return VinculacaoParametroAdministrativo
     */
    public function setProcesso(ProcessoEntity|Processo|EntityInterface|int|null $processo): VinculacaoParametroAdministrativo
    {
        $this->setVisited('processo');
        $this->processo = $processo;

        return $this;
    }
}
