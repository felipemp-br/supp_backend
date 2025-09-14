<?php

declare(strict_types=1);
/**
 * /src/Entity/RelacionamentoPessoal.php.
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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RelacionamentoPessoal.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_relal_pessoal')]
class RelacionamentoPessoal implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Pessoa', inversedBy: 'relacionamentosPessoais')]
    #[ORM\JoinColumn(name: 'pessoa_id', referencedColumnName: 'id', nullable: false)]
    protected Pessoa $pessoa;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Pessoa')]
    #[ORM\JoinColumn(name: 'pessoa_relacionada_id', referencedColumnName: 'id', nullable: false)]
    protected Pessoa $pessoaRelacionada;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeRelacionamentoPessoal')]
    #[ORM\JoinColumn(name: 'mod_relal_pessoal_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeRelacionamentoPessoal $modalidadeRelacionamentoPessoal;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getPessoa(): Pessoa
    {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa): self
    {
        $this->pessoa = $pessoa;

        return $this;
    }

    public function getPessoaRelacionada(): Pessoa
    {
        return $this->pessoaRelacionada;
    }

    public function setPessoaRelacionada(Pessoa $pessoaRelacionada): self
    {
        $this->pessoaRelacionada = $pessoaRelacionada;

        return $this;
    }

    public function getModalidadeRelacionamentoPessoal(): ModalidadeRelacionamentoPessoal
    {
        return $this->modalidadeRelacionamentoPessoal;
    }

    public function setModalidadeRelacionamentoPessoal(
        ModalidadeRelacionamentoPessoal $modalidadeRelacionamentoPessoal
    ): self {
        $this->modalidadeRelacionamentoPessoal = $modalidadeRelacionamentoPessoal;

        return $this;
    }

    public function getOrigemDados(): ?OrigemDados
    {
        return $this->origemDados;
    }

    public function setOrigemDados(?OrigemDados $origemDados): self
    {
        $this->origemDados = $origemDados;

        return $this;
    }
}
