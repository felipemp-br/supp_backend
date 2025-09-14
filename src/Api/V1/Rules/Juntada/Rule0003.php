<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Juntada/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger=O NUP está arquivado e não pode receber juntadas!
 * @classeSwagger=Rule0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003 implements RuleInterface
{
    /**
     * Rule0003 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            JuntadaDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|JuntadaDTO|null $restDto
     * @param EntityInterface                  $entity
     * @param string                           $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        RestDtoInterface|JuntadaDTO|null $restDto,
        EntityInterface $entity,
        string $transactionId
    ): bool {
        if ($restDto->getVolume()?->getProcesso()
            && $restDto->getVolume()?->getProcesso()->getId()
            && (
                ($this->parameterBag->get('constantes.entidades.modalidade_fase.const_2') === $restDto->getVolume()->getProcesso()->getModalidadeFase()->getValor())
                || ($this->parameterBag->get('constantes.entidades.modalidade_fase.const_3') === $restDto->getVolume()->getProcesso()->getModalidadeFase()->getValor())
                || ($this->parameterBag->get('constantes.entidades.modalidade_fase.const_4') === $restDto->getVolume()->getProcesso()->getModalidadeFase()->getValor())
                || ($this->parameterBag->get('constantes.entidades.especie_setor.const_2') === $restDto->getVolume()->getProcesso()->getSetorAtual()->getEspecieSetor()?->getNome())
            )
        ) {
            $this->rulesTranslate->throwException('juntada', '0003');
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 3;
    }
}
