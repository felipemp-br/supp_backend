<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoRepositorio;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRepositorio as VinculacaoRepositorioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoRepositorio as VinculacaoRepositorioEntity;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0002.
 *
 * @descSwagger=Verifica permissão do usuário para excluir VinculacaoRepositorio
 * @classeSwagger=Rule0002
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0002 implements RuleInterface
{
    private TokenStorageInterface $tokenStorage;

    private RulesTranslate $rulesTranslate;

    private CoordenadorService $coordenadorService;

    /**
     * Rule0001 constructor.
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        RulesTranslate $rulesTranslate,
        CoordenadorService $coordenadorService
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->rulesTranslate = $rulesTranslate;
        $this->coordenadorService = $coordenadorService;
    }

    public function supports(): array
    {
        return [
            VinculacaoRepositorioEntity::class => [
                'beforeDelete',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param VinculacaoRepositorioDTO|RestDtoInterface|null $restDto
     * @param EntityInterface|VinculacaoRepositorioEntity    $entity
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        /* @var VinculacaoRepositorioDTO $restDto */
        if ($entity->getUsuario()) { /* Repositorio do tipo "INDIVIDUAL" usuário tem permissão*/
            if ($entity->getUsuario()->getId() !==
                $this->tokenStorage->getToken()->getUser()->getId()
            ) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0001');
            }
        }
        /*
         *  Repositorio do tipo "SETOR"
         *  Permissão para Coordenador de SETOR, UNIDADE ou ÓRGÃO CENTRAL
         * */
        if ($entity->getSetor()) {
            if (!$this->coordenadorService->verificaUsuarioCoordenadorSetor([$entity->getSetor()]) &&
                !$this->coordenadorService->verificaUsuarioCoordenadorUnidade([$entity->getSetor()->getUnidade()]) &&
                !$this->coordenadorService
                    ->verificaUsuarioCoordenadorOrgaoCentral(
                        [$entity->getSetor()->getUnidade()->getModalidadeOrgaoCentral()]
                    )
            ) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0002');
            }
        }

        /*
         *  Repositorio do tipo "UNIDADE"
         *  Permissão para Coordenador de UNIDADE ou ÓRGÃO CENTRAL
         * */
        if ($entity->getUnidade()) {
            if (!$this->coordenadorService->verificaUsuarioCoordenadorUnidade([$entity->getUnidade()]) &&
                !$this->coordenadorService
                    ->verificaUsuarioCoordenadorOrgaoCentral([$entity->getUnidade()->getModalidadeOrgaoCentral()])
            ) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0003');
            }
        }

        /*
         *  Repositorio do tipo ORGAO CENTRAL
         *  Permissão para Coordenador de ÓRGÃO CENTRAL
         * */
        if ($entity->getModalidadeOrgaoCentral()) {
            if (!$this->coordenadorService->verificaUsuarioCoordenadorOrgaoCentral([$entity->getModalidadeOrgaoCentral()])) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0004');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
