<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Bookmark/Rule0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Bookmark;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Bookmark;
use SuppCore\AdministrativoBackend\Api\V1\Resource\BookmarkResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Não é possível criar um bookmark de página igual
 * @classeSwagger=Rule0001
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     *
     * @param RulesTranslate        $rulesTranslate
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private TokenStorageInterface $tokenStorage,
        private BookmarkResource $bookmarkResource
    )
    {
    }

    public function supports(): array
    {
        return [
            Bookmark::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {

        $bookmark = $this->bookmarkResource->getRepository()->findOneBy([
            'usuario'  => $this->tokenStorage->getToken()->getUser(),
            'processo' => $restDto->getProcesso(),
            'componenteDigital' => $restDto->getComponenteDigital(),
            'pagina' => $restDto->getPagina(),
            'textoReferencia' => $restDto->getTextoReferencia(),
            'apagadoEm' => $restDto->getApagadoEm()
        ]);

        if ($bookmark) {
            $this->rulesTranslate->throwException('bookmark', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
