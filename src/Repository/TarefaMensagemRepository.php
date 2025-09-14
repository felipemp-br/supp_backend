<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Repository;

use SuppCore\AdministrativoBackend\Entity\TarefaMensagem;
use SuppCore\AdministrativoBackend\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;
use SuppCore\AdministrativoBackend\QueryBuilder\ArrayQueryBuilder; // <--- ADICIONAR ESTE USE
use SuppCore\AdministrativoBackend\Transaction\TransactionManager; // <--- ADICIONAR ESTE USE

/**
 * @method TarefaMensagem|null find($id, $lockMode = null, $lockVersion = null) // O find do BaseRepository tem outra assinatura para o find(id) simples
 * @method TarefaMensagem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TarefaMensagem[]    findAll()
 * @method TarefaMensagem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TarefaMensagemRepository extends BaseRepository
{
    /**
     * @var string
     * Define o nome da entidade para este repositório.
     * Esta propriedade estática é lida pelo método getEntityName() do BaseRepository.
     *
     * @psalm-suppress NonInvariantDocblockPropertyType
     */
    protected static string $entityName = TarefaMensagem::class;

    /**
     * TarefaMensagemRepository constructor.
     *
     * @param ManagerRegistry    $managerRegistry    Injetado pelo Symfony (service autowiring)
     * @param ArrayQueryBuilder  $arrayQueryBuilder  Injetado pelo Symfony
     * @param TransactionManager $transactionManager Injetado pelo Symfony
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        ArrayQueryBuilder $arrayQueryBuilder,
        TransactionManager $transactionManager
    ) {
        parent::__construct($managerRegistry, $arrayQueryBuilder, $transactionManager);
    }
}