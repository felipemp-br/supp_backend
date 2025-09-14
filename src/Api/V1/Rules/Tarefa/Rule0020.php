<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0020.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0020.
 *
 * @descSwagger=O usuário não pode dar ciência em tarefa compartilhada ou tarefa em que nao e o responsavel!
 * @classeSwagger=Rule0020
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0020 implements RuleInterface
{
    /**
     * @param RulesTranslate $rulesTranslate
     * @param TokenStorageInterface $tokenStorage
     * @param VinculacaoUsuarioRepository $vinculacaoUsuarioRepository
     * @param CoordenadorService $coordenadorService
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private TokenStorageInterface $tokenStorage,
        private VinculacaoUsuarioRepository $vinculacaoUsuarioRepository,
        private CoordenadorService $coordenadorService
    ) {
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'assertCiencia'
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
        if ($this->tokenStorage->getToken() &&
            $this->tokenStorage->getToken()->getUser()) {
            $usuario = $this->tokenStorage->getToken()->getUser();

            // é um assessor do usuário responsável? Pode.
            if ($this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
                $entity->getUsuarioResponsavel()->getId(),
                $usuario->getId()
            )) {
                return true;
            }

            // é um coordenador responsável? Pode.
            $isCoordenador = false;
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorSetor([$entity->getSetorResponsavel()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorUnidade([$entity->getSetorResponsavel()->getUnidade()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral(
                    [$entity->getSetorResponsavel()->getUnidade()->getModalidadeOrgaoCentral()]
                );
            if ($isCoordenador) {
                return true;
            }

            // é o usuário responsável? Pode.
            if ($entity->getUsuarioResponsavel()->getId() !== $usuario->getId()) {
                $this->rulesTranslate->throwException('tarefa', '0020');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
