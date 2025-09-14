<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Sigilo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeCategoriaSigilo as ModalidadeCategoriaSigiloDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoSigilo as TipoSigiloDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\OrigemDados;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Sigilo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/sigilo/{id}',
    jsonLDType: 'Sigilo',
    jsonLDContext: '/api/doc/#model-Sigilo'
)]
#[Form\Form]
class Sigilo extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    use OrigemDados;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $desclassificado = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $observacao = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $codigoIndexacao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $fundamentoLegal = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $razoesClassificacaoSigilo = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraValidadeSigilo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'A data/hora de início do sigilo não pode ser nula!')]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraInicioSigilo = null;

    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $nivelAcesso = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeCategoriaSigilo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeCategoriaSigiloDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeCategoriaSigilo')]
    protected ?EntityInterface $modalidadeCategoriaSigilo = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\TipoSigilo',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: TipoSigiloDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\TipoSigilo')]
    protected ?EntityInterface $tipoSigilo = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Processo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Processo')]
    protected ?EntityInterface $processo = null;

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

    public function getDesclassificado(): bool
    {
        return $this->desclassificado;
    }

    public function setDesclassificado(bool $desclassificado): self
    {
        $this->setVisited('desclassificado');

        $this->desclassificado = $desclassificado;

        return $this;
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

    public function getCodigoIndexacao(): ?string
    {
        return $this->codigoIndexacao;
    }

    public function setCodigoIndexacao(?string $codigoIndexacao): self
    {
        $this->setVisited('codigoIndexacao');

        $this->codigoIndexacao = $codigoIndexacao;

        return $this;
    }

    public function getFundamentoLegal(): ?string
    {
        return $this->fundamentoLegal;
    }

    public function setFundamentoLegal(?string $fundamentoLegal): self
    {
        $this->setVisited('fundamentoLegal');

        $this->fundamentoLegal = $fundamentoLegal;

        return $this;
    }

    public function getRazoesClassificacaoSigilo(): ?string
    {
        return $this->razoesClassificacaoSigilo;
    }

    public function setRazoesClassificacaoSigilo(?string $razoesClassificacaoSigilo): self
    {
        $this->setVisited('razoesClassificacaoSigilo');

        $this->razoesClassificacaoSigilo = $razoesClassificacaoSigilo;

        return $this;
    }

    public function getDataHoraValidadeSigilo(): ?DateTime
    {
        return $this->dataHoraValidadeSigilo;
    }

    public function setDataHoraValidadeSigilo(?DateTime $dataHoraValidadeSigilo): self
    {
        $this->setVisited('dataHoraValidadeSigilo');

        $this->dataHoraValidadeSigilo = $dataHoraValidadeSigilo;

        return $this;
    }

    public function getDataHoraInicioSigilo(): ?DateTime
    {
        return $this->dataHoraInicioSigilo;
    }

    public function setDataHoraInicioSigilo(?DateTime $dataHoraInicioSigilo): self
    {
        $this->setVisited('dataHoraInicioSigilo');

        $this->dataHoraInicioSigilo = $dataHoraInicioSigilo;

        return $this;
    }

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

    public function getModalidadeCategoriaSigilo(): ?EntityInterface
    {
        return $this->modalidadeCategoriaSigilo;
    }

    public function setModalidadeCategoriaSigilo(?EntityInterface $modalidadeCategoriaSigilo): self
    {
        $this->setVisited('modalidadeCategoriaSigilo');

        $this->modalidadeCategoriaSigilo = $modalidadeCategoriaSigilo;

        return $this;
    }

    public function getTipoSigilo(): ?EntityInterface
    {
        return $this->tipoSigilo;
    }

    public function setTipoSigilo(?EntityInterface $tipoSigilo): self
    {
        $this->setVisited('tipoSigilo');

        $this->tipoSigilo = $tipoSigilo;

        return $this;
    }

    public function getProcesso(): ?EntityInterface
    {
        return $this->processo;
    }

    public function setProcesso(?EntityInterface $processo): self
    {
        $this->setVisited('processo');

        $this->processo = $processo;

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
}
