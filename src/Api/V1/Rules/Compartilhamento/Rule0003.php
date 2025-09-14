<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Rules/Compartilhamento/Rule0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Compartilhamento;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoUsuario;
use SuppCore\AdministrativoBackend\Repository\VinculacaoUsuarioRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0003.
 *
 * @descSwagger=Apenas o usuário responsável, seu assessor, coordenador (setor, unidade e órgão central) e criador podem criar ou alterar o compartilhamento da tarefa.
 * @classeSwagger=Rule0003
 * 
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0003 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private TokenStorageInterface $tokenStorage;

    private VinculacaoUsuarioRepository $vinculacaoUsuarioRepository;

    private CoordenadorService $coordenadorService;

    private TransactionManager $transactionManager;

    /**
     * Rule0003 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        TokenStorageInterface $tokenStorage,
        VinculacaoUsuarioRepository $vinculacaoUsuarioRepository,
        CoordenadorService $coordenadorService,
        TransactionManager $transactionManager
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->tokenStorage = $tokenStorage;
        $this->vinculacaoUsuarioRepository = $vinculacaoUsuarioRepository;
        $this->coordenadorService = $coordenadorService;
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            Compartilhamento::class => [
                'beforeCreate',
                'beforeUpdate',
                'beforePatch',
                'skipWhenCommand',
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
        // validacao caso seja adicionar acompanhamento de processo
        if (!$restDto->getTarefa()) {
            return true;
        }

        $usuario = $this->tokenStorage->getToken()->getUser();

        // usuario responsavel
        if (($restDto->getTarefa()->getUsuarioResponsavel()->getId() ===
            $usuario->getId())) {
            return true;
        }

        // criador da tarefa
        if ($restDto->getTarefa()->getCriadoPor() && ($restDto->getTarefa()->getCriadoPor()->getId() ===
            $usuario->getId())) {
            return true;
        }

        // assessor
        /** @var VinculacaoUsuario $vinculacaoUsuario */
        $vinculacaoUsuario = $this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
            $restDto->getTarefa()->getUsuarioResponsavel()->getId(),
            $usuario->getId()
        );
        if ($vinculacaoUsuario && $vinculacaoUsuario->getCompartilhaTarefa()) {
            return true;
        }

        // submeteu à aprovação ou assessor submeteu? Pode.
        /** @var Context $atividadeAprovacao */
        $atividadeAprovacao =
            $this->transactionManager->getContext('atividadeAprovacao', $transactionId);
        if ($atividadeAprovacao &&
            ($atividadeAprovacao->getValue()[0] === $usuario->getId() ||
                $this->vinculacaoUsuarioRepository->findByUsuarioAndUsuarioVinculado(
                    $atividadeAprovacao->getValue()[0],
                    $usuario->getId()
                ))
        ) {
            return true;
        }

        // caso é coordenador da tarefa submetida para aprovacao
        if ($atividadeAprovacao) {
            /** @var \SuppCore\AdministrativoBackend\Entity\Setor $setorAprovacao */
            $setorAprovacao = $atividadeAprovacao->getValue()[1];

            $isCoordenador = false;
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorSetor([$setorAprovacao]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorUnidade([$setorAprovacao->getUnidade()]);
            $isCoordenador |= $this->coordenadorService
                ->verificaUsuarioCoordenadorOrgaoCentral(
                    [$setorAprovacao->getUnidade()->getModalidadeOrgaoCentral()]
                );
            if ($isCoordenador) {
                return true;
            }
        }

        // coordenador
        $isCoordenador = false;
        $isCoordenador |= $this->coordenadorService
            ->verificaUsuarioCoordenadorSetor([$restDto->getTarefa()->getSetorResponsavel()]);
        $isCoordenador |= $this->coordenadorService
            ->verificaUsuarioCoordenadorUnidade([$restDto->getTarefa()->getSetorResponsavel()->getUnidade()]);
        $isCoordenador |= $this->coordenadorService
            ->verificaUsuarioCoordenadorOrgaoCentral(
                [$restDto->getTarefa()->getSetorResponsavel()->getUnidade()->getModalidadeOrgaoCentral()]
            );

        if ($isCoordenador) {
            return true;
        }

        $this->rulesTranslate->throwException('compartilhamento', '0003');

        //return false;
    }

    public function getOrder(): int
    {
        return 2;
    }
}
