<?php

declare(strict_types=1);
/**
 * /src/Fields/Field/RepositorioField.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Fields\Field;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\TransactionRequiredException;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Entity\Processo;
use SuppCore\AdministrativoBackend\Fields\FieldInterface;
use SuppCore\AdministrativoBackend\Repository\RepositorioRepository;

/**
 * Class RepositorioField.
 *
 * Renderiza o Repositorio formatado do processo
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RepositorioField implements FieldInterface
{
    private RepositorioRepository $repositorioRepository;

    private ComponenteDigitalResource $componenteDigitalResource;

    /**
     * RepositorioField constructor.
     *
     * @param RepositorioRepository     $repositorioRepository
     * @param ComponenteDigitalResource $componenteDigitalResource
     */
    public function __construct(
        RepositorioRepository $repositorioRepository,
        ComponenteDigitalResource $componenteDigitalResource
    ) {
        $this->repositorioRepository = $repositorioRepository;
        $this->componenteDigitalResource = $componenteDigitalResource;
    }

    /**
     * @param string $transactionId
     * @param array  $context
     * @param array  $options
     *
     * @return string
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function render(string $transactionId, $context = [], $options = []): ?string
    {
        if (!isset($options[0])) {
            return '';
        }

        $repositorio = $this->repositorioRepository->find((int) $options[0]);

        if (!$repositorio) {
            return '';
        }

        $componenteDigital = $repositorio->getDocumento()->getComponentesDigitais()[0];

        return $this->componenteDigitalResource->download($componenteDigital->getId(), $transactionId)->getConteudo();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'repositorio';
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'info' => [
                'id' => 'supp_core.administrativo_backend.fields_nup_field',
                'nome' => 'REPOSITÓRIO',
                'descricao' => 'REPOSITÓRIO DE CONHECIMENTO',
                'html' => '<span data-method="repositorio" data-options="" '.
                    'data-service="SuppCore\AdministrativoBackend\Fields\Field\RepositorioField">*repositorio*</span>',
            ],
            'opcoes' => [],
            'dependencias' => [
                Processo::class,
            ],
        ];
    }
}
