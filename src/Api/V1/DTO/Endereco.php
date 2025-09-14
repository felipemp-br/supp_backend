<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Endereco.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Municipio as MunicipioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pais as PaisDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
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
 * Class Endereco.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/endereco/{id}',
    jsonLDType: 'Endereco',
    jsonLDContext: '/api/doc/#model-Endereco'
)]
#[Form\Form]
class Endereco extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    use OrigemDados;

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
    protected ?string $bairro = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 8, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Assert\Regex(pattern: '/\d{8}/', message: 'CEP Inválido!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\Digits]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $cep = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Municipio',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: MunicipioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Municipio')]
    protected ?EntityInterface $municipio = null;

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
    protected ?string $complemento = null;

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
    protected ?string $logradouro = null;

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
    protected ?string $numero = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Pais',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: PaisDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Pais')]
    protected ?EntityInterface $pais = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected bool $principal = true;

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

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Pessoa',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: PessoaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa')]
    protected ?EntityInterface $pessoa = null;

    public function getBairro(): ?string
    {
        return $this->bairro;
    }

    /**
     * @return $this
     */
    public function setBairro(?string $bairro): self
    {
        $this->setVisited('bairro');

        $this->bairro = $bairro;

        return $this;
    }

    public function getCep(): ?string
    {
        return $this->cep;
    }

    /**
     * @return $this
     */
    public function setCep(?string $cep): self
    {
        $this->setVisited('cep');

        $this->cep = $cep;

        return $this;
    }

    public function getMunicipio(): ?EntityInterface
    {
        return $this->municipio;
    }

    public function setMunicipio(?EntityInterface $municipio): self
    {
        $this->setVisited('municipio');

        $this->municipio = $municipio;

        return $this;
    }

    public function getComplemento(): ?string
    {
        return $this->complemento;
    }

    /**
     * @return $this
     */
    public function setComplemento(?string $complemento): self
    {
        $this->setVisited('complemento');

        $this->complemento = $complemento;

        return $this;
    }

    public function getLogradouro(): ?string
    {
        return $this->logradouro;
    }

    /**
     * @return $this
     */
    public function setLogradouro(?string $logradouro): self
    {
        $this->setVisited('logradouro');

        $this->logradouro = $logradouro;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    /**
     * @return $this
     */
    public function setNumero(?string $numero): self
    {
        $this->setVisited('numero');

        $this->numero = $numero;

        return $this;
    }

    public function getPais(): ?EntityInterface
    {
        return $this->pais;
    }

    public function setPais(?EntityInterface $pais): self
    {
        $this->setVisited('pais');

        $this->pais = $pais;

        return $this;
    }

    public function getPrincipal(): ?bool
    {
        return $this->principal;
    }

    /**
     * @return $this
     */
    public function setPrincipal(?bool $principal): self
    {
        $this->setVisited('principal');

        $this->principal = $principal;

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

    public function getPessoa(): ?EntityInterface
    {
        return $this->pessoa;
    }

    public function setPessoa(?EntityInterface $pessoa): self
    {
        $this->setVisited('pessoa');

        $this->pessoa = $pessoa;

        return $this;
    }
}
