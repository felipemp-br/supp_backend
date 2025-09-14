<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Coordenador;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Coordenador;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\CoordenadorRepository;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0003.
 *
 * @descSwagger=Verifica se o coordeandor já foi criado antes
 * @classeSwagger=Rule0003
 */
class Rule0003 implements RuleInterface
{
    private RulesTranslate $rulesTranslate;

    private CoordenadorRepository $coordenadorRepository;

    /**
     * Rule0003 constructor.
     */
    public function __construct(
        RulesTranslate $rulesTranslate,
        CoordenadorRepository $coordenadorRepository
    ) {
        $this->rulesTranslate = $rulesTranslate;
        $this->coordenadorRepository = $coordenadorRepository;
    }

    public function supports(): array
    {
        return [
            Coordenador::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|Coordenador|null $restDto
     *
     * @throws RuleException
     */
    public function validate(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): bool
    {
        /* Verificando se Coordenador do tipo "SETOR" já existe */
        if ($restDto->getSetor()) {
            $temCoordenadorSetor = $this->coordenadorRepository
                ->findCoordenadorByUsuarioAndSetor($restDto->getUsuario()->getId(), $restDto->getSetor()->getId());
            if ($temCoordenadorSetor) {
                $this->rulesTranslate->throwException('coordenador', '0003');
            }
        }

        /* Verificando se Coordenador do tipo "UNIDADE" já existe */
        if ($restDto->getUnidade()) {
            $temCoordenadorUnidade = $this->coordenadorRepository
                ->findCoordenadorByUsuarioAndUnidade($restDto->getUsuario()->getId(), $restDto->getUnidade()->getId());
            if ($temCoordenadorUnidade) {
                $this->rulesTranslate->throwException('coordenador', '0003');
            }
        }

        /* Verificando se Coordenador do tipo "ORGAO CENTRAL" já existe */
        if ($restDto->getOrgaoCentral()) {
            $temCoordenadorOrgaoCentral = $this->coordenadorRepository
                ->findCoordenadorByUsuarioAndOrgaoCentral(
                    $restDto->getUsuario()->getId(),
                    $restDto->getOrgaoCentral()->getId()
                );
            if ($temCoordenadorOrgaoCentral) {
                $this->rulesTranslate->throwException('coordenador', '0003');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
