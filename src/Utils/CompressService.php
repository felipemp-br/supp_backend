<?php

declare(strict_types=1);
/**
 * /src/Utils/CompressService.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Utils;

use Exception;
use function gzcompress;
use function gzuncompress;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class CompressService.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class CompressService implements CompressServiceInterface
{
    private $level;

    /**
     * CompressService constructor.
     *
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->level = $params->get('supp_core.administrativo_backend.gzcompress_level');
    }

    /**
     * @param string $plaintext
     *
     * @return string|null
     *
     * @throws Exception
     */
    public function compress(string $plaintext): ?string
    {
        $conteudo = gzcompress($plaintext, $this->level);
        return $conteudo ? $conteudo : null;
    }

    /**
     * @param string $compressedtext
     *
     * @return string|null
     */
    public function uncompress(string $compressedtext): ?string
    {
        $conteudo = gzuncompress($compressedtext);
        return $conteudo ? $conteudo : null;
    }
}
