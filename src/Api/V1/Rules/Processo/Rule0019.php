<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Processo/Rule0019.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Repository\TramitacaoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Rule0019.
 *
 * @descSwagger=Processos com remessas cadastradas não podem ser arquivados
 * @classeSwagger=Rule0018
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0019 implements RuleInterface
{
    /**
     * Rule0019 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly ParameterBagInterface $parameterBag,
        private readonly TramitacaoRepository $tramitacaoRepository
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ProcessoDTO|null $restDto
     * @param EntityInterface|ProcessoEntity    $entity
     * @param string                            $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(
        RestDtoInterface|ProcessoDTO|null $restDto,
        EntityInterface|ProcessoEntity $entity,
        string $transactionId
    ): bool {
        if ($entity->getSetorAtual()->getEspecieSetor()?->getNome()
            !== $this->parameterBag->get('constantes.entidades.especie_setor.const_2')
            && ($restDto->getSetorAtual()?->getEspecieSetor()?->getNome()
            === $this->parameterBag->get('constantes.entidades.especie_setor.const_2'))
        ) {
            $remessaAberta = $this->tramitacaoRepository->findPendenteExternaProcesso($entity->getId());

            if ($remessaAberta) {
                $this->rulesTranslate->throwException('processo', '0020');
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 19;
    }
}
