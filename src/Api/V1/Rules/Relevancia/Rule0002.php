<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Relevancia/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Relevancia;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Relevancia;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\RelevanciaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=NUP já contém uma relevância com esta espécie!
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private RelevanciaRepository $relevanciaRepository;

    /**
     * Rule0002 constructor.
     */
    public function __construct(RulesTranslate $rulesTranslate,
                                RelevanciaRepository $relevanciaRepository)
    {
        $this->rulesTranslate = $rulesTranslate;
        $this->relevanciaRepository = $relevanciaRepository;
    }

    public function supports(): array
    {
        return [
            Relevancia::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Relevancia|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Relevancia|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (null == $restDto->getId()) {
            if ($this->relevanciaRepository->findByProcessoAndEspecie(
                $restDto->getProcesso()->getId(),
                $restDto->getEspecieRelevancia()->getId())) {
                $this->rulesTranslate->throwException('relevancia', '0002');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
