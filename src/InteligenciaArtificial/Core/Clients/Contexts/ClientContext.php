<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Core\Clients\Contexts;

/**
 * ClientContext.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ClientContext
{
    /**
     * Constructor.
     *
     * @param string $contextName
     * @param array  $contextData
     */
    public function __construct(
        private readonly string $contextName,
        private readonly array $contextData = []
    ) {
    }

    /**
     * Retorna o nome do contexto do client.
     *
     * @return string
     */
    public function getContextName(): string
    {
        return $this->contextName;
    }

    /**
     * Retorna os metadados do contexto de execução.
     *
     * @return array
     */
    public function getContextData(): array
    {
        return $this->contextData;
    }
}
