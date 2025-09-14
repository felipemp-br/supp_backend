<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Processo/Rule0000.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Helpers\ProtocoloExterno\ProtocoloExternoManager;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0000.
 *
 * @descSwagger  =Apenas o usuário com poder MASTER pode remover a restrição de acesso do NUP!
 *
 * @classeSwagger=Rule0000
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0000 implements RuleInterface
{
    /**
     * Rule0000 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly ProtocoloExternoManager $protocoloExternoManager
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'assertCreate',
                'assertUpdate',
                'assertPatch',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ProcessoDTO|null $restDto
     * @param EntityInterface|ProcessoEntity    $entity
     * @param string                            $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        RestDtoInterface|ProcessoDTO|null $restDto,
        EntityInterface|ProcessoEntity $entity,
        string $transactionId
    ): bool {
        try {
            return $this->protocoloExternoManager->validate($restDto);
        } catch (Exception $e) {
            $this->rulesTranslate->throwException('processo', '0000', [$e->getMessage()]);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
