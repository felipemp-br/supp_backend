<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoProcesso/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoProcesso;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso as VinculacaoProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\JuntadaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Juntada as JuntadaEntity;
use SuppCore\AdministrativoBackend\Entity\VinculacaoProcesso as VinculacaoProcessoEntity;
use SuppCore\AdministrativoBackend\Entity\Volume as VolumeEntity;
use SuppCore\AdministrativoBackend\Repository\ModalidadeFaseRepository;
use SuppCore\AdministrativoBackend\Repository\VolumeRepository;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=No caso de anexação, migra os documentos para o processo principal e encerra o acessório!
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private ProcessoResource $processoResource;

    private JuntadaResource $juntadaResource;

    private ModalidadeFaseRepository $modalidadeFaseRepository;

    private VolumeRepository $volumeRepository;

    private TransactionManager $transactionManager;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        ProcessoResource $processoResource,
        ModalidadeFaseRepository $modalidadeFaseRepository,
        VolumeRepository $volumeRepository,
        JuntadaResource $juntadaResource,
        TransactionManager $transactionManager,
        private ParameterBagInterface $parameterBag
    ) {
        $this->processoResource = $processoResource;
        $this->modalidadeFaseRepository = $modalidadeFaseRepository;
        $this->volumeRepository = $volumeRepository;
        $this->juntadaResource = $juntadaResource;
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            VinculacaoProcessoDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoProcessoDTO|RestDtoInterface|null $vinculacaoProcessoDTO
     * @param VinculacaoProcessoEntity|EntityInterface    $vinculacaoProcessoEntity
     *
     * @throws Exception
     */
    public function execute(
        ?RestDtoInterface $vinculacaoProcessoDTO,
        EntityInterface $vinculacaoProcessoEntity,
        string $transactionId
    ): void {
        if ('ANEXAÇÃO' === $vinculacaoProcessoDTO->getModalidadeVinculacaoProcesso()->getValor() &&
            $vinculacaoProcessoDTO->getProcessoVinculado()->getId()) {
            $processoDTO = new Processo();
            $processoDTO->setModalidadeFase($this->modalidadeFaseRepository
                ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_fase.const_3')])
            );

            $this->processoResource->update(
                $vinculacaoProcessoDTO->getProcessoVinculado()->getId(),
                $processoDTO,
                $transactionId,
                true
            );

            $volumeDestino = $this->volumeRepository->findVolumeAbertoByProcessoId(
                $vinculacaoProcessoDTO->getProcesso()->getId()
            );

            $this->transactionManager->addContext(
                new Context('anexacaoProcesso', true),
                $transactionId
            );

            /** @var VolumeEntity $volume */
            foreach ($vinculacaoProcessoDTO->getProcessoVinculado()->getVolumes() as $volume) {
                /** @var JuntadaEntity $juntada */
                foreach ($volume->getJuntadas() as $juntada) {
                    if ($juntada->getAtivo() && !$juntada->getVinculada()) {
                        // nova juntada
                        $juntadaDTO = new Juntada();
                        $juntadaDTO->setDocumento($juntada->getDocumento());
                        $juntadaDTO->setDescricao(
                            'JUNTADA POR ANEXAÇÃO DO NUP n. '.
                            $vinculacaoProcessoDTO->getProcessoVinculado()->getNUPFormatado()
                        );
                        $juntadaDTO->setVolume($volumeDestino);
                        $juntadaDTO->setJuntadaDesentranhada($juntada);

                        $this->juntadaResource->create($juntadaDTO, $transactionId);

                        $juntadaDTO = new Juntada();
                        $juntadaDTO->setAtivo(false);
                        $juntadaDTO->setDescricao(
                            'PROCESSO/DOCUMENTO AVULSO ANEXADO AO NUP n. '.
                            $vinculacaoProcessoDTO->getProcesso()->getNUPFormatado()
                        );

                        $this->juntadaResource->update(
                            $juntada->getId(),
                            $juntadaDTO,
                            $transactionId,
                            true
                        );
                    }
                }
            }

            $this->transactionManager->removeContext(
                'anexacaoProcesso',
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
