<?php

declare(strict_types=1);
/**
 * /src/Entity/Assunto.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use DMS\Filter\Rules as Filter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class FundamentaçãoRestrição.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_fundamentacao_restricao')]
class FundamentacaoRestricao implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'fundamentacoesRestricao')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: false)]
    protected Processo $processo;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeFundamentacao')]
    #[ORM\JoinColumn(name: 'modalidade_fundamentacao_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeFundamentacao $modalidadeFundamentacao = null;

    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $setor = null;

    #[ORM\ManyToOne(targetEntity: 'usuario')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuario = null;

    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'unidade_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $unidade = null;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Gedmo\Versioned]
    #[ORM\Column(type: 'string', nullable: false)]
    protected string $fundamentacao;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
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

    public function getModalidadeFundamentacao(): ?ModalidadeFundamentacao
    {
        return $this->modalidadeFundamentacao;
    }

    public function setModalidadeFundamentacao(?ModalidadeFundamentacao $modalidadeFundamentacao): self
    {
        $this->modalidadeFundamentacao = $modalidadeFundamentacao;

        return $this;
    }

    public function getSetor(): ?Setor
    {
        return $this->setor;
    }

    public function setSetor(?Setor $setor): self
    {
        $this->setor = $setor;

        return $this;
    }

    public function getUnidade(): ?Setor
    {
        return $this->unidade;
    }

    public function setUnidade(?Setor $unidade): self
    {
        $this->unidade = $unidade;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getFundamentacao(): string
    {
        return $this->fundamentacao;
    }

    public function setFundamentacao(string $fundamentacao): self
    {
        $this->fundamentacao = $fundamentacao;

        return $this;
    }
}
