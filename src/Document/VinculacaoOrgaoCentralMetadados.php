<?php

declare(strict_types=1);
/**
 * /src/Document/VinculacaoOrgaoCentralMetadados.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class VinculacaoOrgaoCentralMetadados.
 *
 * @ES\ObjectType()
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class VinculacaoOrgaoCentralMetadados
{
    public function __construct()
    {
        $this->modalidadeOrgaoCentral = new ArrayCollection();
        $this->assuntoAdministrativo = new ArrayCollection();
        $this->especieDocumento = new ArrayCollection();
        $this->especieSetor = new ArrayCollection();
        $this->especieProcesso = new ArrayCollection();
    }

    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ModalidadeOrgaoCentral")
     */
    protected ArrayCollection $modalidadeOrgaoCentral;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\AssuntoAdministrativo")
     */
    protected ArrayCollection $assuntoAdministrativo;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\EspecieSetor")
     */
    protected ArrayCollection $especieSetor;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\EspecieProcesso")
     */
    protected ArrayCollection $especieProcesso;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\EspecieDocumento")
     */
    protected ArrayCollection $especieDocumento;

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
     * @return $this
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getModalidadeOrgaoCentral(): ArrayCollection
    {
        return $this->modalidadeOrgaoCentral;
    }

    /**
     * @param ModalidadeOrgaoCentral $modalidadeOrgaoCentral
     *
     * @return $this
     */
    public function setModalidadeOrgaoCentral(ModalidadeOrgaoCentral $modalidadeOrgaoCentral): self
    {
        $this->modalidadeOrgaoCentral->add($modalidadeOrgaoCentral);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAssuntoAdministrativo(): ArrayCollection
    {
        return $this->assuntoAdministrativo;
    }

    /**
     * @param AssuntoAdministrativo $assuntoAdministrativo
     *
     * @return $this
     */
    public function setAssuntoAdministrativo(AssuntoAdministrativo $assuntoAdministrativo): self
    {
        $this->assuntoAdministrativo->add($assuntoAdministrativo);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEspecieSetor(): ArrayCollection
    {
        return $this->especieSetor;
    }

    /**
     * @param EspecieSetor $especieSetor
     *
     * @return $this
     */
    public function setEspecieSetor(EspecieSetor $especieSetor): self
    {
        $this->especieSetor->add($especieSetor);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEspecieProcesso(): ArrayCollection
    {
        return $this->especieProcesso;
    }

    /**
     * @param EspecieProcesso $especieProcesso
     *
     * @return $this
     */
    public function setEspecieProcesso(EspecieProcesso $especieProcesso): self
    {
        $this->especieProcesso->add($especieProcesso);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getEspecieDocumento(): ArrayCollection
    {
        return $this->especieDocumento;
    }

    /**
     * @param EspecieDocumento $especieDocumento
     *
     * @return $this
     */
    public function setEspecieDocumento(EspecieDocumento $especieDocumento): self
    {
        $this->especieDocumento->add($especieDocumento);

        return $this;
    }
}
