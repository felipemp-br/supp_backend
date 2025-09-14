<?php
declare(strict_types=1);
/**
 * /src/Diagnostics/Message/Test.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Diagnostics\Message;

/**
 * Class Test.
 */
class Test
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
