<?php

declare(strict_types=1);
/**
 * /src/Entity/TipoValidacaoWorkflow.php.
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Doctrine\ORM\Enableable\Enableable;
use SuppCore\AdministrativoBackend\Entity\Traits\Ativo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Descricao;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Sigla;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use SuppCore\AdministrativoBackend\Entity\Traits\Valor;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class TipoValidacaoWorkflow.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[UniqueEntity(fields: ['valor'], message: 'Valor já está em utilização para essa modalidade!')]
#[Enableable]
#[ORM\Table(name: 'ad_tipo_valid_workflow')]
#[ORM\UniqueConstraint(columns: ['valor', 'apagado_em'])]
class TipoValidacaoWorkflow implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;
    use Valor;
    use Descricao;
    use Ativo;
    use Sigla;

    /**
     * @var Collection|ArrayCollection|ArrayCollection<ValidacaoTransicaoWorkflow>
     */
    #[ORM\OneToMany(mappedBy: 'tipoValidacaoWorkflow', targetEntity: 'ValidacaoTransicaoWorkflow')]
    protected $validacoes;

    /**
     * TipoValidacaoWorkflow constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
        $this->validacoes = new ArrayCollection();
    }

    /**
     * @return Collection|ArrayCollection|ArrayCollection<ValidacaoTransicaoWorkflow>
     */
    public function getValidacoes(): Collection
    {
        return $this->validacoes;
    }

    public function addValidacao(ValidacaoTransicaoWorkflow $validacao): self
    {
        if (!$this->validacoes->contains($validacao)) {
            $this->validacoes->add($validacao);
            $validacao->setTipoValidacaoWorkflow($this);
        }

        return $this;
    }

    public function removeValidacao(ValidacaoTransicaoWorkflow $validacao): self
    {
        if ($this->validacoes->contains($validacao)) {
            $this->validacoes->removeElement($validacao);
        }

        return $this;
    }
}
