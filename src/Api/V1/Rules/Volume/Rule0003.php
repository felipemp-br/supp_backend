<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Volume/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Volume;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Volume;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\VolumeRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger=Volumes eletrônicos com componentes digitais juntados não podem ser convertidos em eletrônicos!
 * @classeSwagger=Rule0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private VolumeRepository $volumeRepository;

    /**
     * Rule0003 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        VolumeRepository $volumeRepository,
        private ParameterBagInterface $parameterBag
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->volumeRepository = $volumeRepository;
    }

    public function supports(): array
    {
        return [
            Volume::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Volume|RestDtoInterface|null $restDto
     *
     * @throws RuleException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (($this->parameterBag->get('constantes.entidades.modalidade_meio.const_2') === $restDto->getModalidadeMeio()->getValor()) &&
            ($this->parameterBag->get('constantes.entidades.modalidade_meio.const_1') === $restDto->getModalidadeMeio()->getValor()) &&
            (true === $this->volumeRepository->hasComponenteDigital($entity->getId()))) {
            $this->rulesTranslate->throwException('volume', '0003');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
