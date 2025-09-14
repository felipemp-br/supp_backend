<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ObjetoAvaliado/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ObjetoAvaliado;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ObjetoAvaliado as ObjetoAvaliadoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ObjetoAvaliadoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica se o objeto avaliado já existe
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    /**
     * Rule0001 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private ObjetoAvaliadoRepository $ObjetoAvaliadoRespository
    ) {
    }

    public function supports(): array
    {
        return [
            ObjetoAvaliadoDTO::class => [
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
        $objetoAvaliado = $this
            ->ObjetoAvaliadoRespository
            ->findBy(
                ['objetoId' => $restDto->getObjetoId(), 'classe' => $restDto->getClasse()]
            );

        if (!empty($objetoAvaliado)) {
            $this->rulesTranslate->throwException('_gestao_devedor_objeto_avaliado', 'R0001', [$objetoAvaliado[0]->getId()]);
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
