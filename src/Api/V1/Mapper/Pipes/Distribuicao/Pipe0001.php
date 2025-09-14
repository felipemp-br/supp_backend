<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Distribuicao/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Distribuicao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Distribuicao as DistribuicaoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Distribuicao as DistribuicaoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Utils\CoordenadorService;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Pipe0001.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{
    /**
     * Pipe0001 constructor.
     */
    public function __construct(
        private readonly CoordenadorService $coordenadorService,
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    public function supports(): array
    {
        return [
            DistribuicaoDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param DistribuicaoDTO|RestDtoInterface|null $restDto
     * @param DistribuicaoEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface &$restDto, EntityInterface $entity): void
    {
        $exibeAuditoria = false;

        // É o usuario responsável pela tarefa?
        if ($this->tokenStorage->getToken()->getUserIdentifier() ===
            $entity->getTarefa()->getUsuarioResponsavel()->getUserIdentifier()
        ) {
            $exibeAuditoria = true;
        }

        // É o usuário anterior?
        if ($this->tokenStorage->getToken()->getUserIdentifier() ===
            $entity->getUsuarioAnterior()?->getUserIdentifier()) {
            $exibeAuditoria = true;
        }

        // É coordenador?
        $setores = array_filter([
            $entity->getSetorAnterior(), $entity->getSetorPosterior()
        ]);
        $unidades = array_filter([
            $entity->getSetorAnterior()?->getUnidade(),
            $entity->getSetorPosterior()->getUnidade()
        ]);
        $orgaosCentrais = array_filter([
            $entity->getSetorAnterior()?->getUnidade()?->getModalidadeOrgaoCentral(),
            $entity->getSetorPosterior()->getUnidade()->getModalidadeOrgaoCentral()
        ]);

        if ($this->coordenadorService->verificaUsuarioCoordenadorSetor($setores) ||
            $this->coordenadorService->verificaUsuarioCoordenadorUnidade($unidades) ||
            $this->coordenadorService->verificaUsuarioCoordenadorOrgaoCentral($orgaosCentrais)
        ) {
            $exibeAuditoria = true;
        }

        if (false === $exibeAuditoria) {
            $restDto->setAuditoriaDistribuicao(
                'Usuário não tem permissão para visualizar a auditoria de distribuição'
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
