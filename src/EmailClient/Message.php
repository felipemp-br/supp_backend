<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\EmailClient;

use DateTime;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Class Message.
 */
class Message extends RestDto
{
    use IdUuid;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    protected ?string $subject = '';

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $date = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Serializer\Groups(['default'])]
    #[OA\Property(type: 'string')]
    protected ?string $htmlBody = '';

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    protected ?bool $readed = false;

    #[OA\Property(ref: new Model(type: Folder::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\EmailClient\Address')]
    protected ?Folder $folder = null;

    #[OA\Property(ref: new Model(type: Address::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\EmailClient\Address')]
    protected ?Address $from = null;

    #[Serializer\SkipWhenEmpty]
    #[OA\Property(ref: new Model(type: Address::class))]
    protected array $to = [];

    #[Serializer\SkipWhenEmpty]
    #[OA\Property(ref: new Model(type: Attachment::class))]
    protected array $attachments = [];

    #[Serializer\SkipWhenEmpty]
    #[OA\Property(ref: new Model(type: Address::class))]
    protected array $cc = [];

    #[Serializer\SkipWhenEmpty]
    #[OA\Property(ref: new Model(type: Address::class))]
    protected array $bcc = [];

    public function getFrom(): ?Address
    {
        return $this->from;
    }

    public function setFrom(?Address $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): array
    {
        return $this->to;
    }

    public function setTo(array $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function addTo(Address $to): self
    {
        $this->to[] = $to;

        return $this;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function setCc(array $cc): self
    {
        $this->cc = $cc;

        return $this;
    }

    public function addCc(Address $cc): self
    {
        $this->cc[] = $cc;

        return $this;
    }

    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function setBcc(array $bcc): self
    {
        $this->bcc = $bcc;

        return $this;
    }

    public function addBcc(Address $bcc): self
    {
        $this->bcc[] = $bcc;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(?DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHtmlBody(): ?string
    {
        return $this->htmlBody;
    }

    public function setHtmlBody(?string $htmlBody): self
    {
        $this->htmlBody = $htmlBody;

        return $this;
    }

    public function getFolder(): ?Folder
    {
        return $this->folder;
    }

    public function setFolder(?Folder $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    public function getReaded(): ?bool
    {
        return $this->readed;
    }

    public function setReaded(?bool $readed): self
    {
        $this->readed = $readed;

        return $this;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function setAttachments(array $attachments): self
    {
        $this->attachments = $attachments;

        return $this;
    }

    public function addAttachment(Attachment $attachment): self
    {
        $this->attachments[] = $attachment;

        return $this;
    }
}
