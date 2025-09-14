<?php
declare(strict_types=1);
/**
 * /src/Document/AssuntoAdministrativo.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class AssuntoAdministrativo.
 *
 * @ES\ObjectType()
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class AssuntoAdministrativo
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $nome;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AssuntoAdministrativo
     */
    public function setId(int $id): AssuntoAdministrativo
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return AssuntoAdministrativo
     */
    public function setNome(string $nome): AssuntoAdministrativo
    {
        $this->nome = $nome;

        return $this;
    }
}
