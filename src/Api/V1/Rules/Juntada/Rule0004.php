<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Juntada/Rule0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ComponenteDigitalRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0004.
 *
 * @descSwagger=Os NUPs recebidos via barramento/integração não podem possuir documentos com mais de 1 (um) componente digital!
 * @classeSwagger=Rule0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private ComponenteDigitalRepository $componenteDigitalRepository;

    /**
     * Rule0004 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        ComponenteDigitalRepository $componenteDigitalRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->componenteDigitalRepository = $componenteDigitalRepository;
    }

    public function supports(): array
    {
        return [
            Juntada::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param Juntada|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Juntada|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getVolume()->getProcesso()->getOrigemDados() &&
            'BARRAMENTO_PEN' === $restDto->getVolume()->getProcesso()->getOrigemDados()->getFonteDados()) {
            $result = $this->componenteDigitalRepository->findCountByDocumento($restDto->getDocumento()->getId());
            if ($result > 1) {
                $this->rulesTranslate->throwException('juntada', '0004');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 4;
    }
}
