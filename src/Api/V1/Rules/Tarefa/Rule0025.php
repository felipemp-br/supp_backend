<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Tarefa/Rule0025.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Tarefa;

use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0025.
 *
 * @descSwagger=O usuário não pode excluir tarefa em que nao e o responsavel!
 * @classeSwagger=Rule0025
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0025 implements RuleInterface
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
                'beforeDelete'
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

            // é o usuário que criou a tarefa?  Pode.
            if ($entity->getCriadoPor() &&
                ($entity->getCriadoPor()->getId() === $usuario->getId())) {
                return true;
            }

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
                $this->rulesTranslate->throwException('tarefa', '0025');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
