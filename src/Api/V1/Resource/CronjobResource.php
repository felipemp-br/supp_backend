<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/CronjobResource.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Cronjob as DTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Cronjob as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\CronjobRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Utils\TraceService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

/**
 * Class CronjobResource.
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
class CronjobResource extends RestResource
{
    /**
     * Constructor.
     *
     * @param Repository            $repository
     * @param ValidatorInterface    $validator
     * @param ParameterBagInterface $parameterBag
     * @param TraceService          $traceService
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        private readonly ParameterBagInterface $parameterBag,
        private readonly TraceService $traceService,
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(DTO::class);
    }

    /**
     * @param int          $id
     * @param DTO          $dto
     * @param string       $transactionId
     * @param Process|null $process
     *
     * @return EntityInterface|null
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function startJob(
        int $id,
        RestDtoInterface $dto,
        string $transactionId,
        Process &$process = null
    ): ?EntityInterface {
        $entity = $this->getEntity($id);
        $this->validateDto($dto, true);
        $this->beforeStartJob($dto, $entity, $transactionId);
        $process = Process::fromShellCommandline(
            'php bin/console supp:cronjob:runner --job=$cronJobId'
        );
        $process->setEnv(
            [
                'cronJobId' => $entity->getId(),
                $this->traceService->getEnvTraceKey() => $this->traceService->getTraceId(),
            ]
        );
        $process->setWorkingDirectory($this->parameterBag->get('kernel.project_dir'));

        $process->setOptions(['create_new_console' => true]);
        $process->start();
        $process->wait();
        $entity = $this->update($dto->getId(), $dto, $transactionId);
        $this->afterStartJob($dto, $entity, $transactionId);

        return $entity;
    }

    protected function beforeStartJob(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeStartJob');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeStartJob');
    }

    protected function afterStartJob(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterStartJob');
    }

    /**
     * @param int                  $id
     * @param RestDtoInterface|DTO $dto
     * @param string               $transactionId
     * @param Process|null         $process
     *
     * @return EntityInterface|null
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function executeJobCommand(
        int $id,
        RestDtoInterface|DTO $dto,
        string $transactionId,
        Process &$process = null
    ): ?EntityInterface {
        try {
            /** @var Entity $entity */
            $entity = $this->getEntity($id);
            $this->validateDto($dto, true);
            $this->beforeExecuteJobCommand($dto, $entity, $transactionId);
            $process = Process::fromShellCommandline($dto->getComando());
            $process->setEnv(
                array_merge(
                    [
                        'cronjob_id' => $dto->getId(),
                    ],
                    $process->getEnv()
                )
            );
            $process->setWorkingDirectory($this->parameterBag->get('kernel.project_dir'));
            $process->setOptions(['create_new_console' => true]);
            $process->setTimeout(!is_null($dto->getTimeout()) ? ($dto->getTimeout() ?: null) : 60);
            $process->start();
            $process->wait();
            $dto->setUltimoPid($process->getPid());
            if ($process->isSuccessful()) {
                $dto->setStatusUltimaExecucao(Entity::ST_EXECUCAO_SUCESSO);
                if ($entity->getSincrono()) {
                    $dto->setPercentualExecucao(100);
                }
            } else {
                $dto->setStatusUltimaExecucao(Entity::ST_EXECUCAO_ERRO);
                $dto->setPercentualExecucao(100);
            }

            $entity = $this->update($id, $dto, $transactionId);
            $this->afterExecuteJobCommand($dto, $entity, $transactionId);

            return $entity;
        } catch (Throwable $exception) {
            $dto->setStatusUltimaExecucao(Entity::ST_EXECUCAO_ERRO);
            $entity = $this->update($id, $dto, $transactionId);
            $this->afterExecuteJobCommand($dto, $entity, $transactionId);
            throw new Exception($exception->getMessage());
        }
    }

    protected function beforeExecuteJobCommand(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'beforeExecuteJobCommand');
        $this->rulesManager->proccess($dto, $entity, $transactionId, 'beforeExecuteJobCommand');
    }

    protected function afterExecuteJobCommand(
        RestDtoInterface $dto,
        EntityInterface $entity,
        string $transactionId
    ): void {
        $this->triggersManager->proccess($dto, $entity, $transactionId, 'afterExecuteJobCommand');
    }
}
