<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Setor/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Setor;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor;
use SuppCore\AdministrativoBackend\Api\V1\Resource\SetorResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\EspecieSetor;
use SuppCore\AdministrativoBackend\Repository\EspecieSetorRepository;
use SuppCore\AdministrativoBackend\Transaction\Context;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Criação de setor/unidade da espécie protocolo.
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    private EspecieSetorRepository $especieSetorRepository;
    private SetorResource $setorResource;
    private TransactionManager $transactionManager;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        EspecieSetorRepository $especieSetorRepository,
        SetorResource $setorResource,
        TransactionManager $transactionManager
    ) {
        $this->especieSetorRepository = $especieSetorRepository;
        $this->setorResource = $setorResource;
        $this->transactionManager = $transactionManager;
    }

    public function supports(): array
    {
        return [
            Setor::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Setor|RestDtoInterface|null $restDto
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // unidade? Cria o protocolo!
        if (!$restDto->getParent()) {
            $this->transactionManager->addContext(
                new Context('criacaoUnidade', true),
                $transactionId
            );

            $protocoloDTO = new Setor();
            $protocoloDTO->setNome('PROTOCOLO');
            $protocoloDTO->setAtivo($restDto->getAtivo());
            $protocoloDTO->setEspecieSetor(
                $this->especieSetorRepository->findByNome('PROTOCOLO')
            );
            $protocoloDTO->setPrefixoNUP($restDto->getPrefixoNUP());
            $protocoloDTO->setMunicipio($restDto->getMunicipio());
            $protocoloDTO->setParent($entity);
            $protocoloDTO->setSigla('PROT');
            $protocoloDTO->setUnidade($entity);

            $this->setorResource->create($protocoloDTO, $transactionId);

            $arquivoDTO = new Setor();
            $arquivoDTO->setNome('ARQUIVO');
            $arquivoDTO->setAtivo($restDto->getAtivo());
            /** @var EspecieSetor $especieSetor */
            $especieSetor = $this->especieSetorRepository->findByNome('ARQUIVO');
            $arquivoDTO->setEspecieSetor($especieSetor);
            $arquivoDTO->setPrefixoNUP($restDto->getPrefixoNUP());
            $arquivoDTO->setParent($entity);
            $arquivoDTO->setMunicipio($restDto->getMunicipio());
            $arquivoDTO->setSigla('ARQU');
            $arquivoDTO->setUnidade($entity);

            $this->setorResource->create($arquivoDTO, $transactionId);

            $this->transactionManager->removeContext(
                'criacaoUnidade',
                $transactionId
            );
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
