<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoModelo/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoModelo;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoModelo as VinculacaoModeloDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeModeloResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModeloResource;
use SuppCore\AdministrativoBackend\Api\V1\Triggers\Modelo\Message\IndexacaoMessage;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Modelo;
use SuppCore\AdministrativoBackend\Entity\VinculacaoModelo as VinculacaoModeloEntity;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0002.
 *
 * @descSwagger  =Atualizar a modalidade do Modelo após a promoção
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    private ModeloResource $modeloResource;
    private ModalidadeModeloResource $modalidadeModeloResource;

    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        ModalidadeModeloResource $modalidadeModeloResource,
        ModeloResource $modeloResource,
        private TransactionManager $transactionManager,
        private ParameterBagInterface $parameterBag,
    ) {
        $this->modalidadeModeloResource = $modalidadeModeloResource;
        $this->modeloResource = $modeloResource;
    }

    public function supports(): array
    {
        return [
            VinculacaoModeloDTO::class => [
                'beforeUpdate'
            ],
        ];
    }

    /**
     * @param VinculacaoModeloDTO|RestDtoInterface|null $vinculacaoModeloDTO
     * @param VinculacaoModeloEntity|EntityInterface    $vinculacaoModeloEntity
     *
     * @throws Exception
     */
    public function execute(
        ?RestDtoInterface $vinculacaoModeloDTO,
        EntityInterface $vinculacaoModeloEntity,
        string $transactionId
    ): void {
        //PROMOÇÃO DE MODELO DE USUÁRIO
        if(!$vinculacaoModeloDTO->getUsuario() && $vinculacaoModeloEntity->getUsuario()){

            $this->transactionManager->addContext(
                new Context(
                    'promoverModelo',
                    true
                ),
                $transactionId
            );
            $modeloDto = $this->modeloResource->getDtoForEntity(
                $vinculacaoModeloEntity->getModelo()->getId(),
                Modelo::class
            );
            $modalidadeModelo = $vinculacaoModeloDTO->getModalidadeOrgaoCentral() ?
                $this->modalidadeModeloResource
                    ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_3')]):
                $this->modalidadeModeloResource
                    ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_2')]);

            $modeloDto->setModalidadeModelo($modalidadeModelo);
            $this->modeloResource->update(
                $vinculacaoModeloEntity->getModelo()->getId(),
                $modeloDto,
                $transactionId
            );

            $this->transactionManager->removeContext(
                'promoverModelo',
                $transactionId
            );
            //PROMOÇÃO DE MODELO PARA NACIONAL
        } else if (!$vinculacaoModeloEntity->getModalidadeOrgaoCentral() &&
                    $vinculacaoModeloDTO->getModalidadeOrgaoCentral()){

            if($vinculacaoModeloDTO->getModelo()->getModalidadeModelo()->getValor() !==
                $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_3')){

                $this->transactionManager->addContext(
                    new Context(
                        'promoverModelo',
                        true
                    ),
                    $transactionId
                );
                $modeloDto = $this->modeloResource->getDtoForEntity(
                    $vinculacaoModeloEntity->getModelo()->getId(),
                    Modelo::class
                );
                $modalidadeModelo = $this->modalidadeModeloResource
                        ->findOneBy(['valor' =>
                            $this->parameterBag->get('constantes.entidades.modalidade_modelo.const_3')]);

                $modeloDto->setModalidadeModelo($modalidadeModelo);
                $this->modeloResource->update(
                    $vinculacaoModeloEntity->getModelo()->getId(),
                    $modeloDto,
                    $transactionId
                );

                $this->transactionManager->removeContext(
                    'promoverModelo',
                    $transactionId
                );
            }
        }

    }

    public function getOrder(): int
    {
        return 1;
    }
}
