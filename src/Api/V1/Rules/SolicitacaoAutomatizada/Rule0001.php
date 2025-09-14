<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/SolicitacaoAutomatizada/Rule0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\SolicitacaoAutomatizada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\SolicitacaoAutomatizadaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger  =Verifica se já existe pedido para o beneficiário informado.
 * @classeSwagger=Rule0001
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0001 implements RuleInterface
{

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        private readonly RulesTranslate $rulesTranslate,
        private readonly SolicitacaoAutomatizadaRepository $solicitacaoAutomatizadaRepository
    ) {
    }

    public function supports(): array
    {
        return [
            SolicitacaoAutomatizada::class => [
                'beforeCreate'
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     * @return bool
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        $solicitacaoAutomatizada = $this->solicitacaoAutomatizadaRepository->findBy(
            ['beneficiario' => $restDto->getBeneficiario()]
        );
        if (count($solicitacaoAutomatizada) > 0) {
            $dadosFormulario = $solicitacaoAutomatizada[0]?->getDadosFormulario();
            $dadosBeneficiario = json_decode($dadosFormulario?->getDataValue() ?? '', true);
            $dadosFormularioSolicita = $restDto->getDadosFormulario();
            $dadosBeneficiarioSolicita = json_decode($dadosFormularioSolicita?->getDataValue() ?? '', true);

            if (is_array($dadosBeneficiario) && is_array($dadosBeneficiarioSolicita) &&
                $dadosBeneficiario['cpfCrianca'] === $dadosBeneficiarioSolicita['cpfCrianca'] &&
                $dadosBeneficiario['numeroBeneficioNegado'] === $dadosBeneficiarioSolicita['numeroBeneficioNegado']
            ) {
                $this->rulesTranslate->throwException('solicitacaoAutomatizada', '0001');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
