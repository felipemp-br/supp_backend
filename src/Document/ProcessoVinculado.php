<?php
declare(strict_types=1);

/**
 * /src/Document/ProcessoVinculado.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class ProcessoVinculado.
 *
 * @ES\ObjectType()
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class ProcessoVinculado
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $NUP;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ProcessoVinculado
     */
    public function setId(int $id): ProcessoVinculado
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getNUP(): string
    {
        return $this->NUP;
    }

    /**
     * @param string $NUP
     * @return ProcessoVinculado
     */
    public function setNUP(string $NUP): ProcessoVinculado
    {
        $this->NUP = $NUP;

        return $this;
    }
}
