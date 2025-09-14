<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Timeline;

use DateTime;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Timeline Event Class.
 */
class TimelineEvent extends RestDto
{
    #[Serializer\Exclude]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $id = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    protected ?string $message = '';

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $eventDate = null;

    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected RestDtoInterface $usuario;

    #[OA\Property(ref: new Model(type: TarefaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa')]
    protected RestDtoInterface $tarefa;

    #[OA\Property(ref: new Model(type: EventType::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Timeline\EventType')]
    protected RestDtoInterface $typeEvent;

    #[OA\Property(type: 'string')]
    protected ?string $objectContext = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $lastEvent = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $firstEvent = false;

    public function getId(): int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->setVisited('message');
        $this->message = $message;

        return $this;
    }

    public function getEventDate(): ?DateTime
    {
        return $this->eventDate;
    }

    public function setEventDate(?DateTime $eventDate): self
    {
        $this->setVisited('eventDate');
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getUsuario(): RestDtoInterface
    {
        return $this->usuario;
    }

    public function setUsuario(RestDtoInterface $usuario): self
    {
        $this->setVisited('usuario');
        $this->usuario = $usuario;

        return $this;
    }

    public function getTypeEvent(): RestDtoInterface
    {
        return $this->typeEvent;
    }

    public function setTypeEvent(RestDtoInterface $typeEvent): self
    {
        $this->setVisited('typeEvent');
        $this->typeEvent = $typeEvent;

        return $this;
    }

    public function getObjectContext(): ?string
    {
        return $this->objectContext;
    }

    public function setObjectContext(?string $objectContext): self
    {
        $this->setVisited('objectContext');
        $this->objectContext = $objectContext;

        return $this;
    }

    public function getTarefa(): RestDtoInterface
    {
        return $this->tarefa;
    }

    public function setTarefa(RestDtoInterface $tarefa): self
    {
        $this->setVisited('tarefa');
        $this->tarefa = $tarefa;

        return $this;
    }

    public function getLastEvent(): bool
    {
        return $this->lastEvent;
    }

    public function setLastEvent(bool $lastEvent): self
    {
        $this->setVisited('lastEvent');
        $this->lastEvent = $lastEvent;

        return $this;
    }

    public function getFirstEvent(): bool
    {
        return $this->firstEvent;
    }

    public function setFirstEvent(bool $firstEvent): self
    {
        $this->setVisited('firstEvent');
        $this->firstEvent = $firstEvent;

        return $this;
    }
}
