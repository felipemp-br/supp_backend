<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use DateTime;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Atividade;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\Resource\AtividadeResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\EspecieAtividadeResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Gera a atividade de aposição de ciência!
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    private AtividadeResource $atividadeResource;

    private EspecieAtividadeResource $especieAtividadeResource;

    private TokenStorageInterface $tokenStorage;

    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        AtividadeResource $atividadeResource,
        EspecieAtividadeResource $especieAtividadeResource,
        TokenStorageInterface $tokenStorage,
        private ParameterBagInterface $parameterBag
    ) {
        $this->atividadeResource = $atividadeResource;
        $this->especieAtividadeResource = $especieAtividadeResource;
        $this->tokenStorage = $tokenStorage;
    }

    public function supports(): array
    {
        return [
            Tarefa::class => [
                'afterCiencia',
            ],
        ];
    }

    /**
     * @param Tarefa|RestDtoInterface|null $restDto
     * @param TarefaEntity|EntityInterface $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $ciencia = $this->especieAtividadeResource->findOneBy(
            [
                'nome' => $this->parameterBag->get('constantes.entidades.especie_atividade.const_2')
            ]
        );

        $atividade = new Atividade();
        $atividade->setTarefa($entity);
        $atividade->setDataHoraConclusao(new DateTime());
        $atividade->setUsuario($this->tokenStorage->getToken()->getUser());
        $atividade->setSetor($entity->getSetorResponsavel());
        $atividade->setEncerraTarefa(true);
        $atividade->setEspecieAtividade($ciencia);
        $this->atividadeResource->create($atividade, $transactionId);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
