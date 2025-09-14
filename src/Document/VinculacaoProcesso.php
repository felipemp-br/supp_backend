<?php
declare(strict_types=1);

/**
 * /src/Document/VinculacaoProcesso.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class VinculacaoProcesso.
 *
 * @ES\ObjectType()
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class VinculacaoProcesso
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ProcessoVinculado")
     */
    protected ArrayCollection $processoVinculado;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ModalidadeVinculacaoProcesso")
     */
    protected ArrayCollection $modalidadeVinculacaoProcesso;

    /**
     * VinculacaoProcesso constructor.
     */
    public function __construct()
    {
        $this->processoVinculado = new ArrayCollection();
        $this->modalidadeVinculacaoProcesso = new ArrayCollection();
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
     * @return VinculacaoProcesso
     */
    public function setId(int $id): VinculacaoProcesso
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getProcessoVinculado(): ArrayCollection
    {
        return $this->processoVinculado;
    }

    /**
     * @param ProcessoVinculado $processoVinculado
     * @return VinculacaoProcesso
     */
    public function setProcessoVinculado(ProcessoVinculado $processoVinculado): VinculacaoProcesso
    {
        $this->processoVinculado->add($processoVinculado);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getModalidadeVinculacaoProcesso(): ArrayCollection
    {
        return $this->modalidadeVinculacaoProcesso;
    }

    /**
     * @param ModalidadeVinculacaoProcesso $modalidadeVinculacaoProcesso
     * @return VinculacaoProcesso
     */
    public function setModalidadeVinculacaoProcesso(ModalidadeVinculacaoProcesso $modalidadeVinculacaoProcesso): VinculacaoProcesso
    {
        $this->modalidadeVinculacaoProcesso->add($modalidadeVinculacaoProcesso);

        return $this;
    }
}
