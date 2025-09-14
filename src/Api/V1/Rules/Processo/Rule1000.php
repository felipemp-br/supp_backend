<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Processo/Rule1000.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use DateTime;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Rule1000.
 *
 * @descSwagger  =O prazo para adesão ao Desenrola terminou em 31/12/2024 às 23:59 (horário de Brasília).
 *
 * @classeSwagger=Rule1000
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule1000 implements RuleInterface
{
    /**
     * @param RulesTranslate $rulesTranslate
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        private readonly RulesTranslate                $rulesTranslate,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'assertCreate',
                'assertUpdate',
                'assertPatch',
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
        EntityInterface|ProcessoEntity    $entity,
        string                            $transactionId
    ): bool {
        if ($restDto->getProtocoloEletronico() &&
            (
                $this->authorizationChecker->isGranted('ROLE_USUARIO_EXTERNO')
                || $this->authorizationChecker->isGranted('ROLE_DPU')
            ) &&
            $restDto->getDadosRequerimento()) {
            $dadosFormulario = json_decode($restDto->getDadosRequerimento(), true);

            $tiposRequerimentoTransacao = [
                'requerimento_transacao_adesao_pgf',
                'requerimento_transacao_informacoes_pgf'
            ];

            if (in_array($dadosFormulario['tipoRequerimento'], $tiposRequerimentoTransacao)) {
                $dataLimiteTransacao = new DateTime('2025-01-01 00:00:00');
                $agora = new DateTime();

                if ($agora->format('YmdHis') >= $dataLimiteTransacao->format('YmdHis')) {
                    $this->rulesTranslate->throwException('processo', '1000');
                }
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
