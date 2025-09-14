<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use SuppCore\AdministrativoBackend\Api\V1\DTO\TarefaMensagem as TarefaMensagemDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\TarefaMensagem as TarefaMensagemEntity;
use SuppCore\AdministrativoBackend\Repository\TarefaMensagemRepository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use DateTime;

/**
 * Class TarefaMensagemResource.
 *
 * @method TarefaMensagemRepository getRepository()
 * @method TarefaMensagemEntity[]   find(array $criteria = null, array $orderBy = null, int $limit = null, int $offset = null, array $search = null, array $populate = null): array
 * @method TarefaMensagemEntity|null findOne(int $id, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method TarefaMensagemEntity|null findOneBy(array $criteria, array $orderBy = null, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method TarefaMensagemEntity      create(RestDtoInterface $dto, string $transactionId, bool $skipValidation = null): EntityInterface
 * @method TarefaMensagemEntity      save(EntityInterface $entity, string $transactionId, bool $skipValidation = null): EntityInterface 
 * // Removidos update, delete, patch por enquanto
 */
class TarefaMensagemResource extends RestResource
{
    /**
     * TarefaMensagemResource constructor.
     *
     * @param TarefaMensagemRepository $repository
     * @param ValidatorInterface       $validator
     */
    public function __construct(
        TarefaMensagemRepository $repository,
        ValidatorInterface $validator
        // Injete outros serviços se o resource precisar deles diretamente
        // (ex: para lógica complexa antes/depois de persistir)
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(TarefaMensagemDTO::class); // Define o DTO padrão para esta resource
    }

    /**
     * Sobrescreve o método create para adicionar lógica específica antes de persistir,
     * como garantir que a tarefa e o usuário sejam definidos corretamente.
     *
     * @param TarefaMensagemDTO $dto
     * @param string            $transactionId
     * @param bool|null         $skipValidation
     *
     * @return EntityInterface|TarefaMensagemEntity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(RestDtoInterface $dto, string $transactionId, ?bool $skipValidation = null): EntityInterface
    {
        $skipValidation ??= false;

        // Valida o DTO
        $this->validateDto($dto, $skipValidation);

        /** @var TarefaMensagemEntity $entity */
        $entity = new TarefaMensagemEntity(); // Cria uma nova instância da entidade

        // Antes de chamar o mapeador (se houver um automático), ou antes de persistir,
        // garantimos que as informações cruciais estão na entidade.
        // O DTO já deve ter tarefa e usuário setados pelo Controller.
        
        if ($dto->getTarefa() instanceof TarefaEntity) {
            $entity->setTarefa($dto->getTarefa());
        } else {
            // Isso não deveria acontecer se o controller fez o trabalho direito
            throw new \LogicException('A Tarefa não foi definida no DTO para criação da mensagem.');
        }

        if ($dto->getUsuario() instanceof UsuarioEntity) {
            $entity->setUsuario($dto->getUsuario());
            // A entidade TarefaMensagem deve setar usuarioNome e uuid no construtor/setters
        } else {
            // Isso não deveria acontecer
            throw new \LogicException('O Usuário não foi definido no DTO para criação da mensagem.');
        }

        $entity->setConteudo($dto->getConteudo());

        // dataHoraEnvio e uuid são setados no construtor da TarefaMensagemEntity
        // criadoEm e atualizadoEm são gerenciados pelo Timestampable trait

        // Hooks before/after (se você tiver RulesManager/TriggersManager configurados para TarefaMensagem)
        // $this->beforeCreate($dto, $entity, $transactionId);

        // Persiste a entidade através do método save herdado ou diretamente
        $this->save($entity, $transactionId, $skipValidation); // save() chama persist e flush se necessário

        // $this->afterCreate($dto, $entity, $transactionId);

        return $entity;
    }

    // Se você tiver um sistema de mapeamento automático DTO <-> Entidade
    // que é invocado pelo `persistEntity` do `RestResource` base, você pode
    // precisar ajustar ou confiar nele. O método create acima é mais explícito.
    // Se o seu `RestResource::create` já lida com a transformação de DTO para Entidade
    // e persistência, você pode não precisar sobrescrevê-lo, mas sim garantir que
    // os dados no DTO (tarefa, usuario) sejam corretamente mapeados para a nova entidade.
    // A lógica de `setTarefa` e `setUsuario` pode ser movida para um `beforeCreate` hook
    // se o seu `RestResource` tiver tal mecanismo e você passar o DTO diretamente para o `parent::create`.

    /*
    // Exemplo de como poderia ser com hooks (se seu RestResource suportar)
    public function beforeCreate(RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        parent::beforeCreate($dto, $entity, $transactionId); // Chama o hook pai se existir

        if ($dto instanceof TarefaMensagemDTO && $entity instanceof TarefaMensagemEntity) {
            if (!$entity->getTarefa() && $dto->getTarefa() instanceof TarefaEntity) {
                $entity->setTarefa($dto->getTarefa());
            }
            if (!$entity->getUsuario() && $dto->getUsuario() instanceof UsuarioEntity) {
                $entity->setUsuario($dto->getUsuario());
            }
            // Conteúdo já deve ser mapeado pelo sistema de DTO/Mapper
        }
    }
    */
}