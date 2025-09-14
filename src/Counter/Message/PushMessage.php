<?php

declare(strict_types=1);
/**
 * /src/Counter/Message/PushMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Counter\Message;

/**
 * Class PushMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class PushMessage
{
    private string $identifier;

    private string $channel;

    private array $criteria;

    private string $resource;

    private bool $desabilitaSoftDeleteable = false;

    private bool $useSelectForCount = false;

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return array
     */
    public function getCriteria(): array
    {
        return $this->criteria;
    }

    /**
     * @param array $criteria
     */
    public function setCriteria(array $criteria): void
    {
        $this->criteria = $criteria;
    }

    /**
     * @return string
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * @param string $resource
     */
    public function setResource(string $resource): void
    {
        $this->resource = $resource;
    }

    /**
     * @return bool
     */
    public function getDesabilitaSoftDeleteable(): bool
    {
        return $this->desabilitaSoftDeleteable;
    }

    /**
     * @param bool $desabilitaSoftDeleteable
     */
    public function setDesabilitaSoftDeleteable(bool $desabilitaSoftDeleteable): void
    {
        $this->desabilitaSoftDeleteable = $desabilitaSoftDeleteable;
    }

    /**
     * @return bool
     */
    public function getUseSelectForCount(): bool
    {
        return $this->useSelectForCount;
    }

    /**
     * @param bool $useSelectForCount
     */
    public function setUseSelectForCount(bool $useSelectForCount): void
    {
        $this->useSelectForCount = $useSelectForCount;
    }
}
