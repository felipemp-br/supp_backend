<?php

declare(strict_types=1);

/**
 * /src/Controller/VinculacaoPessoaBarramentoController.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoPessoaBarramento;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoPessoaBarramento as VinculacaoPessoaBarramentoDTO;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Atualiza a Pessoa do administrativo como validada.
 * @classeSwagger=Trigger0001
 *
 *
 */
class Trigger0001 implements TriggerInterface
{
    private PessoaResource $pessoaResource;

    /**
     * Trigger0001 constructor.
     *
     * @param PessoaResource $pessoaResource
     */
    public function __construct(
        PessoaResource $pessoaResource
    ) {
        $this->pessoaResource = $pessoaResource;
    }

    /**
     * @return array
     */
    public function supports(): array
    {
        return [
            VinculacaoPessoaBarramentoDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     *
     * @throws AnnotationException
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ReflectionException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getPessoa() && $restDto->getPessoa()->getId()) {
            /** @var PessoaDTO $pessoaDto */
            $pessoaDto = $this->pessoaResource
                ->getDtoForEntity($restDto->getPessoa()->getId(), PessoaDTO::class);

            $pessoaDto->setPessoaValidada(true);

            $this->pessoaResource->update($restDto->getPessoa()->getId(), $pessoaDto, $transactionId);
        }
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 1;
    }
}
