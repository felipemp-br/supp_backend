<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoEtiqueta/Trigger0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoEtiqueta;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\Repository\VinculacaoEtiquetaRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0007.
 *
 * @descSwagger  =Define se a execução da ação da etiqueta será automática ou dependera da ação do usuário.
 *
 * @classeSwagger=Trigger0007
 *
 * @author       Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0007 implements TriggerInterface
{
    /**
     * Constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        protected TokenStorageInterface $tokenStorage
    ) {
    }

    public function supports(): array
    {
        return [
            VinculacaoEtiquetaDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoEtiquetaDTO|RestDtoInterface|null $vinculacaoEtiquetaDTO
     * @param EntityInterface $vinculacaoEtiquetaEntity
     * @param string $transactionId
     *
     * @return void
     */
    public function execute(
        VinculacaoEtiquetaDTO|RestDtoInterface|null $vinculacaoEtiquetaDTO,
        EntityInterface $vinculacaoEtiquetaEntity,
        string $transactionId
    ): void {
        $usuarioSessao = $this->tokenStorage->getToken()?->getUser()?->getId();
        // Verifica se a opção escolhida para a etiqueta foi de sugestão ou disparo automático
        if (null !== $vinculacaoEtiquetaDTO->getEtiqueta()->getTipoExecucaoAcaoSugestao()) {
            $vinculacaoEtiquetaDTO->setSugestao(
                !$vinculacaoEtiquetaDTO->getEtiqueta()->getSistema()
            );
        } else {
            // Verifica se foi o usuário que criou a etiqueta. Se não for, marca como sugestão
            $vinculacaoEtiquetaDTO->setSugestao(
                !$vinculacaoEtiquetaDTO->getEtiqueta()->getSistema()
                && (!$usuarioSessao || $usuarioSessao !== $vinculacaoEtiquetaDTO->getEtiqueta()?->getCriadoPor()?->getId())
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
