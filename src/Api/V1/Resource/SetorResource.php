<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/SetorResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Modelo\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Setor\Message\TransferirAcervoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Setor as Entity;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Repository\SetorRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class SetorResource.
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
class SetorResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */
    private TransactionManager $transactionManager;

    /**
     * SetorResource constructor.
     *
     * @param Repository         $repository
     * @param ValidatorInterface $validator
     * @param ProcessoRepository $processoRepository
     * @param ProcessoResource   $processoResource
     */
    public function __construct(
        private Repository $repository,
        private ValidatorInterface $validator,
        private ParameterBagInterface $parameterBag,
        protected ProcessoRepository $processoRepository,
        protected ProcessoResource $processoResource,
        TransactionManager $transactionManager,
        private TokenStorageInterface $tokenStorage,
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(SetorDTO::class);
        $this->transactionManager = $transactionManager;
    }

    /**
     * Método que transfere os processos de um setor para outro.
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \ReflectionException
     */
    public function transferirProcessosSetor(
        int $id,
        int $idDestino,
        string $transactionId
    ): void {
        $this->transactionManager->addAsyncDispatch(new TransferirAcervoMessage(
            $id,
            $idDestino,
            $this->tokenStorage->getToken()->getUser()->getId()
        ), $transactionId);

        $this->transactionManager->commit($transactionId);
    }

    /**
     * Método que transfere os processos de uma Unidade para outra.
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \ReflectionException
     */
    public function transferirProcessosUnidade(
        int $id,
        int $idDestino,
        string $transactionId
    ): void {
        $protocoloDestino = $this->getRepository()->findProtocoloInUnidade($idDestino);
        $arquivoDestino = $this->getRepository()->findArquivoInUnidade($idDestino);

        $setores = $this->getRepository()->findBy(['unidade' => $this->findOne($id)]);

        $setorArquivo = $this->parameterBag->get('constantes.entidades.especie_setor.const_2');

        foreach ($setores as $setor) {
            if ($this->processoResource->count(['setorAtual.id' => 'eq:'.$setor->getId()]) > 0) {
                $this->transactionManager->addAsyncDispatch(new TransferirAcervoMessage(
                    $setor->getId(),
                    $setorArquivo !== $setor->getEspecieSetor()?->getNome() ?
                        $protocoloDestino->getId() : $arquivoDestino->getId(),
                    $this->tokenStorage->getToken()->getUser()->getId()
                ), $transactionId);
            }
        }

        $this->transactionManager->commit($transactionId);
    }

    /**
     * Método que coloca na fila modelos para reindexação.
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \ReflectionException
     */
    public function reindexarModelos(
        int $id,
        string $transactionId
    ): Entity {
        $entity = $this->findOne($id);
        $this->transactionManager->addAsyncDispatch(new IndexacaoMessage(
            $entity->getUuid(),
            explode('Entity\\', $entity::class)[1]
        ), $transactionId);

        return $entity;
    }
}
