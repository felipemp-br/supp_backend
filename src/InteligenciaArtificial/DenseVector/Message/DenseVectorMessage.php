<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Modelo/Message/DenseVectorMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\DenseVector\Message;

/**
 * Class DenseVectorMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DenseVectorMessage
{
    private int $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
