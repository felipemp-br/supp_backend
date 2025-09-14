<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Compartilhamento/Rule0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Compartilhamento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Compartilhamento as CompartilhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoUsuario;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0004.
 *
 * @descSwagger=Apenas o usuário responsável pela tarefa pode remover compartilhamento da tarefa!
 * @classeSwagger=Rule0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0004 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TokenStorageInterface $tokenStorage;

    private VinculacaoUsuarioRepository $vinculacaoUsuarioRepository;

    private CoordenadorService $coordenadorService;

    /**
     * Rule0004 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        VinculacaoUsuarioRepository $vinculacaoUsuarioRepository,
        CoordenadorService $coordenadorService
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->vinculacaoUsuarioRepository = $vinculacaoUsuarioRepository;
        $this->coordenadorService = $coordenadorService;
    }

    public function supports(): array
    {
        return [
            CompartilhamentoEntity::class => [
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param Compartilhamento|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\Compartilhamento|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if (!$this->tokenStorage->getToken() || !$this->tokenStorage->getToken()->getUser() || !$entity->getTarefa()) {
            return true;
        }

        // usuario responsavel
        if (($entity->getTarefa()->getUsuarioResponsavel()->getId() ===
            $this->tokenStorage->getToken()->getUser()->getId())) {
            return true;
        }

        // assessor
        /** @var VinculacaoUsuario $vinculacaoUsuario */
        $vinculacaoUsuario = $this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
            $entity->getTarefa()->getUsuarioResponsavel()->getId(),
            $this->tokenStorage->getToken()->getUser()->getId()
        );
        if ($vinculacaoUsuario && $vinculacaoUsuario->getCompartilhaTarefa()) {
            return true;
        }

        // coordenador
        $isCoordenador = false;
        $isCoordenador |= $this->coordenadorService
            ->verificaUsuarioCoordenadorSetor([$entity->getTarefa()->getSetorResponsavel()]);
        $isCoordenador |= $this->coordenadorService
            ->verificaUsuarioCoordenadorUnidade([$entity->getTarefa()->getSetorResponsavel()->getUnidade()]);
        $isCoordenador |= $this->coordenadorService
            ->verificaUsuarioCoordenadorOrgaoCentral(
                [$entity->getTarefa()->getSetorResponsavel()->getUnidade()->getModalidadeOrgaoCentral()]
            );

        if ($isCoordenador) {
            return true;
        }

        //Caso em que o usuário quer apagar o seu compartilhamento com a Tarefa de outro responsável
        if ($this->tokenStorage->getToken()->getUser()->getId() ===
            $entity->getUsuario()->getId()){
            return true;
            }

        $this->rulesTranslate->throwException('compartilhamento', '0004');

        //return false;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
