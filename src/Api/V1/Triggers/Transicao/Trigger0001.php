<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Transicao/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Transicao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Historico as HistoricoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Transicao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\HistoricoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ModalidadeFaseResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Altera a modalidade fase do processo de acordo com a transição realizada!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private ProcessoResource $processoResource;

    private ModalidadeFaseResource $modalidadeFaseResource;

    private ComponenteDigitalResource $componenteDigitalResource;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        ProcessoResource $processoResource,
        ModalidadeFaseResource $modalidadeFaseResource,
        ComponenteDigitalResource $componenteDigitalResource,
        private ParameterBagInterface $parameterBag,
        private TransactionManager $transactionManager,
        private HistoricoResource $historicoResource,
    ) {
        $this->processoResource = $processoResource;
        $this->modalidadeFaseResource = $modalidadeFaseResource;
        $this->componenteDigitalResource = $componenteDigitalResource;
    }

    public function supports(): array
    {
        return [
            Transicao::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Transicao|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $processoDTO = new Processo();

        switch ($restDto->getModalidadeTransicao()->getValor()) {
            case 'TRANSFERÊNCIA': // transferência para o arquivo intermediário
                $processoDTO->setModalidadeFase($this->modalidadeFaseResource
                    ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_fase.const_2')]));
                $this->processoResource->update($restDto->getProcesso()->getId(), $processoDTO, $transactionId, true);
                break;

            case 'RECOLHIMENTO': // recolhimento para guarda definitiva
                $processoDTO->setModalidadeFase($this->modalidadeFaseResource
                    ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_fase.const_3')]));
                $this->processoResource->update($restDto->getProcesso()->getId(), $processoDTO, $transactionId, true);
                break;

            case 'DESARQUIVAMENTO': // desarquivamento
                $processoDTO->setModalidadeFase($this->modalidadeFaseResource
                    ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_fase.const_1')]));
                $this->processoResource->update($restDto->getProcesso()->getId(), $processoDTO, $transactionId, true);
                break;

            case 'EXTRAVIO': // recolhimento para guarda definitiva
                break;

            case 'ELIMINAÇÃO': // eliminacao
                $processoDTO->setModalidadeFase($this->modalidadeFaseResource
                    ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_fase.const_4')]));
                $this->processoResource->update($restDto->getProcesso()->getId(), $processoDTO, $transactionId, true);


                $this->transactionManager->addContext(
                    new Context(
                        'eliminacao',
                        true
                    ),
                    $transactionId
                );
                // A eliminação tem como único efeito apagar os componentes digitais,
                // para fins de economia no filesystem
                foreach ($restDto->getProcesso()->getVolumes() as $volume) {
                    /** @var Juntada $juntada */
                    foreach ($volume->getJuntadas() as $juntada) {
                        foreach ($juntada->getDocumento()->getComponentesDigitais() as $componenteDigital) {
                            // comentado por hora, como uma garantia na fase de implantação do sistema
                            $this->componenteDigitalResource->delete($componenteDigital->getId(), $transactionId);
                        }
                    }
                }
                $this->transactionManager->removeContext('eliminacao', $transactionId);

                break;
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
