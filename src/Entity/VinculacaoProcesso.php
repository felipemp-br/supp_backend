<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoProcesso.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
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
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoProcesso.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['processoVinculado', 'apagadoEm'], message: 'Processo já se encontra vinculado a outro!')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_vinc_processo')]
#[ORM\UniqueConstraint(columns: ['processo_vinculado_id', 'apagado_em'])]
class VinculacaoProcesso implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'vinculacoesProcessos')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: false)]
    protected Processo $processo;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Processo')]
    #[ORM\JoinColumn(name: 'processo_vinculado_id', referencedColumnName: 'id', nullable: false)]
    protected Processo $processoVinculado;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeVinculacaoProcesso')]
    #[ORM\JoinColumn(name: 'mod_vinc_processo_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeVinculacaoProcesso $modalidadeVinculacaoProcesso;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $observacao = null;

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

    public function getProcessoVinculado(): Processo
    {
        return $this->processoVinculado;
    }

    public function setProcessoVinculado(Processo $processoVinculado): self
    {
        $this->processoVinculado = $processoVinculado;

        return $this;
    }

    public function getModalidadeVinculacaoProcesso(): ModalidadeVinculacaoProcesso
    {
        return $this->modalidadeVinculacaoProcesso;
    }

    public function setModalidadeVinculacaoProcesso(
        ModalidadeVinculacaoProcesso $modalidadeVinculacaoProcesso
    ): self {
        $this->modalidadeVinculacaoProcesso = $modalidadeVinculacaoProcesso;

        return $this;
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
}
