<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Processo/Rule0013.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;

/**
 * Class Rule0013.
 *
 * @descSwagger=Verifica se o processo possui juntadas!
 * @classeSwagger=Rule0013
 */
class Rule0013 implements RuleInterface
{
    /**
     * Rule0013 constructor.
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private TransactionManager $transactionManager,
        private ProcessoRepository $processoRepository
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'beforeDownload',
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
        $sequencial = $this->transactionManager->getContext('sequencial', $transactionId)->getValue();

        if ('all' === $sequencial) {
            $tamanho = $this->processoRepository->findSizeBytes($entity->getId());
        } else {
            $sequenciasId = $this->processaDigitos($sequencial);
            $tamanho = $this->processoRepository->findSizeBytesBySequenciais($entity->getId(), $sequenciasId);
        }

        // 0mb
        if (!$tamanho) {
            $this->rulesTranslate->throwException('processo', '0013a');
        }

        // 300mb
        if ($tamanho > 314572800) {
            $this->rulesTranslate->throwException('processo', '0013b');
        }

        return true;
    }

    private function processaDigitos(?string $expressao): array
    {
        $digitos = [];
        if (!strlen($expressao)) {
            return $digitos;
        }
        $intervalos = explode(',', $expressao);
        foreach ($intervalos as $intervalo) {
            $inicioFim = explode('-', $intervalo);
            if (count($inicioFim) > 1) {
                for ($j = min($inicioFim); $j <= max($inicioFim); ++$j) {
                    $digitos[] = (int) $j;
                }
            } else {
                $digitos[] = (int) $inicioFim[0];
            }
        }

        return $digitos;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
