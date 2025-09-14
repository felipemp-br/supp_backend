<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/VinculacaoParametroAdministrativo/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoParametroAdministrativo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoParametroAdministrativo;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VinculacaoParametroAdministrativoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger  =Esse Processo já possui o mesmo parametro administrativo!
 * @classeSwagger=Rule0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VinculacaoParametroAdministrativoRepository $vinculacaoParametroAdministrativoRepository;

    /**
     * Rule0002 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        VinculacaoParametroAdministrativoRepository $vinculacaoParametroAdministrativoRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->vinculacaoParametroAdministrativoRepository = $vinculacaoParametroAdministrativoRepository;
    }

    public function supports(): array
    {
        return [
            VinculacaoParametroAdministrativo::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $result = $this->vinculacaoParametroAdministrativoRepository->findByProcessoAndParametroAdministrativo(
            $restDto->getProcesso()->getId(),
            $restDto->getParametroAdministrativo()->getId()
        );
        if ($result) {
            $this->rulesTranslate->throwException('vinculacaoParametroAdministrativo', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
