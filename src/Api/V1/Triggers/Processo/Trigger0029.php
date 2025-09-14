<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0029.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerReadOneInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0029.
 *
 * @descSwagger  =Atualiza etiquetas do processo de acordo com sua relevância!
 * @classeSwagger=Trigger0029
 */
class Trigger0029 implements TriggerReadOneInterface
{
    /**
     * Trigger0029 constructor.
     */
    public function __construct(
        private readonly EtiquetaRepository         $etiquetaRepository,
        private readonly ParameterBagInterface      $parameterBag,
        private readonly VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        private readonly TransactionManager         $transactionManager
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoEntity::class => [
                'afterFindOne',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param int $id
     * @param array|null $populate
     * @param array|null $orderBy
     * @param array|null $context
     * @param ProcessoEntity|null $entity
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @noinspection PhpParameterByRefIsNotUsedAsReferenceInspection
     */
    public function execute(
        int              &$id,
        ?array           &$populate,
        ?array           &$orderBy,
        ?array           &$context,
        ?EntityInterface &$entity,
    ): void {
        $transactionId = $this->transactionManager->getCurrentTransactionId();

        /** @var VinculacaoEtiquetaEntity $vinculacaoEtiquetaExistente */
        $vinculacaoEtiquetaExistente = $entity->getVinculacoesEtiquetas()->findFirst(
            function (int $key, VinculacaoEtiquetaEntity $element) {
                return $element->getEtiqueta()->getNome() ===
                    $this->parameterBag->get('constantes.entidades.etiqueta.const_6') &&
                    $element->getEtiqueta()->getSistema() === true;
            }
        );

        if ($entity->getRelevancias()->count()) {
            if (!$vinculacaoEtiquetaExistente) {
                $etiqueta = $this->etiquetaRepository->findOneBy(
                    [
                        'nome' => $this->parameterBag->get('constantes.entidades.etiqueta.const_6'),
                        'sistema' => true,
                    ]
                );

                $vinculacaoEtiquetaDTO = new VinculacaoEtiquetaDTO();
                $vinculacaoEtiquetaDTO->setEtiqueta($etiqueta);
                $vinculacaoEtiquetaDTO->setProcesso($entity);
                $vinculacaoEtiquetaDTO->setPrivada(false);
                $vinculacaoEtiquetaDTO->setSugestao(false);

                $vinculacaoEtiquetaEntity = $this->vinculacaoEtiquetaResource
                    ->create($vinculacaoEtiquetaDTO, $transactionId);

                $entity->addVinculacaoEtiqueta($vinculacaoEtiquetaEntity);
            }
        } else {
            if ($vinculacaoEtiquetaExistente) {
                $this->vinculacaoEtiquetaResource->delete($vinculacaoEtiquetaExistente->getId(), $transactionId);

                $entity->removeVinculacaoEtiqueta($vinculacaoEtiquetaExistente);
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
