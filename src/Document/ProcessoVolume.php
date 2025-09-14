<?php
declare(strict_types=1);

/**
 * /src/Document/ProcessoVolume.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class ProcessoVolume.
 *
 * @ES\ObjectType()
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class ProcessoVolume
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
     * @return ProcessoVolume
     */
    public function setId(int $id): ProcessoVolume
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
     * @return ProcessoVolume
     */
    public function setNUP(string $NUP): ProcessoVolume
    {
        $this->NUP = $NUP;

        return $this;
    }
}
