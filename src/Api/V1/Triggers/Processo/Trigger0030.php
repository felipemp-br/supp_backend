<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0030.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoEtiqueta as VinculacaoEtiquetaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoEtiqueta as VinculacaoEtiquetaEntity;
use SuppCore\AdministrativoBackend\Repository\EtiquetaRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerReadInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0030.
 *
 * @descSwagger  =Atualiza etiquetas dos processos de acordo com sua relevância!
 * @classeSwagger=Trigger0030
 */
class Trigger0030 implements TriggerReadInterface
{
    /**
     * Trigger0027 constructor.
     */
    public function __construct(
        private EtiquetaRepository $etiquetaRepository,
        private ParameterBagInterface $parameterBag,
        private VinculacaoEtiquetaResource $vinculacaoEtiquetaResource,
        private TransactionManager $transactionManager
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoEntity::class => [
                'afterFind',
                'skipWhenCommand',
            ],
        ];
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param int   $limit
     * @param int   $offset
     * @param array $populate
     * @param array $result
     *
     * @return void
     */
    public function execute(
        array &$criteria,
        array &$orderBy,
        int &$limit,
        int &$offset,
        array &$populate,
        array &$result
    ): void {
        $transactionId = $this->transactionManager->getCurrentTransactionId();

        $etiqueta = null;
        
        /** @var ProcessoEntity */
        foreach($result['entities'] as $entity) {
            
            /** @var VinculacaoEtiquetaEntity */
            $vinculacaoEtiquetaExistente = $entity->getVinculacoesEtiquetas()->findFirst(function(int $key, VinculacaoEtiquetaEntity $element) {
                return $element->getEtiqueta()->getNome() === $this->parameterBag->get('constantes.entidades.etiqueta.const_6') && $element->getEtiqueta()->getSistema() === true;
            });

            if ($entity->getRelevancias()->count()) {
                if(!$vinculacaoEtiquetaExistente) {
                    if(!$etiqueta) {
                        $etiqueta = $this->etiquetaRepository->findOneBy(
                            [
                                'nome' => $this->parameterBag->get('constantes.entidades.etiqueta.const_6'),
                                'sistema' => true,
                            ]
                        );
                    }

                    $vinculacaoEtiquetaDTO = new VinculacaoEtiquetaDTO();
                    $vinculacaoEtiquetaDTO->setEtiqueta($etiqueta);
                    $vinculacaoEtiquetaDTO->setProcesso($entity);
                    $vinculacaoEtiquetaDTO->setPrivada(false);
                    $vinculacaoEtiquetaDTO->setSugestao(false);
    
                    $vinculacaoEtiquetaEntity = $this->vinculacaoEtiquetaResource->create($vinculacaoEtiquetaDTO, $transactionId);
                    
                    $entity->addVinculacaoEtiqueta($vinculacaoEtiquetaEntity);
                }
            } else {
                if($vinculacaoEtiquetaExistente) {
                    $this->vinculacaoEtiquetaResource->delete($vinculacaoEtiquetaExistente->getId(), $transactionId);

                    $entity->removeVinculacaoEtiqueta($vinculacaoEtiquetaExistente);
                }
            }

        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
