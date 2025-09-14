<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Entity; // Ajuste o namespace se necessário

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;       // Para a chave primária auto-incrementada
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable; // Para criadoEm/atualizadoEm
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;      // Para o identificador único universal
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TarefaMensagem.
 *
 * Representa uma mensagem no chat vinculado a uma Tarefa.
 */
#[ORM\Entity(repositoryClass: TarefaMensagemRepository::class)] // <--- ADICIONE OU MODIFIQUE ESTA LINHA
#[ORM\Table(
    name: 'ad_tarefa_mensagem',
    indexes: [ // Definindo índices aqui é uma forma
        new ORM\Index(columns: ['tarefa_id'], name: 'idx_tarefa_mensagem_tarefa'),
        new ORM\Index(columns: ['usuario_id'], name: 'idx_tarefa_mensagem_usuario'),
        new ORM\Index(columns: ['data_hora_envio'], name: 'idx_tarefa_mensagem_data_envio'),
        // new ORM\Index(columns: ['uuid'], name: 'idx_tarefa_mensagem_uuid_normal') // Índice normal, se precisar além do unique
    ],
    uniqueConstraints: [ // Definindo unique constraints aqui
        new ORM\UniqueConstraint(name: 'uq_tarefa_mensagem_uuid', columns: ['uuid'])
    ]
)]
#[Gedmo\Loggable]
class TarefaMensagem implements EntityInterface
{
    use Id;           // Chave primária numérica, auto-incrementada
    use Uuid;         // Identificador UUID
    use Timestampable;  // Adiciona criadoEm e atualizadoEm

    #[Assert\NotNull(message: 'A tarefa da mensagem não pode ser nula.')]
    #[ORM\ManyToOne(targetEntity: Tarefa::class)]
    #[ORM\JoinColumn(name: 'tarefa_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    protected ?Tarefa $tarefa = null;

    #[Assert\NotNull(message: 'O usuário da mensagem não pode ser nulo.')]
    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: false, onDelete: 'SET NULL')]
    protected ?Usuario $usuario = null;

    #[Assert\NotBlank(message: 'O nome do usuário não pode estar em branco.')]
    #[Assert\Length(max: 255, maxMessage: 'O nome do usuário deve ter no máximo {{ limit }} caracteres.')]
    #[ORM\Column(name: 'usuario_nome', type: 'string', length: 255, nullable: false)]
    protected string $usuarioNome;

    #[Assert\NotBlank(message: 'O conteúdo da mensagem não pode estar em branco.')]
    #[ORM\Column(name: 'conteudo', type: 'text', nullable: false)]
    protected string $conteudo;

    /**
     * Data e hora em que a mensagem foi enviada.
     * Se preferir usar o `criadoEm` do Timestampable, remova esta propriedade
     * e ajuste o getter para retornar $this->getCriadoEm().
     * Mantendo explícito por enquanto.
     */
    #[Assert\NotNull(message: 'A data e hora de envio não pode ser nula.')]
    #[ORM\Column(name: 'data_hora_envio', type: 'datetime', nullable: false)]
    protected DateTime $dataHoraEnvio;

    /**
     * Constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setUuid(); // Inicializa o UUID
        $this->dataHoraEnvio = new DateTime(); // Ou defina ao persistir
        // O Timestampable cuidará do criadoEm e atualizadoEm
    }

    // Getters e Setters permanecem os mesmos da versão anterior,
    // exceto que agora você terá getId() e getUuid() disponíveis.

    public function getTarefa(): ?Tarefa
    {
        return $this->tarefa;
    }

    public function setTarefa(?Tarefa $tarefa): self
    {
        $this->tarefa = $tarefa;
        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;
        if ($usuario) {
            $this->setUsuarioNome($usuario->getNome());
        }
        return $this;
    }

    public function getUsuarioNome(): string
    {
        return $this->usuarioNome;
    }

    public function setUsuarioNome(string $usuarioNome): self
    {
        $this->usuarioNome = $usuarioNome;
        return $this;
    }

    public function getConteudo(): string
    {
        return $this->conteudo;
    }

    public function setConteudo(string $conteudo): self
    {
        $this->conteudo = $conteudo;
        return $this;
    }

    public function getDataHoraEnvio(): DateTime
    {
        return $this->dataHoraEnvio;
    }

    public function setDataHoraEnvio(DateTime $dataHoraEnvio): self
    {
        $this->dataHoraEnvio = $dataHoraEnvio;
        return $this;
    }

    public function getUsuarioId(): ?int
    {
        return $this->usuario ? $this->usuario->getId() : null;
    }

    // Se optar por usar o `criadoEm` do Timestampable como data de envio:
    // public function getDataHoraEnvio(): ?\DateTimeInterface // DateTimeInterface é mais geral
    // {
    //     return $this->getCriadoEm();
    // }
}