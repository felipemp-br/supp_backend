<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Compartilhamento/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Compartilhamento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\CompartilhamentoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0001.
 *
 * @descSwagger=Se o compartilhamento do processo e/ou tarefa já existe para um determinado usuário, não será possível fazer o mesmo compartilhamento. 
 * @classeSwagger=Rule0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private CompartilhamentoRepository $compartilhamentoRepository;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        CompartilhamentoRepository $compartilhamentoRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->compartilhamentoRepository = $compartilhamentoRepository;
    }

    public function supports(): array
    {
        return [
            Compartilhamento::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $result = false;
        if ($restDto->getTarefa() && $restDto->getTarefa()->getId()) {
            $result = $this->compartilhamentoRepository->findByTarefaAndUsuario(
                $restDto->getTarefa()->getId(),
                $restDto->getUsuario()->getId()
            );
        }

        if ($restDto->getProcesso() && $restDto->getProcesso()->getId()) {
            $result = $this->compartilhamentoRepository->findByProcessoAndUsuario(
                $restDto->getProcesso()->getId(),
                $restDto->getUsuario()->getId()
            );
        }
        if ($result) {
            $restDto->getTarefa() ?
                $this->rulesTranslate->throwException('compartilhamento', '0001'):
                $this->rulesTranslate->throwException('compartilhamento', '0001b');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
