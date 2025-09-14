<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/DocumentoAvulso/Rule0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\DocumentoAvulso;

use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoUsuario;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger=Apenas o usuário responsável pela tarefa ou seu asssessor pode criar oficios na tarefa!
 * @classeSwagger=Rule0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0002 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TokenStorageInterface $tokenStorage;

    private VinculacaoUsuarioRepository $vinculacaoUsuarioRepository;

    private CoordenadorService $coordenadorService;

    /**
     * Rule0002 constructor.
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
            DocumentoAvulso::class => [
                'beforeCreate',
                'beforeDelete',
            ],
        ];
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\DocumentoAvulso|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getTarefaOrigem()->getUsuarioResponsavel()->getId() !==
            $this->tokenStorage->getToken()->getUser()->getId()) {
            /** @var VinculacaoUsuario $vinculacaoUsuario */
            $vinculacaoUsuario = $this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
                $restDto->getTarefaOrigem()->getUsuarioResponsavel()->getId(),
                $this->tokenStorage->getToken()->getUser()->getId()
            );
            $assessorCriaOficio = false;
            if ($vinculacaoUsuario) {
                $assessorCriaOficio = $vinculacaoUsuario->getCriaOficio();
            }

            $isCoordenador = false;
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorSetor([$restDto->getTarefaOrigem()->getSetorResponsavel()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorUnidade([$restDto->getTarefaOrigem()->getSetorResponsavel()->getUnidade()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral(
                    [$restDto->getTarefaOrigem()->getSetorResponsavel()->getUnidade()->getModalidadeOrgaoCentral()]
                );

            if ((!$assessorCriaOficio) && (0 === $isCoordenador)) {
                $this->rulesTranslate->throwException('documentoAvulso', '0002');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
