<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Favorito/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Favorito;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Favorito;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\FavoritoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Favorito já está cadastrado!
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private FavoritoRepository $favoritoRepository;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        FavoritoRepository $favoritoRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->favoritoRepository = $favoritoRepository;
    }

    public function supports(): array
    {
        return [
            Favorito::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Favorito|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Favorito|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $result = $this->favoritoRepository->findBy(
            [
                'usuario' => $restDto->getUsuario(),
                'objectId' => $restDto->getObjectId(),
                'objectClass' => $restDto->getObjectClass(),
                'context' => $restDto->getContext(),
            ]
        );
        if ($result) {
            $this->rulesTranslate->throwException('favorito', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
