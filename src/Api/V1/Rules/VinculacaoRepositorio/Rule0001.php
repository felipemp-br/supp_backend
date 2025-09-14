<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\VinculacaoRepositorio;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRepositorio;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoRepositorio as VinculacaoRepositorioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Rule0001.
 *
 * @descSwagger=Verifica permissão do usuário para criar VinculacaoRepositorio
 * @classeSwagger=Rule0001
 *
 * @author Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Rule0001 implements RuleInterface
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
            VinculacaoRepositorio::class => [
                'beforeCreate',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param VinculacaoRepositorioDTO|RestDtoInterface|null $restDto
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        /* @var VinculacaoRepositorioDTO $restDto */
        if ($restDto->getUsuario()) { /* Repositorio do tipo "INDIVIDUAL" usuário tem permissão*/
            if ($restDto->getUsuario()->getId() !==
                $this->tokenStorage->getToken()->getUser()->getId()
            ) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0001');
            }
        }
        /*
         *  Repositorio do tipo "SETOR"
         *  Permissão para Coordenador de SETOR, UNIDADE ou ÓRGÃO CENTRAL
         * */
        if ($restDto->getSetor()) {
            if (!$this->coordenadorService->verificaUsuarioCoordenadorSetor([$restDto->getSetor()]) &&
                !$this->coordenadorService->verificaUsuarioCoordenadorUnidade([$restDto->getSetor()->getUnidade()]) &&
                !$this->coordenadorService
                    ->verificaUsuarioCoordenadorOrgaoCentral(
                        [$restDto->getSetor()->getUnidade()->getModalidadeOrgaoCentral()]
                    )
            ) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0002');
            }
        }

        /*
         *  Repositorio do tipo "UNIDADE"
         *  Permissão para Coordenador de UNIDADE ou ÓRGÃO CENTRAL
         * */
        if ($restDto->getUnidade()) {
            if (!$this->coordenadorService->verificaUsuarioCoordenadorUnidade([$restDto->getUnidade()]) &&
                !$this->coordenadorService
                    ->verificaUsuarioCoordenadorOrgaoCentral([$restDto->getUnidade()->getModalidadeOrgaoCentral()])
            ) {
                $this->rulesTranslate->throwException('vinculacaoRepositorio', '0003');
            }
        }

        /*
         *  Repositorio do tipo ORGAO CENTRAL
         *  Permissão para Coordenador de ÓRGÃO CENTRAL
         * */
        if ($restDto->getModalidadeOrgaoCentral()) {
            if (!$this->coordenadorService->verificaUsuarioCoordenadorOrgaoCentral([$restDto->getModalidadeOrgaoCentral()])) {
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
