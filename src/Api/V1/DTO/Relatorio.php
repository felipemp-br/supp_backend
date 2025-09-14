<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Relatorio.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoRelatorio as TipoRelatorioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\TipoRelatorio as TipoRelatorioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Relatorio.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/relatorio/{id}',
    jsonLDType: 'Relatorio',
    jsonLDContext: '/api/doc/#model-Relatorio'
)]
#[Form\Form]
class Relatorio extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    public const PARAMS = ['dataHoraInicio', 'dataHoraFim', 'usuario', 'setor', 'unidade'];

    public const FORMATOS = ['html', 'pdf', 'xlsx'];

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $observacao = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\TipoRelatorio',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: TipoRelatorioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\TipoRelatorio')]
    protected ?EntityInterface $tipoRelatorio = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Documento',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: DocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documento = null;

    #[Serializer\Exclude]
    #[Assert\Choice(['html', 'pdf', 'xlsx'])]
    #[OA\Property(type: 'string')]
    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    protected ?string $formato = 'html';

    #[Serializer\Exclude]
    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    protected ?string $parametros = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $status = 0;

    /**
     * @var VinculacaoEtiquetaDTO[]
     */
    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta',
        dtoGetter: 'getVinculacoesEtiquetas',
        dtoSetter: 'addVinculacaoEtiqueta',
        collection: true
    )]
    protected $vinculacoesEtiquetas = [];

    public function __construct()
    {
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): self
    {
        $this->setVisited('observacao');

        $this->observacao = $observacao;

        return $this;
    }

    /**
     * @return int|EntityInterface|TipoRelatorio|TipoRelatorioEntity|null
     */
    public function getTipoRelatorio(): ?EntityInterface
    {
        return $this->tipoRelatorio;
    }

    /**
     * @param int|EntityInterface|TipoRelatorio|TipoRelatorioEntity|null $tipoRelatorio
     */
    public function setTipoRelatorio(?EntityInterface $tipoRelatorio): self
    {
        $this->setVisited('tipoRelatorio');

        $this->tipoRelatorio = $tipoRelatorio;

        return $this;
    }

    public function getDocumento(): ?EntityInterface
    {
        return $this->documento;
    }

    public function setDocumento(?EntityInterface $documento): self
    {
        $this->setVisited('documento');

        $this->documento = $documento;

        return $this;
    }

    public function getFormato(): ?string
    {
        return $this->formato;
    }

    public function setFormato(?string $formato): self
    {
        $this->setVisited('formato');

        $this->formato = $formato;

        return $this;
    }

    public function getParametros(): ?string
    {
        return $this->parametros;
    }

    /**
     * @return array
     */
    public function getParametrosAsArray(): ?array
    {
        if ($this->parametros) {
            return json_decode($this->parametros, true);
        }

        return [];
    }

    public function setParametros(?string $parametros): self
    {
        $this->setVisited('parametros');

        $this->parametros = $parametros;

        return $this;
    }

    public function addVinculacaoEtiqueta(VinculacaoEtiquetaDTO $vinculacaoEtiqueta): self
    {
        $this->vinculacoesEtiquetas[] = $vinculacaoEtiqueta;

        return $this;
    }

    public function getVinculacoesEtiquetas(): array
    {
        return $this->vinculacoesEtiquetas;
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
