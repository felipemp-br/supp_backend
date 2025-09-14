<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoRepositorio.php.
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
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class VinculacaoRepositorio.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_vinc_repositorio')]
class VinculacaoRepositorio implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Repositorio', inversedBy: 'vinculacoesRepositorios')]
    #[ORM\JoinColumn(name: 'repositorio_id', referencedColumnName: 'id', nullable: false)]
    protected Repositorio $repositorio;

    #[ORM\ManyToOne(targetEntity: 'EspecieSetor')]
    #[ORM\JoinColumn(name: 'especie_setor_id', referencedColumnName: 'id', nullable: true)]
    protected ?EspecieSetor $especieSetor = null;

    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'setor_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $setor = null;

    #[ORM\ManyToOne(targetEntity: 'usuario')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuario = null;

    #[ORM\ManyToOne(targetEntity: 'ModalidadeOrgaoCentral')]
    #[ORM\JoinColumn(name: 'orgao_central_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeOrgaoCentral $modalidadeOrgaoCentral = null;

    #[ORM\ManyToOne(targetEntity: 'Setor')]
    #[ORM\JoinColumn(name: 'unidade_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $unidade = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getRepositorio(): Repositorio
    {
        return $this->repositorio;
    }

    public function setRepositorio(Repositorio $repositorio): self
    {
        $this->repositorio = $repositorio;

        return $this;
    }

    public function getEspecieSetor(): ?EspecieSetor
    {
        return $this->especieSetor;
    }

    public function setEspecieSetor(?EspecieSetor $especieSetor): self
    {
        $this->especieSetor = $especieSetor;

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

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getModalidadeOrgaoCentral(): ?ModalidadeOrgaoCentral
    {
        return $this->modalidadeOrgaoCentral;
    }

    public function setModalidadeOrgaoCentral(?ModalidadeOrgaoCentral $modalidadeOrgaoCentral): self
    {
        $this->modalidadeOrgaoCentral = $modalidadeOrgaoCentral;

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

    #[Assert\Callback]
    public function isValid(ExecutionContextInterface $context): void
    {
        $campos = [
            $this->getUsuario(),
            $this->getSetor(),
            $this->getModalidadeOrgaoCentral(),
            $this->getUnidade(),
            $this->getEspecieSetor(),
        ];

        // Limpa os campos vazios
        $camposPreenchidos = array_filter($campos);

        if (count($camposPreenchidos) > 1) {
            $context
                ->buildViolation('A vinculacaoRepositorio deve ser realizada com apenas um ou nenhum vínculo')
                ->atPath('id')
                ->addViolation();
        }
    }
}
