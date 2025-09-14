<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoEtiqueta/Rule0014.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoEtiqueta;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0014.
 *
 * @descSwagger=O usuário não pode transformar uma vinculação de etiqueta de sistema em privada
 * @classeSwagger=Rule0014
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0014 implements RuleInterface
{
    /**
     * Rule0014 constructor.
     */
    public function __construct(
        protected  RulesTranslate $rulesTranslate,
    ) { }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaDto::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param VinculacaoEtiquetaDto|RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(
        VinculacaoEtiquetaDto|RestDtoInterface|null $restDto,
        EntityInterface $entity,
        string $transactionId): bool
    {
        if ($restDto->getEtiqueta() &&
            $restDto->getPrivada() &&
            $restDto->getEtiqueta()->getSistema()) {
            $this->rulesTranslate->throwException('vinculacao_etiqueta', '0014');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
