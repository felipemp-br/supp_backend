<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Processo/Rule0022.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0022.
 *
 * @descSwagger=Verifica se o NUP já existe na base de dados
 *
 * @classeSwagger=Rule0022
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0022 implements RuleInterface
{
    /**
     * Rule0022 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private ParameterBagInterface $parameterBag,
        private ProcessoResource $processoResource
    ) {
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     * @param string                $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getTipoProtocolo() === $this->parameterBag->get('constantes.entidades.tipo_processo.const_2')) {
            $processoExiste = $this->processoResource->findOneBy([
                'NUP' => $restDto->getNUP(),
            ]);
            if ($processoExiste) {
                $this->rulesTranslate->throwException('processo', '0023');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
