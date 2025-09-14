<?php

declare(strict_types=1);
/**
 * /src/Entity/Tramitacao.php.
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

/**
 * Class Tramitacao.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_tramitacao')]
class Tramitacao implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $observacao = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected ?bool $urgente = false;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'tramitacoes')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: false)]
    protected Processo $processo;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_origem_id', referencedColumnName: 'id', nullable: false)]
    protected Setor $setorOrigem;

    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_destino_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $setorDestino = null;

    #[ORM\ManyToOne(targetEntity: 'Pessoa')]
    #[ORM\JoinColumn(name: 'pessoa_destino_id', referencedColumnName: 'id', nullable: true)]
    protected ?Pessoa $pessoaDestino = null;

    #[ORM\Column(name: 'data_hora_recebimento', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraRecebimento = null;

    #[ORM\ManyToOne(targetEntity: 'Usuario')]
    #[ORM\JoinColumn(name: 'usuario_recebimento_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuarioRecebimento = null;

    #[ORM\Column(name: 'mecanismo_remessa', type: 'string', nullable: true)]
    protected ?string $mecanismoRemessa = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): self
    {
        $this->observacao = $observacao;

        return $this;
    }

    public function getUrgente(): ?bool
    {
        return $this->urgente;
    }

    public function setUrgente(bool $urgente): self
    {
        $this->urgente = $urgente;

        return $this;
    }

    public function getProcesso(): Processo
    {
        return $this->processo;
    }

    public function setProcesso(Processo $processo): self
    {
        $this->processo = $processo;

        return $this;
    }

    public function getSetorOrigem(): Setor
    {
        return $this->setorOrigem;
    }

    public function setSetorOrigem(Setor $setorOrigem): self
    {
        $this->setorOrigem = $setorOrigem;

        return $this;
    }

    public function getSetorDestino(): ?Setor
    {
        return $this->setorDestino;
    }

    public function setSetorDestino(?Setor $setorDestino): self
    {
        $this->setorDestino = $setorDestino;

        return $this;
    }

    public function getPessoaDestino(): ?Pessoa
    {
        return $this->pessoaDestino;
    }

    public function setPessoaDestino(?Pessoa $pessoaDestino): self
    {
        $this->pessoaDestino = $pessoaDestino;

        return $this;
    }

    public function getDataHoraRecebimento(): ?DateTime
    {
        return $this->dataHoraRecebimento;
    }

    public function setDataHoraRecebimento(?DateTime $dataHoraRecebimento): self
    {
        $this->dataHoraRecebimento = $dataHoraRecebimento;

        return $this;
    }

    public function getUsuarioRecebimento(): ?Usuario
    {
        return $this->usuarioRecebimento;
    }

    public function setUsuarioRecebimento(?Usuario $usuarioRecebimento): self
    {
        $this->usuarioRecebimento = $usuarioRecebimento;

        return $this;
    }

    public function getMecanismoRemessa(): ?string
    {
        return $this->mecanismoRemessa;
    }

    public function setMecanismoRemessa(?string $mecanismoRemessa): self
    {
        $this->mecanismoRemessa = $mecanismoRemessa;

        return $this;
    }
}
