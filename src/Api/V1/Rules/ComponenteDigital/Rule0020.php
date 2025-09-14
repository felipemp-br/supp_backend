<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/ComponenteDigital/Rule0020.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\ComponenteDigital;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoUsuario;
use SuppCore\AdministrativoBackend\Repository\CompartilhamentoRepository;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0020.
 *
 * @descSwagger=Apenas o usuário responsável pela tarefa, quem recebeu um compartilhamento ou seu asssessor pode criar minutas na tarefa!
 * @classeSwagger=Rule0020
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0020 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;
    private TransactionManager $transactionManager;
    private TokenStorageInterface $tokenStorage;
    private VinculacaoUsuarioRepository $vinculacaoUsuarioRepository;
    private CoordenadorService $coordenadorService;
    private CompartilhamentoRepository $compartilhamentoRepository;

    /**
     * Rule0020 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        VinculacaoUsuarioRepository $vinculacaoUsuarioRepository,
        CoordenadorService $coordenadorService,
        TransactionManager $transactionManager,
        CompartilhamentoRepository $compartilhamentoRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->vinculacaoUsuarioRepository = $vinculacaoUsuarioRepository;
        $this->coordenadorService = $coordenadorService;
        $this->transactionManager = $transactionManager;
        $this->compartilhamentoRepository = $compartilhamentoRepository;
    }

    public function supports(): array
    {
        return [
            ComponenteDigital::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param ComponenteDigital|RestDtoInterface|null                                  $restDto
     * @param \SuppCore\AdministrativoBackend\Entity\ComponenteDigital|EntityInterface $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        if ($restDto->getTarefaOrigem() &&
            ($restDto->getTarefaOrigem()->getUsuarioResponsavel()->getId() !==
            $this->tokenStorage->getToken()->getUser()->getId())) {
            /** @var VinculacaoUsuario $vinculacaoUsuario */
            $vinculacaoUsuario = $this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
                $restDto->getTarefaOrigem()->getUsuarioResponsavel()->getId(),
                $this->tokenStorage->getToken()->getUser()->getId()
            );
            $assessorCriaMinuta = false;
            if ($vinculacaoUsuario) {
                $assessorCriaMinuta = $vinculacaoUsuario->getCriaMinuta();
                if (!$assessorCriaMinuta &&
                    $vinculacaoUsuario->getCriaOficio() &&
                    $this->transactionManager->getContext('minuta_documento_avulso', $transactionId)) {
                    $assessorCriaMinuta = true;
                }
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

            $temCompartilhamento = false;
            // tem compartilhamento da tarefa? Pode.
            if ($this->tokenStorage->getToken() &&
                $this->tokenStorage->getToken()->getUser() &&
                $this->compartilhamentoRepository->findByTarefaAndUsuario(
                    $restDto->getTarefaOrigem()->getId(),
                    $this->tokenStorage->getToken()->getUser()->getId()
                )
            ) {
                $temCompartilhamento = true;
            }

            if (($restDto->getTarefaOrigem()->getUsuarioResponsavel()->getId() !==
                    $this->tokenStorage->getToken()->getUser()->getId()) &&
                !$assessorCriaMinuta && !$isCoordenador && !$temCompartilhamento
            ) {
                $this->rulesTranslate->throwException('componenteDigital', '0020');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
