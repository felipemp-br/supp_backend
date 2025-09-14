<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Bookmark.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Bookmark.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/bookmark/{id}',
    jsonLDType: 'Bookmark',
    jsonLDContext: '/api/doc/#model-Bookmark'
)]
#[Form\Form]
class Bookmark extends RestDto
{
    use IdUuid;
    use Blameable;
    use Softdeleteable;
    use Timeblameable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuario = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo {{ limit }} caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected string $nome = '';

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'integer', default: 0)]
    #[DTOMapper\Property]
    protected int $pagina = 0;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo {{ limit }} caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $descricao = '';

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ComponenteDigital',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: ComponenteDigitalDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital')]
    protected ?EntityInterface $componenteDigital = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Processo',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: ProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Processo')]
    protected ?EntityInterface $processo = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Juntada',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: JuntadaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada')]
    protected ?EntityInterface $juntada = null;

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
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $corHexadecimal = '#FFFFFF';

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo {{ limit }} caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $textoReferencia = '';

    public function __construct()
    {
    }

    public function getUsuario(): ?EntityInterface
    {
        return $this->usuario;
    }

    public function setUsuario(?EntityInterface $usuario): self
    {
        $this->setVisited('usuario');

        $this->usuario = $usuario;

        return $this;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->setVisited('nome');

        $this->nome = $nome;

        return $this;
    }

    public function getPagina(): ?int
    {
        return $this->pagina;
    }

    public function setPagina(int $pagina): self
    {
        $this->setVisited('pagina');

        $this->pagina = $pagina;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->setVisited('descricao');

        $this->descricao = $descricao;

        return $this;
    }

    public function getComponenteDigital(): ?EntityInterface
    {
        return $this->componenteDigital;
    }

    public function setComponenteDigital(?EntityInterface $componenteDigital): self
    {
        $this->setVisited('componenteDigital');

        $this->componenteDigital = $componenteDigital;

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

    public function getJuntada(): ?EntityInterface
    {
        return $this->juntada;
    }

    public function setJuntada(?EntityInterface $juntada): self
    {
        $this->setVisited('juntada');

        $this->juntada = $juntada;

        return $this;
    }

    public function getCorHexadecimal(): ?string
    {
        return $this->corHexadecimal;
    }

    public function setCorHexadecimal(?string $corHexadecimal): self
    {
        $this->setVisited('corHexadecimal');

        $this->corHexadecimal = $corHexadecimal;

        return $this;
    }

    public function getTextoReferencia(): ?string
    {
        return $this->textoReferencia;
    }

    public function setTextoReferencia(?string $textoReferencia): self
    {
        $this->setVisited('textoReferencia');

        $this->textoReferencia = $textoReferencia;

        return $this;
    }
}
