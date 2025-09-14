<?php

declare(strict_types=1);
/**
 * /src/Elastic/Message/DenseVectorQueryMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Elastic\Message;

/**
 * Class DenseVectorQueryMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DenseVectorQueryMessage
{
    private string $id;
    private string $query;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @param string $query
     */
    public function setQuery(string $query): void
    {
        $this->query = $query;
    }
}
