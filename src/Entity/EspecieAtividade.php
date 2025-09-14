<?php

declare(strict_types=1);
/**
 * /src/Entity/EspecieAtividade.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Immutable\Immutable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Nome;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EspecieAtividade.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['nome', 'generoAtividade'], message: 'Nome já está em utilização para esse gênero!')]
#[Enableable]
#[Immutable(
    fieldName: 'nome',
    expressionValues: 'env:constantes.entidades.especie_atividade.immutable',
    expression: Immutable::EXPRESSION_IN
)]
#[ORM\Table(name: 'ad_especie_atividade')]
#[ORM\UniqueConstraint(columns: ['nome', 'genero_atividade_id', 'apagado_em'])]
class EspecieAtividade implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Nome;
    use Descricao;
    use Ativo;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'GeneroAtividade')]
    #[ORM\JoinColumn(name: 'genero_atividade_id', referencedColumnName: 'id', nullable: true)]
    protected ?GeneroAtividade $generoAtividade = null;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<TransicaoWorkflow>
     */
    #[ORM\OneToMany(mappedBy: 'especieAtividade', targetEntity: 'TransicaoWorkflow')]
    protected $transicoesWorkflow;

    /**
     * EspecieAtividade constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->transicoesWorkflow = new ArrayCollection();
    }

    public function getGeneroAtividade(): GeneroAtividade
    {
        return $this->generoAtividade;
    }

    public function setGeneroAtividade(GeneroAtividade $generoAtividade): self
    {
        $this->generoAtividade = $generoAtividade;

        return $this;
    }

    /**
     * @return Collection|ArrayCollection|ArrayCollection<TransicaoWorkflow>
     */
    public function getTransicoesWorkflow(): Collection
    {
        return $this->transicoesWorkflow;
    }

    public function addTransicaoWorkflow(TransicaoWorkflow $transicaoWorkflow): self
    {
        if (!$this->transicoesWorkflow->contains($transicaoWorkflow)) {
            $this->transicoesWorkflow->add($transicaoWorkflow);
            $transicaoWorkflow->setEspecieAtividade($this);
        }

        return $this;
    }

    public function removeTransicaoWorkflow(TransicaoWorkflow $transicaoWorkflow): self
    {
        if ($this->transicoesWorkflow->contains($transicaoWorkflow)) {
            $this->transicoesWorkflow->removeElement($transicaoWorkflow);
        }

        return $this;
    }
}
