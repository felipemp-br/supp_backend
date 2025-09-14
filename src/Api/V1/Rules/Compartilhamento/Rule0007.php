<?php

/** @noinspection PhpUndefinedClassInspection */
declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Compartilhamento/Rule0007.php.
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
 * Class Rule0007.
 *
 * @descSwagger=Usuário já acompanhando o processo!
 * @classeSwagger=Rule0007
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0007 implements RuleInterface
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

        if ($restDto->getProcesso() && $restDto->getProcesso()->getId()) {
            $result = $this->compartilhamentoRepository->findByProcessoAndUsuario(
                $restDto->getProcesso()->getId(),
                $restDto->getUsuario()->getId()
            );
        }
        if ($result) {
            $this->rulesTranslate->throwException('compartilhamento', '0007');
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
