<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\EmailClient;

use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Class Folder.
 */
class Folder extends RestDto
{
    use IdUuid;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    protected ?string $path = '';

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    protected ?string $name = '';

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    protected ?string $parsedName = '';

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    protected ?string $fullname = '';

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $hasChildren = false;

    #[Serializer\SkipWhenEmpty]
    #[OA\Property(ref: new Model(type: self::class))]
    protected array $childrens = [];

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    protected ?int $totalMessages = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    protected ?int $recentMessages = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    protected ?int $unreadMessages = null;

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getParsedName(): ?string
    {
        return $this->parsedName;
    }

    public function setParsedName(?string $parsedName): self
    {
        $this->parsedName = $parsedName;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(?string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getHasChildren(): ?bool
    {
        return $this->hasChildren;
    }

    public function setHasChildren(?bool $hasChildren): self
    {
        $this->hasChildren = $hasChildren;

        return $this;
    }

    public function getChildrens(): array
    {
        return $this->childrens;
    }

    public function setChildrens(array $childrens): self
    {
        $this->childrens = $childrens;

        return $this;
    }

    public function addChildren(self $folder): self
    {
        $this->childrens[] = $folder;

        return $this;
    }

    public function getTotalMessages(): ?int
    {
        return $this->totalMessages;
    }

    public function setTotalMessages(?int $totalMessages): self
    {
        $this->totalMessages = $totalMessages;

        return $this;
    }

    public function getRecentMessages(): ?int
    {
        return $this->recentMessages;
    }

    public function setRecentMessages(?int $recentMessages): self
    {
        $this->recentMessages = $recentMessages;

        return $this;
    }

    public function getUnreadMessages(): ?int
    {
        return $this->unreadMessages;
    }

    public function setUnreadMessages(?int $unreadMessages): self
    {
        $this->unreadMessages = $unreadMessages;

        return $this;
    }
}
