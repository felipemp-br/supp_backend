<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0014.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use Gaufrette\Filesystem;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Filesystem\FilesystemManager;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0014.
 *
 * @descSwagger=Caso informado um hash no criação do componente, é preciso validar a existência no filesystem!
 * @classeSwagger=Rule0014
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0014 implements RuleInterface
{
    /**
     * Rule0014 constructor.
     *
     * @param RulesTranslate    $rulesTranslate
     * @param FilesystemManager $filesystemManager
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private FilesystemManager $filesystemManager
    ) {
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeCreate',
                'beforeReverter',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\ComponenteDigital|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        // upload por referência a um hash
        if ($restDto->getHash() && !$restDto->getConteudo() && !$restDto->getModelo()) {
            $filesystem = $this->filesystemManager
                ->getFilesystemService($entity, $restDto)
                ->get();
            if (!$filesystem->has($restDto->getHash())) {
                $this->rulesTranslate->throwException('componenteDigital', '0014');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
