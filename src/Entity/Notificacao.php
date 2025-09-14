<?php

declare(strict_types=1);
/**
 * /src/Entity/Notificacao.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
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
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class Notificacao.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_notificacao')]
class Notificacao implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Id;
    use Uuid;
    use Softdeleteable;

    /**
     * Usuário que remeteu a notificação, pode ser nulo nos casos de notificação criada pelo sistema.
     */
    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'remetente_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $remetente = null;

    /**
     * Usuário destinatário da notificação, nunca pode ser nulo.
     */
    #[Assert\NotNull(message: 'O destinatário da notificação não pode ser nulo')]
    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'destinatario_id', referencedColumnName: 'id', nullable: false)]
    protected Usuario $destinatario;

    /**
     * Modalidade da notificação.
     */
    #[Assert\NotNull(message: 'A modalidade da notificação não pode ser nula')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeNotificacao')]
    #[ORM\JoinColumn(name: 'mod_notificacao_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeNotificacao $modalidadeNotificacao = null;

    /**
     * Todas as notificações devem possuir uma data e hora de expiração.
     */
    #[Assert\NotNull(message: 'A data/hora de expiração da notificação não pode ser nula!')]
    #[ORM\Column(name: 'data_hora_expiracao', type: 'datetime', nullable: false)]
    protected DateTime $dataHoraExpiracao;

    /**
     * Registro de data e hora de leitura da notificação pelo usuário.
     */
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_leitura', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraLeitura = null;

    /**
     * Conteúdo da notificação.
     */
    #[Assert\NotBlank(message: 'Conteúdo não pode estar em branco.')]
    #[Assert\Length(max: 4000, maxMessage: 'O conteúdo da mensagem deve ter no máximo 4000 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'conteudo', type: 'string', length: 4000, nullable: false)]
    protected string $conteudo = '';

    /**
     * Marcação de urgência da notificação.
     */
    #[ORM\Column(name: 'urgente', type: 'boolean', nullable: false)]
    protected bool $urgente = false;

    /**
     * Contexto para exibição da notificação.
     */
    #[ORM\Column(name: 'contexto', type: 'string', nullable: true)]
    protected ?string $contexto = '';

    #[ORM\ManyToOne(targetEntity: 'TipoNotificacao')]
    #[ORM\JoinColumn(name: 'tipo_notificacao_id', referencedColumnName: 'id', nullable: true)]
    protected ?TipoNotificacao $tipoNotificacao = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getRemetente(): ?Usuario
    {
        return $this->remetente;
    }

    public function setRemetente(?Usuario $remetente): self
    {
        $this->remetente = $remetente;

        return $this;
    }

    public function getDestinatario(): Usuario
    {
        return $this->destinatario;
    }

    public function setDestinatario(Usuario $destinatario): self
    {
        $this->destinatario = $destinatario;

        return $this;
    }

    public function getModalidadeNotificacao(): ModalidadeNotificacao
    {
        return $this->modalidadeNotificacao;
    }

    public function setModalidadeNotificacao(ModalidadeNotificacao $modalidadeNotificacao): self
    {
        $this->modalidadeNotificacao = $modalidadeNotificacao;

        return $this;
    }

    public function getDataHoraExpiracao(): DateTime
    {
        return $this->dataHoraExpiracao;
    }

    public function setDataHoraExpiracao(DateTime $dataHoraExpiracao): self
    {
        $this->dataHoraExpiracao = $dataHoraExpiracao;

        return $this;
    }

    public function getDataHoraLeitura(): ?DateTime
    {
        return $this->dataHoraLeitura;
    }

    public function setDataHoraLeitura(?DateTime $dataHoraLeitura): self
    {
        $this->dataHoraLeitura = $dataHoraLeitura;

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

    public function getUrgente(): bool
    {
        return $this->urgente;
    }

    public function setUrgente(bool $urgente): self
    {
        $this->urgente = $urgente;

        return $this;
    }

    public function getContexto(): ?string
    {
        return $this->contexto;
    }

    public function setContexto(?string $contexto): self
    {
        $this->contexto = $contexto;

        return $this;
    }

    public function getTipoNotificacao(): ?TipoNotificacao
    {
        return $this->tipoNotificacao;
    }

    public function setTipoNotificacao(?EntityInterface $tipoNotificacao): self
    {
        $this->tipoNotificacao = $tipoNotificacao;

        return $this;
    }

    /**
     * @throws Exception
     */
    #[Assert\Callback]
    public function isDateIntervalValid(ExecutionContextInterface $context): void
    {
        $agora = new DateTime();

        if (!$this->getDataHoraLeitura() && $this->getDataHoraExpiracao() < $agora) {
            $context->buildViolation('A data/hora de expiração da notificação deve ser no futuro!')
                ->atPath('dataHoraExpiracao')
                ->addViolation();
        }

        $umAnoDepois = clone $agora;
        $umAnoDepois->modify('+1 year');

        if ($this->getDataHoraExpiracao() > $umAnoDepois) {
            $context->buildViolation('A data/hora de expiração da notificação ultrassou o limite de 1 (um) ano!')
                ->atPath('dataHoraExpiracao')
                ->addViolation();
        }
    }
}
