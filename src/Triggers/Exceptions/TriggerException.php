<?php

declare(strict_types=1);
/**
 * /src/Triggers/Exceptions/TriggerException.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Triggers\Exceptions;

use Exception;
use Throwable;

/**
 * Class TriggerException.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class TriggerException extends Exception
{
    protected string $domain;

    protected string $errorCode;

    /**
     * TriggerException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param string $domain
     */
    public function setDomain(string $domain): void
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @param string $errorCode
     */
    public function setErrorCode(string $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
