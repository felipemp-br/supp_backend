<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/VinculacaoOrgaoCentralMetadados.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoOrgaoCentralMetadados.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'tese' => 'tese',
        'modalidadeOrgaoCentral' => 'modalidadeOrgaoCentral',
        'documento' => 'documento',
        'modelo' => 'modelo',
        'repositorio' => 'repositorio',
        'assuntoAdministrativo' => 'assuntoAdministrativo',
        'especieSetor' => 'especieSetor',
        'especieDocumento' => 'especieDocumento',
        'especieProcesso' => 'especieProcesso',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\VinculacaoOrgaoCentralMetadados',
    message: 'Informações já vinculadas ao Órgão Central!'
)]
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/vinculacao_orgao_central_metadados/{id}',
    jsonLDType: 'VinculacaoOrgaoCentralMetadados',
    jsonLDContext: '/api/doc/#model-VinculacaoOrgaoCentralMetadados'
)]
#[Form\Form]
class VinculacaoOrgaoCentralMetadados extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Tese',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tese'))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tese')]
    protected ?EntityInterface $tese = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral'))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeOrgaoCentral')]
    protected ?EntityInterface $modalidadeOrgaoCentral = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Documento',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento'))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Modelo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo'))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo')]
    protected ?EntityInterface $modelo = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Repositorio',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio'))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Repositorio')]
    protected ?EntityInterface $repositorio = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: 'SuppCore\AdministrativoBackend\Api\V1\DTO\AssuntoAdministrativo'))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\AssuntoAdministrativo')]
    protected ?EntityInterface $assuntoAdministrativo = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieSetor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieSetor'))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieSetor')]
    protected ?EntityInterface $especieSetor = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieProcesso',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieProcesso'))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieProcesso')]
    protected ?EntityInterface $especieProcesso = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\EspecieDocumento',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieDocumento'))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieDocumento')]
    protected ?EntityInterface $especieDocumento = null;

    public function getTese(): ?EntityInterface
    {
        return $this->tese;
    }

    /**
     * @return $this
     */
    public function setTese(?EntityInterface $tese): self
    {
        $this->setVisited('tese');
        $this->tese = $tese;

        return $this;
    }

    public function getModalidadeOrgaoCentral(): ?EntityInterface
    {
        return $this->modalidadeOrgaoCentral;
    }

    /**
     * @return $this
     */
    public function setModalidadeOrgaoCentral(?EntityInterface $modalidadeOrgaoCentral): self
    {
        $this->setVisited('modalidadeOrgaoCentral');
        $this->modalidadeOrgaoCentral = $modalidadeOrgaoCentral;

        return $this;
    }

    public function getDocumento(): ?EntityInterface
    {
        return $this->documento;
    }

    /**
     * @return $this
     */
    public function setDocumento(?EntityInterface $documento): self
    {
        $this->setVisited('documento');
        $this->documento = $documento;

        return $this;
    }

    public function getModelo(): ?EntityInterface
    {
        return $this->modelo;
    }

    /**
     * @return $this
     */
    public function setModelo(?EntityInterface $modelo): self
    {
        $this->setVisited('modelo');
        $this->modelo = $modelo;

        return $this;
    }

    public function getRepositorio(): ?EntityInterface
    {
        return $this->repositorio;
    }

    /**
     * @return $this
     */
    public function setRepositorio(?EntityInterface $repositorio): self
    {
        $this->setVisited('repositorio');
        $this->repositorio = $repositorio;

        return $this;
    }

    public function getAssuntoAdministrativo(): ?EntityInterface
    {
        return $this->assuntoAdministrativo;
    }

    /**
     * @return $this
     */
    public function setAssuntoAdministrativo(?EntityInterface $assuntoAdministrativo): self
    {
        $this->setVisited('assuntoAdministrativo');
        $this->assuntoAdministrativo = $assuntoAdministrativo;

        return $this;
    }

    public function getEspecieSetor(): ?EntityInterface
    {
        return $this->especieSetor;
    }

    /**
     * @return $this
     */
    public function setEspecieSetor(?EntityInterface $especieSetor): self
    {
        $this->setVisited('especieSetor');
        $this->especieSetor = $especieSetor;

        return $this;
    }

    public function getEspecieProcesso(): ?EntityInterface
    {
        return $this->especieProcesso;
    }

    /**
     * @return $this
     */
    public function setEspecieProcesso(?EntityInterface $especieProcesso): self
    {
        $this->setVisited('especieProcesso');
        $this->especieProcesso = $especieProcesso;

        return $this;
    }

    public function getEspecieDocumento(): ?EntityInterface
    {
        return $this->especieDocumento;
    }

    /**
     * @return $this
     */
    public function setEspecieDocumento(?EntityInterface $especieDocumento): self
    {
        $this->setVisited('especieDocumento');
        $this->especieDocumento = $especieDocumento;

        return $this;
    }
}
