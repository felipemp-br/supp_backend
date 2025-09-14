<?php

declare(strict_types=1);
/**
 * /src/Document/OrigemDados.php.
 *
 * @author  PGE-RS <supp@pge.rs.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * Class OrigemDados.
 *
 * @ES\ObjectType()
 *
 * @author  PGE-RS <supp@pge.rs.gov.br>
 */
class OrigemDados
{
    /**
     * @ES\Id()
     */
    protected int $id;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected string $fonteDados;

    /**
     * @ES\Property(type="text", analyzer="string_analyzer")
     */
    protected ?string $idExterno;


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
     * @return OrigemDados
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getFonteDados(): string
    {
        return $this->fonteDados;
    }

    /**
     * @param string $fonteDados
     *
     * @return OrigemDados
     */
    public function setFonteDados(string $fonteDados): self
    {
        $this->fonteDados = $fonteDados;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getIdExterno(): ?string
    {
        return $this->idExterno;
    }

    /**
     * @param null|string $idExterno
     *
     * @return OrigemDados
     */
    public function setIdExterno(?string $idExterno): self
    {
        $this->idExterno = $idExterno;

        return $this;
    }
}
