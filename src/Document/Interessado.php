<?php
declare(strict_types=1);

/**
 * /src/Document/Interessado.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use Doctrine\Common\Collections\ArrayCollection;
use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class Interessado.
 *
 * @ES\ObjectType()
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Interessado
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\PessoaInteressado")
     */
    protected ArrayCollection $pessoa;

    /**
     * @ES\Embedded(class="SuppCore\AdministrativoBackend\Document\ModalidadeInteressado")
     */
    protected ArrayCollection $modalidadeInteressado;

    /**
     * Interessado constructor.
     */
    public function __construct()
    {
        $this->pessoa = new ArrayCollection();
        $this->modalidadeInteressado = new ArrayCollection();
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
     * @return Interessado
     */
    public function setId(int $id): Interessado
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPessoa(): ArrayCollection
    {
        return $this->pessoa;
    }

    /**
     * @param PessoaInteressado $pessoa
     * @return Interessado
     */
    public function setPessoa(PessoaInteressado $pessoa): Interessado
    {
        $this->pessoa->add($pessoa);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getModalidadeInteressado(): ArrayCollection
    {
        return $this->modalidadeInteressado;
    }

    /**
     * @param ModalidadeInteressado $modalidadeInteressado
     * @return Interessado
     */
    public function setModalidadeInteressado(ModalidadeInteressado $modalidadeInteressado): Interessado
    {
        $this->modalidadeInteressado->add($modalidadeInteressado);

        return $this;
    }
}
