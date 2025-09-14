<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/DocumentoAvulsoResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use DateInterval;
use DateTime;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use phpDocumentor\Reflection\Types\Boolean;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\DocumentoAvulso as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\DocumentoAvulsoRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DocumentoAvulsoResource.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 *
 * @codingStandardsIgnoreStart
 *
 * @method Repository  getRepository(): Repository
 * @method Entity[]    find(array $criteria = null, array $orderBy = null, int $limit = null, int $offset = null, array $search = null, array $populate = null): array
 * @method Entity|null findOne(int $id, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity      create(RestDtoInterface $dto, string $transactionId, bool $skipValidation = null): EntityInterface
 * @method Entity      update(int $id, RestDtoInterface $dto, string $transactionId, bool $skipValidation = null): EntityInterface
 * @method Entity      delete(int $id, string $transactionId): EntityInterface
 * @method Entity      save(EntityInterface $entity, string $transactionId, bool $skipValidation = null): EntityInterface
 *
 * @codingStandardsIgnoreEnd
 */
class DocumentoAvulsoResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * DocumentoAvulsoResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        private EspecieTarefaResource $especieTarefaResource,
        private ParameterBagInterface $parameterBag,
        private SetorResource $setorResource,
        private UsuarioResource $usuarioResource,
        private TarefaResource $tarefaResource,
        private EtiquetaResource $etiquetaResource,
        private DocumentoResource $documentoResource,
        protected TransactionManager $transactionManager
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(DocumentoAvulso::class);
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface $dto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function remeter(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeRemeter($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterRemeter($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeRemeter(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertRemeter');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeRemeter');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeRemeter');
    }

    public function afterRemeter(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterRemeter');
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface $dto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function responder(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeResponder($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterResponder($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface $dto
     */
    public function beforeResponder(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertResponder');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeResponder');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeResponder');
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface $dto
     */
    public function afterResponder(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterResponder');
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface $dto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function complementar(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeComplementar($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterComplementar($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface $dto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function toggleEncerramento(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        if ($entity->getDataHoraEncerramento()) {
            $dto->setDataHoraEncerramento(null);
        } else {
            $dto->setDataHoraEncerramento(new DateTime());
        }

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeToggleEncerramento($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterToggleEncerramento($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface $dto
     */
    public function beforeToggleEncerramento(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertToggleEncerramento');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeToggleEncerramento');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeToggleEncerramento');
    }

    public function afterToggleEncerramento(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterToggleEncerramento');
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function toggleLida(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        if ($entity->getDataHoraLeitura()) {
            $dto->setDataHoraLeitura(null);
        } else {
            $dto->setDataHoraLeitura(new DateTime());
        }

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeToggleLida($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterToggleLida($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeToggleLida(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeToggleLida');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeToggleLida');
    }

    public function afterToggleLida(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'afterToggleLida');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterToggleLida');
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface $dto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function distribuir(
        int $id,
        int $setorId,
        int|bool $usuarioId,
        RestDtoInterface $dto,
        string $transactionId,
        bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        $this->transactionManager->addContext(
            new Context(
                'distribuirOficio',
                true
            ),
            $transactionId
        );

        /** @var DocumentoAvulso $documentoAvulsoDto */
        $documentoAvulsoDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        $tarefaOrigem = $documentoAvulsoDto->getTarefaOrigem();

        // CRIANDO NOVA TAREFA
        $inicioPrazo = new DateTime();
        $finalPrazo = new DateTime();
        $finalPrazo->add(new DateInterval('P5D'));
        $especieTarefa = $this->especieTarefaResource->findOneBy(
            [
                'nome' => $this->parameterBag->get('constantes.entidades.especie_tarefa.const_2'),
            ]
        );

        $setorResponsavel = $this->setorResource->findOne($setorId);

        $tarefaDTO = new Tarefa();
        $tarefaDTO->setProcesso($documentoAvulsoDto->getProcesso());
        $tarefaDTO->setEspecieTarefa($especieTarefa);
        $tarefaDTO->setSetorResponsavel($setorResponsavel);
        if ($usuarioId) {
            $usuarioResponsavel = $this->usuarioResource->findOne($usuarioId);
            $tarefaDTO->setUsuarioResponsavel($usuarioResponsavel);
        }
        $tarefaDTO->setDataHoraInicioPrazo($inicioPrazo);
        $tarefaDTO->setDataHoraFinalPrazo($finalPrazo);

        $tarefaEntity = $this->tarefaResource->create($tarefaDTO, $transactionId);

        // UPDATE NO DOCUMENTO AVULSO
        $documentoAvulsoDto->setTarefaOrigem($tarefaEntity);
        $documentoAvulsoDto->setUsuarioResponsavel($tarefaEntity->getUsuarioResponsavel());
        $documentoAvulsoDto->setSetorResponsavel($setorResponsavel);

        $documentoAvulsoEntity = $this->update($id, $documentoAvulsoDto, $transactionId);

        $documento = $documentoAvulsoEntity->getDocumentoRemessa();

        $documentoDto = $this->documentoResource->getDtoForEntity(
            $documento->getId(),
            Documento::class
        );
        $documentoDto->setTarefaOrigem($tarefaEntity);
        $this->documentoResource->update(
            $documentoDto->getId(),
            $documentoDto,
            $transactionId
        );

        // ATUALIZANDO ETIQUETAS DA TAREFA
        /* @var VinculacaoEtiqueta $vinculacaoEtiquetaOrigemDto */

        // OFICIO EM ELABORAÇÃO. NESSE CASO EXISTEM DUAS ETIQUETAS. UMA DE TAREFA E OUTRA DA MINUTA
        if (!$documentoAvulsoDto->getDataHoraRemessa()) {
            $vinculacaoEtiqueta = $this->vinculacaoEtiquetaResource->findOneBy(
                [
                    'tarefa' => $tarefaOrigem,
                    'etiqueta' => $this->etiquetaResource->findOneBy(['nome' =>
                        $this->parameterBag->get('constantes.entidades.etiqueta.const_3')]),
                ]
            );
            if ($vinculacaoEtiqueta) {
                $vinculacaoEtiquetaDTO = $this->vinculacaoEtiquetaResource->getDtoForEntity(
                    $vinculacaoEtiqueta->getId(),
                    VinculacaoEtiqueta::class
                );
                $vinculacaoEtiquetaDTO->setTarefa($tarefaEntity);
                $this->vinculacaoEtiquetaResource->update(
                    $vinculacaoEtiqueta->getId(),
                    $vinculacaoEtiquetaDTO,
                    $transactionId
                );
            }
            $vinculacaoEtiqueta = $this->vinculacaoEtiquetaResource->findOneBy(
                [
                    'objectUuid' => $documentoDto->getUuid(),
                ]
            );
            if ($vinculacaoEtiqueta) {
                $vinculacaoEtiquetaDTO = $this->vinculacaoEtiquetaResource->getDtoForEntity(
                    $vinculacaoEtiqueta->getId(),
                    VinculacaoEtiqueta::class
                );
                $vinculacaoEtiquetaDTO->setTarefa($tarefaEntity);
                $this->vinculacaoEtiquetaResource->update(
                    $vinculacaoEtiqueta->getId(),
                    $vinculacaoEtiquetaDTO,
                    $transactionId
                );
            }
        // OFICIO REMETIDO
        } elseif (!$documentoAvulsoDto->getDataHoraResposta()) {
            $vinculacaoEtiqueta = $this->vinculacaoEtiquetaResource->findOneBy(
                [
                    'tarefa' => $tarefaOrigem,
                    'etiqueta' => $this->etiquetaResource->findOneBy(['nome' =>
                        $this->parameterBag->get('constantes.entidades.etiqueta.const_4')]),
                ]
            );
            if ($vinculacaoEtiqueta) {
                $vinculacaoEtiquetaDTO = $this->vinculacaoEtiquetaResource->getDtoForEntity(
                    $vinculacaoEtiqueta->getId(),
                    VinculacaoEtiqueta::class
                );
                $vinculacaoEtiquetaDTO->setTarefa($tarefaEntity);
                $this->vinculacaoEtiquetaResource->update(
                    $vinculacaoEtiqueta->getId(),
                    $vinculacaoEtiquetaDTO,
                    $transactionId
                );
            }
        // OFICIO RESPONDIDO
        } else {
            $vinculacaoEtiqueta = $this->vinculacaoEtiquetaResource->findOneBy(
                [
                    'tarefa' => $tarefaOrigem,
                    'etiqueta' => $this->etiquetaResource->findOneBy(['nome' =>
                        $this->parameterBag->get('constantes.entidades.etiqueta.const_8')]),
                ]
            );
            if ($vinculacaoEtiqueta) {
                $vinculacaoEtiquetaDTO = $this->vinculacaoEtiquetaResource->getDtoForEntity(
                    $vinculacaoEtiqueta->getId(),
                    VinculacaoEtiqueta::class
                );
                $vinculacaoEtiquetaDTO->setTarefa($tarefaEntity);
                $this->vinculacaoEtiquetaResource->update(
                    $vinculacaoEtiqueta->getId(),
                    $vinculacaoEtiquetaDTO,
                    $transactionId
                );
            }
        }

        $this->transactionManager->removeContext('distribuirOficio', $transactionId);

        return $documentoAvulsoEntity;
    }

    /**
     * @param DocumentoAvulso|RestDtoInterface $dto
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function reiterar(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeReiterar($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterReiterar($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeReiterar(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'assertReiterar');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeReiterar');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeReiterar');
    }

    public function afterReiterar(int &$id, RestDtoInterface $dto, EntityInterface $entity, string $transactionId): void
    {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterReiterar');
    }

        /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ReflectionException
     */
    public function observacao(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        bool $skipValidation = null
    ): EntityInterface {
        $skipValidation ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        /**
         * Determine used dto class and create new instance of that and load entity to that. And after that patch
         * that dto with given partial OR whole dto class.
         */
        $restDto = $this->getDtoForEntity($id, get_class($dto), $dto);

        // Validate DTO
        $this->validateDto($restDto, $skipValidation);

        // Before callback method call
        $this->beforeObservacao($id, $restDto, $entity, $transactionId);

        // Create or update entity
        $this->persistEntity($entity, $restDto, $transactionId);

        // After callback method call
        $this->afterObservacao($id, $restDto, $entity, $transactionId);

        return $entity;
    }

    public function beforeObservacao(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeObservacao');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeObservacao');
    }

    public function afterObservacao(
        int &$id,
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'afterObservacao');
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterObservacao');
    }
}
