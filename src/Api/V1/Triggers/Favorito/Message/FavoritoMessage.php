<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Message/FavoritoMessage.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Favorito\Message;

/**
 * Class FavoritoMessage.
 */
class FavoritoMessage
{
    private string $blocoFavoritos;

    /**
     * FavoritoMessage constructor.
     *
     * @param $processaBlocoFavoritos
     */
    public function __construct($blocoFavoritos)
    {
        $this->blocoFavoritos = $blocoFavoritos;
    }

    public function getBlocoFavoritos(): string
    {
        return $this->blocoFavoritos;
    }
}
