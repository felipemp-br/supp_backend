<?php

declare(strict_types=1);
/**
 * /src/Document/VinculacaoRepositorio.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class VinculacaoRepositorio.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoRepositorio
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\EspecieSetor")
     */
    protected ArrayCollection $especieSetor;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Setor")
     */
    protected ArrayCollection $setor;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ModalidadeOrgaoCentral")
     */
    protected ArrayCollection $modalidadeOrgaoCentral;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\Usuario")
     */
    protected ArrayCollection $usuario;

    /**
     * VinculacaoRepositorio constructor.
     */
    public function __construct()
    {
        $this->especieSetor = new ArrayCollection();
        $this->setor = new ArrayCollection();
        $this->usuario = new ArrayCollection();
        $this->modalidadeOrgaoCentral = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return VinculacaoRepositorio
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ArrayCollection<EspecieSetor>
     */
    public function getEspecieSetor(): ArrayCollection
    {
        return $this->especieSetor;
    }

    /**
     * @param EspecieSetor $especieSetor
     *
     * @return VinculacaoRepositorio
     */
    public function setEspecieSetor(EspecieSetor $especieSetor): self
    {
        $this->especieSetor->add($especieSetor);

        return $this;
    }

    /**
     * @return ArrayCollection<Setor>
     */
    public function getSetor(): ArrayCollection
    {
        return $this->setor;
    }

    /**
     * @param Setor $setor
     *
     * @return VinculacaoRepositorio
     */
    public function setSetor(Setor $setor): self
    {
        $this->setor->add($setor);

        return $this;
    }

    /**
     * @return ArrayCollection<ModalidadeRepositorio>
     */
    public function getModalidadeOrgaoCentral(): ArrayCollection
    {
        return $this->modalidadeOrgaoCentral;
    }

    /**
     * @param ModalidadeOrgaoCentral $modalidadeOrgaoCentral
     *
     * @return VinculacaoRepositorio
     */
    public function setModalidadeOrgaoCentral(ModalidadeOrgaoCentral $modalidadeOrgaoCentral): self
    {
        $this->modalidadeOrgaoCentral->add($modalidadeOrgaoCentral);

        return $this;
    }

    /**
     * @return ArrayCollection<Usuario>
     */
    public function getUsuario(): ArrayCollection
    {
        return $this->usuario;
    }

    /**
     * @param Usuario $usuario
     *
     * @return VinculacaoRepositorio
     */
    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario->add($usuario);

        return $this;
    }
}
