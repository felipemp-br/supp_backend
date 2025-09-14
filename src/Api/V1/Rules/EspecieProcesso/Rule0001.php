<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/EspecieProcesso/Rule0001.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\EspecieProcesso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\EspecieProcesso as EspecieProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\EspecieProcesso as EspecieProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\EspecieProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica unicidade de nome para cada genero
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private EspecieProcessoRepository $especieProcessoRepository;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        EspecieProcessoRepository $especieProcessoRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->especieProcessoRepository = $especieProcessoRepository;
    }

    public function supports(): array
    {
        return [
            EspecieProcessoDTO::class => [
                'beforeCreate',
                'beforeUpdate',
            ],
        ];
    }

    /**
     * @param EspecieProcessoDTO|RestDtoInterface|null $restDto
     * @param EspecieProcessoEntity|EntityInterface    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $especieProcesso = $this->especieProcessoRepository->findOneBy(
            [
                'nome' => $restDto->getNome(),
                'generoProcesso' => $restDto->getGeneroProcesso(),
            ]
        );

        if ($especieProcesso && $especieProcesso->getId() != $restDto->getId()) {
            $this->rulesTranslate->throwException('especieProcesso', '0001');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
