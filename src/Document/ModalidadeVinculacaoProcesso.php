<?php

declare(strict_types=1);
/**
 * /src/Document/ModalidadeVinculacaoProcesso.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class ModalidadeVinculacaoProcesso.
 *
 * @ES\ObjectType()
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class ModalidadeVinculacaoProcesso
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $valor;

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
     * @return ModalidadeVinculacaoProcesso
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getValor(): string
    {
        return $this->valor;
    }

    /**
     * @param string $valor
     *
     * @return ModalidadeVinculacaoProcesso
     */
    public function setValor(string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }
}
