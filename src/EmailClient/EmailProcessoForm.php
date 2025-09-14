<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\EmailClient;

use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ContaEmail as ContaEmailDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Class Message.
 */
#[Form\Form]
class EmailProcessoForm extends RestDto
{
    use IdUuid;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'string')]
    protected ?string $tipo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'string')]
    protected string|int|null $folderIdentifier = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'string')]
    protected string|int|null $messageIdentifier = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Processo',
            'required' => false,
        ]
    )]
    #[Serializer\Groups(['default'])]
    #[OA\Property(ref: new Model(type: ProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Processo')]
    protected ?EntityInterface $processo = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ContaEmail',
            'required' => true,
        ]
    )]
    #[Serializer\Groups(['default'])]
    #[OA\Property(ref: new Model(type: ContaEmailDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ContaEmail')]
    protected ?EntityInterface $contaEmail = null;

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    /**
     * @param string|null $tipo
     */
    public function setTipo(string $tipo): self
    {
        $this->setVisited('tipo');
        $this->tipo = $tipo;

        return $this;
    }

    public function getFolderIdentifier(): string|int|null
    {
        return $this->folderIdentifier;
    }

    public function setFolderIdentifier(string|int|null $folderIdentifier): self
    {
        $this->setVisited('folderIdentifier');
        $this->folderIdentifier = $folderIdentifier;

        return $this;
    }

    public function getMessageIdentifier(): string|int|null
    {
        return $this->messageIdentifier;
    }

    public function setMessageIdentifier(string|int|null $messageIdentifier): self
    {
        $this->setVisited('messageIdentifier');
        $this->messageIdentifier = $messageIdentifier;

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

    public function getContaEmail(): ?EntityInterface
    {
        return $this->contaEmail;
    }

    public function setContaEmail(?EntityInterface $contaEmail): self
    {
        $this->setVisited('contaEmail');
        $this->contaEmail = $contaEmail;

        return $this;
    }
}
