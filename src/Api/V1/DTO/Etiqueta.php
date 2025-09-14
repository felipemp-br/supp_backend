<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Etiqueta.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeEtiqueta as ModalidadeEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral as ModalidadeOrgaoCentralDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\Descricao;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Etiqueta as EtiquetaEntity;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral as ModalidadeOrgaoCentralEntity;
use SuppCore\AdministrativoBackend\Entity\Setor as SetorEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Etiqueta.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/etiqueta/{id}',
    jsonLDType: 'Etiqueta',
    jsonLDContext: '/api/doc/#model-Etiqueta'
)]
#[Form\Form]
class Etiqueta extends RestDto
{
    use IdUuid;
    use Descricao;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

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
    protected ?string $corHexadecimal = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeEtiqueta',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ModalidadeEtiquetaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeEtiqueta')]
    protected ?EntityInterface $modalidadeEtiqueta = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ],
        methods: [
            new Form\Method('createMethod', roles: ['ROLE_ROOT']),
            new Form\Method('updateMethod', roles: ['ROLE_ROOT']),
            new Form\Method('patchMethod', roles: ['ROLE_ROOT']),
        ]
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $sistema = null;

    /**
     * Nome.
     */
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
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: 'O campo nome deve ter no mínimo 3 caracteres!',
        maxMessage: 'O campo nome deve ter no máximo 20 caracteres!'
    )]
    #[Assert\Length(max: 20, maxMessage: 'O campo nome deve ter no máximo 20 caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $nome = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    protected ?EntityInterface $usuario = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    protected ?EntityInterface $setor = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    protected ?EntityInterface $unidade = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral',
            'required' => false,
        ]
    )]
    protected ?EntityInterface $modalidadeOrgaoCentral = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean')]
    #[DTOMapper\Property]
    protected ?bool $privada = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected ?bool $ativo = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    #[Assert\Choice(choices: EtiquetaEntity::TIPO_EXECUCAO_ACAO_SUGESTAO_ALLOWED)]
    protected ?int $tipoExecucaoAcaoSugestao = null;

    public function setAtivo(?bool $ativo): self
    {
        $this->setVisited('ativo');

        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo|null.
     *
     * @return bool
     */
    public function getAtivo(): ?bool
    {
        return $this->ativo;
    }

    public function setNome(?string $nome): self
    {
        $this->setVisited('nome');

        $this->nome = $nome;

        return $this;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setSistema(?bool $sistema): self
    {
        $this->setVisited('sistema');

        $this->sistema = $sistema;

        return $this;
    }

    /**
     * Get sistema|null.
     *
     * @return bool
     */
    public function getSistema(): ?bool
    {
        return $this->sistema;
    }

    public function getModalidadeEtiqueta(): ?EntityInterface
    {
        return $this->modalidadeEtiqueta;
    }

    public function setModalidadeEtiqueta(?EntityInterface $modalidadeEtiqueta): self
    {
        $this->setVisited('modalidadeEtiqueta');

        $this->modalidadeEtiqueta = $modalidadeEtiqueta;

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

    /**
     * @return UsuarioDTO|UsuarioEntity|EntityInterface|int|null
     */
    public function getUsuario(): ?EntityInterface
    {
        return $this->usuario;
    }

    /**
     * @param UsuarioDTO|UsuarioEntity|EntityInterface|int|null $usuario
     */
    public function setUsuario(?EntityInterface $usuario): self
    {
        $this->setVisited('usuario');

        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @return SetorDTO|SetorEntity|EntityInterface|int|null
     */
    public function getSetor(): ?EntityInterface
    {
        return $this->setor;
    }

    /**
     * @param SetorDTO|SetorEntity|EntityInterface|int|null $setor
     */
    public function setSetor(?EntityInterface $setor): self
    {
        $this->setVisited('setor');

        $this->setor = $setor;

        return $this;
    }

    /**
     * @return SetorDTO|SetorEntity|EntityInterface|int|null
     */
    public function getUnidade(): ?EntityInterface
    {
        return $this->unidade;
    }

    /**
     * @param SetorDTO|SetorEntity|EntityInterface|int|null $unidade
     */
    public function setUnidade(?EntityInterface $unidade): self
    {
        $this->setVisited('unidade');

        $this->unidade = $unidade;

        return $this;
    }

    /**
     * @return ModalidadeOrgaoCentralDTO|ModalidadeOrgaoCentralEntity|EntityInterface|int|null
     */
    public function getModalidadeOrgaoCentral(): ?EntityInterface
    {
        return $this->modalidadeOrgaoCentral;
    }

    /**
     * @param ModalidadeOrgaoCentralDTO|ModalidadeOrgaoCentralEntity|EntityInterface|int|null $modalidadeOrgaoCentral
     */
    public function setModalidadeOrgaoCentral(?EntityInterface $modalidadeOrgaoCentral): self
    {
        $this->setVisited('modalidadeOrgaoCentral');

        $this->modalidadeOrgaoCentral = $modalidadeOrgaoCentral;

        return $this;
    }

    public function getPrivada(): ?bool
    {
        return $this->privada;
    }

    public function setPrivada(?bool $privada): self
    {
        $this->setVisited('privada');

        $this->privada = $privada;

        return $this;
    }

    public function getTipoExecucaoAcaoSugestao(): ?int
    {
        return $this->tipoExecucaoAcaoSugestao;
    }

    /**
     * @return $this
     */
    public function setTipoExecucaoAcaoSugestao(?int $tipoExecucaoAcaoSugestao): self
    {
        $this->setVisited('tipoExecucaoAcaoSugestao');
        $this->tipoExecucaoAcaoSugestao = $tipoExecucaoAcaoSugestao;

        return $this;
    }
}
