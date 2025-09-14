<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Usuario/Rule0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Usuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0007.
 *
 * @descSwagger=Usuário externos, estagiários, e terceirizados só podem ter nível de acesso igual a 0!
 * @classeSwagger=Rule0007
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0007 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    /**
     * Rule0003 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        private ParameterBagInterface $parameterBag
    ) {
        $this->rulesTranslate = $rulesTranslate;
    }

    public function supports(): array
    {
        return [
            Usuario::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Usuario|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Usuario|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getNivelAcesso() &&
            ($restDto->getNivelAcesso() !== $entity->getNivelAcesso()) &&
            ($restDto->getNivelAcesso() > 0) &&
            (!$restDto->getColaborador() ||
                $this->parameterBag->get('constantes.entidades.modalidade_colaborador.const_1') === $restDto->getColaborador()->getModalidadeColaborador()->getValor() ||
                $this->parameterBag->get('constantes.entidades.modalidade_colaborador.const_2') === $restDto->getColaborador()->getModalidadeColaborador()->getValor()
            )
        ) {
            $this->rulesTranslate->throwException('usuario', '0007');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
