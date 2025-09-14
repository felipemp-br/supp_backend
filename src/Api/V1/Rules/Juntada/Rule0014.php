<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Juntada/Rule0014.php.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0014.
 *
 * @descSwagger=Verifica se o usuário tem permissão de enviar os documentos por e-mail.
 * @classeSwagger=Rule0014
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0014 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * Rule0014 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function supports(): array
    {
        return [
            JuntadaEntity::class => [
                'beforeSendEmail',
            ],
        ];
    }

    /**
     * @param Juntada|RestDtoInterface|null $restDto
     * @param JuntadaEntity|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        //implementar validação
        if (!$this->authorizationChecker->isGranted('VIEW', $entity->getDocumento()->getId())) {
            $this->rulesTranslate->throwException('juntada', '0014');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
