<?php

declare(strict_types=1);
/**
 * /src/Entity/ChatParticipante.php.
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChatParticipante.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[ORM\Table(name: 'ad_chat_participante')]
class ChatParticipante implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Id;
    use Uuid;

    #[ORM\Column(name: 'administrador', type: 'boolean', nullable: false)]
    protected bool $administrador = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false)]
    protected ?Usuario $usuario = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Chat', inversedBy: 'participantes')]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id', nullable: false)]
    protected ?Chat $chat = null;

    #[ORM\Column(name: 'ultima_visualizacao', type: 'datetime', nullable: true)]
    protected ?DateTime $ultimaVisualizacao = null;

    #[ORM\Column(name: 'mensagens_nao_lidas', type: 'integer', nullable: true)]
    protected ?int $mensagensNaoLidas = 0;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getAdministrador(): bool
    {
        return $this->administrador;
    }

    public function setAdministrador(bool $administrador): self
    {
        $this->administrador = $administrador;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
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

    public function getUltimaVisualizacao(): ?DateTime
    {
        return $this->ultimaVisualizacao;
    }

    public function setUltimaVisualizacao(?DateTime $ultimaVisualizacao): self
    {
        $this->ultimaVisualizacao = $ultimaVisualizacao;

        return $this;
    }

    public function getMensagensNaoLidas(): int
    {
        return (int) $this->mensagensNaoLidas;
    }

    public function setMensagensNaoLidas(int $mensagensNaoLidas): self
    {
        $this->mensagensNaoLidas = $mensagensNaoLidas;

        return $this;
    }
}
