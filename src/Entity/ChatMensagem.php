<?php

declare(strict_types=1);
/**
 * /src/Entity/ChatMensagem.php.
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DMS\Filter\Rules as Filter;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChatMensagem.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_chat_mensagem')]
class ChatMensagem implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $mensagem = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Usuario', inversedBy: 'vinculacoesUsuarios')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    protected ?Usuario $usuario = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Chat', inversedBy: 'mensagens')]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id', nullable: false)]
    protected ?Chat $chat = null;

    #[ORM\ManyToOne(targetEntity: 'ChatMensagem')]
    #[ORM\JoinColumn(name: 'mensagem_id', referencedColumnName: 'id', nullable: true)]
    protected ?ChatMensagem $replyTo = null;

    #[ORM\ManyToOne(targetEntity: 'ComponenteDigital')]
    #[ORM\JoinColumn(name: 'componente_digital_id', referencedColumnName: 'id', nullable: true)]
    protected ?ComponenteDigital $componenteDigital = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function setChat(Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }

    public function getMensagem(): ?string
    {
        return $this->mensagem;
    }

    public function setMensagem(?string $mensagem): self
    {
        $this->mensagem = $mensagem;

        return $this;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getReplyTo(): self|null
    {
        return $this->replyTo;
    }

    public function setReplyTo(?self $replyTo): self
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    public function getComponenteDigital(): ComponenteDigital|null
    {
        return $this->componenteDigital;
    }

    public function setComponenteDigital(?ComponenteDigital $componenteDigital): self
    {
        $this->componenteDigital = $componenteDigital;

        return $this;
    }
}
