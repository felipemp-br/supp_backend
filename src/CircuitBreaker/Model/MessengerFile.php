<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\CircuitBreaker\Model;

/**
 * MessengerFile.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
readonly class MessengerFile
{
    /**
     * Constructor.
     *
     * @param string $path
     * @param string $filename
     */
    public function __construct(
        private string $path,
        private string $filename,
    ) {
    }

    /**
     * Return path.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Return filename.
     *
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }
}
