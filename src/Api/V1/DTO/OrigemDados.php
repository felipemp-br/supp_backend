<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/OrigemDados.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use DMS\Filter\Rules as Filter;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class OrigemDados.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/origem_dados/{id}',
    jsonLDType: 'OrigemDados',
    jsonLDContext: '/api/doc/#model-OrigemDados'
)]
#[Form\Form]
class OrigemDados extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $idExterno = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraUltimaConsulta = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $servico = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $fonteDados = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $mensagemUltimaConsulta = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected int $status = 0;

    public function getIdExterno(): ?string
    {
        return $this->idExterno;
    }

    public function setIdExterno(?string $idExterno): self
    {
        $this->setVisited('idExterno');

        $this->idExterno = $idExterno;

        return $this;
    }

    public function getDataHoraUltimaConsulta(): ?DateTime
    {
        return $this->dataHoraUltimaConsulta;
    }

    public function setDataHoraUltimaConsulta(?DateTime $dataHoraUltimaConsulta): self
    {
        $this->setVisited('dataHoraUltimaConsulta');

        $this->dataHoraUltimaConsulta = $dataHoraUltimaConsulta;

        return $this;
    }

    public function getServico(): ?string
    {
        return $this->servico;
    }

    public function setServico(?string $servico): self
    {
        $this->setVisited('servico');

        $this->servico = $servico;

        return $this;
    }

    public function getFonteDados(): ?string
    {
        return $this->fonteDados;
    }

    public function setFonteDados(?string $fonteDados): self
    {
        $this->setVisited('fonteDados');

        $this->fonteDados = $fonteDados;

        return $this;
    }

    public function getMensagemUltimaConsulta(): ?string
    {
        return $this->mensagemUltimaConsulta;
    }

    public function setMensagemUltimaConsulta(?string $mensagemUltimaConsulta): self
    {
        $this->setVisited('mensagemUltimaConsulta');

        $this->mensagemUltimaConsulta = $mensagemUltimaConsulta;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->setVisited('status');

        $this->status = $status;

        return $this;
    }
}
