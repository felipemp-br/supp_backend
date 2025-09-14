<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/ConfiguracaoNup.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Nome;
use SuppCore\AdministrativoBackend\DTO\Traits\Sigla;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Configuracao Nup.
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'nome' => 'nome',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\ConfiguracaoNup',
    message: 'Campo já está em utilização!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/configuracaoNup/{id}',
    jsonLDType: 'ConfiguracaoNup',
    jsonLDContext: '/api/doc/#model-ConfiguracaoNup'
)]
#[Form\Form]
class ConfiguracaoNup extends RestDto
{
    use IdUuid;
    use Nome;
    use Descricao;
    use Sigla;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraInicioVigencia = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraFimVigencia = null;

    public function getDataHoraInicioVigencia(): ?DateTime
    {
        return $this->dataHoraInicioVigencia;
    }

    public function setDataHoraInicioVigencia(?DateTime $dataHoraInicioVigencia): self
    {
        $this->setVisited('dataHoraInicioVigencia');

        $this->dataHoraInicioVigencia = $dataHoraInicioVigencia;

        return $this;
    }

    public function getDataHoraFimVigencia(): ?DateTime
    {
        return $this->dataHoraFimVigencia;
    }

    public function setDataHoraFimVigencia(?DateTime $dataHoraFimVigencia): self
    {
        $this->setVisited('dataHoraFimVigencia');

        $this->dataHoraFimVigencia = $dataHoraFimVigencia;

        return $this;
    }
}
