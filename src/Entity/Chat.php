<?php

declare(strict_types=1);
/**
 * /src/Entity/Chat.php.
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DMS\Filter\Rules as Filter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Chat.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Enableable]
#[ORM\Table(name: 'ad_chat')]
class Chat implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Ativo;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $nome = '';

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $descricao = '';

    #[ORM\Column(name: 'grupo', type: 'boolean', nullable: false)]
    protected bool $grupo = false;

    #[ORM\ManyToOne(targetEntity: 'ComponenteDigital')]
    #[ORM\JoinColumn(name: 'capa_id', referencedColumnName: 'id', nullable: true)]
    protected ?ComponenteDigital $capa = null;

    #[ORM\ManyToOne(targetEntity: 'ChatMensagem', cascade: ['all'])]
    #[ORM\JoinColumn(name: 'ultima_mensagem_id', referencedColumnName: 'id', nullable: true)]
    protected ?ChatMensagem $ultimaMensagem = null;

    /**
     * @var Collection|ArrayCollection<ChatParticipante>|null
     */
    #[ORM\OneToMany(mappedBy: 'chat', targetEntity: 'ChatParticipante', cascade: ['all'])]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id')]
    protected ArrayCollection|Collection|null $participantes;

    /**
     * @var Collection|ArrayCollection<ChatParticipante>|null
     */
    #[ORM\OneToMany(mappedBy: 'chat', targetEntity: 'ChatMensagem', cascade: ['all'])]
    #[ORM\JoinColumn(name: 'chat_id', referencedColumnName: 'id')]
    protected ArrayCollection|Collection|null $mensagens;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->participantes = new ArrayCollection();
    }

    public function getNome(): string|null
    {
        return $this->nome;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function getDescricao(): string|null
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getCapa(): ComponenteDigital|null
    {
        return $this->capa;
    }

    public function setCapa(?ComponenteDigital $capa): self
    {
        $this->capa = $capa;

        return $this;
    }

    public function getGrupo(): bool
    {
        return $this->grupo;
    }

    public function setGrupo(bool $grupo): self
    {
        $this->grupo = $grupo;

        return $this;
    }

    public function addParticipante(ChatParticipante $participante): self
    {
        if (!$this->participantes->contains($participante)) {
            $this->participantes[] = $participante;
            $participante->setChat($this);
        }

        return $this;
    }

    public function removeParticipante(ChatParticipante $participante): self
    {
        if ($this->participantes->contains($participante)) {
            $this->participantes->removeElement($participante);
        }

        return $this;
    }

    public function getParticipantes(): ArrayCollection|Collection
    {
        return $this->participantes;
    }

    public function getUltimaMensagem(): ?ChatMensagem
    {
        return $this->ultimaMensagem;
    }

    public function setUltimaMensagem(?ChatMensagem $ultimaMensagem): self
    {
        $this->ultimaMensagem = $ultimaMensagem;

        return $this;
    }
}
