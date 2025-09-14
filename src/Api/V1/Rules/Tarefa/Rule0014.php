<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0014.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\TarefaRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0014.
 *
 * @descSwagger  =O acesso é restrito e o usuário responsável não tem poderes para vê-lo! O acesso deve ser concedido antes da abertura da tarefa!
 * @classeSwagger=Rule0014
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0014 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TarefaRepository $tarefaRepository;

    /**
     * Rule0014 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TarefaRepository $tarefaRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tarefaRepository = $tarefaRepository;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'beforeUpdate',
                'beforePatch',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Tarefa|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        // redistribuição apenas
        if (!$restDto->getUsuarioResponsavel() ||
            ($restDto->getUsuarioResponsavel()->getId() !== $entity->getUsuarioResponsavel()->getId())) {
            $temAcesso = false;
            // TODO Criar pesquisa sem utilizar hql
            $result = $this->tarefaRepository->findAclUsuarioResponsavel($entity->getProcesso()->getId());
            if ($result) {
                foreach ($result as $row) {
                    $row = array_change_key_case($row, CASE_UPPER);
                    if ('ROLE_USER' === $row['IDENTIFIER']) {
                        $temAcesso = true;
                        break;
                    }
                    if (strpos($row['IDENTIFIER'], 'Usuario') > 0) {
                        $username = str_replace(
                            'SuppCore\AdministrativoBackend\Entity\Usuario-',
                            '',
                            $row['IDENTIFIER']
                        );
                        if ($username == $restDto->getUsuarioResponsavel()->getUsername()) {
                            $temAcesso = true;
                            break;
                        }
                    }
                    if (strpos($row['IDENTIFIER'], 'SETOR') > 0) {
                        $setorId = str_replace('ACL_SETOR_', '', $row['IDENTIFIER']);
                        if ($setorId == $restDto->getSetorResponsavel()->getId()) {
                            $temAcesso = true;
                            break;
                        }
                    }
                    if (strpos($row['IDENTIFIER'], 'UNIDADE') > 0) {
                        $unidadeId = str_replace('ACL_UNIDADE_', '', $row['IDENTIFIER']);
                        if ($unidadeId == $restDto->getSetorResponsavel()->getUnidade()->getId()) {
                            $temAcesso = true;
                            break;
                        }
                    }
                }
                if (!$temAcesso) {
                    $this->rulesTranslate->throwException('tarefa', '0014');
                }
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 14;
    }
}
