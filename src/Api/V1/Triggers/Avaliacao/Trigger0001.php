<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Avaliacao/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Avaliacao;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Avaliacao as AvaliacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ObjetoAvaliado as ObjetoAvaliadoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ObjetoAvaliadoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Avaliacao as AvaliacaoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Integracao\Avaliacao\AvaliacaoManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Cria ou atualiza o objeto avaliado.
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0001 implements TriggerInterface
{
    public function __construct(
        private ObjetoAvaliadoResource $objetoAvaliadoResource,
        private AvaliacaoManager $avaliacaoManager
    ) {
    }

    public function supports(): array
    {
        return [
            AvaliacaoDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param AvaliacaoDTO|RestDtoInterface|null $restDto
     * @param AvaliacaoEntity|EntityInterface    $entity
     * @param string                             $transactionId
     */
    public function execute(
        AvaliacaoDTO|RestDtoInterface|null $restDto,
        AvaliacaoEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        // verificar se existe um objeto avaliado e se nao existe
        $objetoAvaliadoEntity = $this
            ->objetoAvaliadoResource
            ->getRepository()
            ->findOneBy(
                [
                    'classe' => $restDto->getClasse(),
                    'objetoId' => $restDto->getObjetoId(),
                ]
            );

        if (!$objetoAvaliadoEntity) {
            // recupera o motor de avaliacao
            $motorAvaliacao = $this
                ->avaliacaoManager
                ->getAvaliacao($restDto->getClasse(), []);

            $objetoAvaliadoDTO = new ObjetoAvaliadoDTO();
            $objetoAvaliadoDTO->setClasse($restDto->getClasse());
            $objetoAvaliadoDTO->setObjetoId($restDto->getObjetoId());
            // cria o objeto avaliado
            $objetoAvaliadoEntity = $this->objetoAvaliadoResource
                ->create($objetoAvaliadoDTO, $transactionId);
            // acrescenta o valor da avaliacao atual
            $objetoAvaliadoEntity->setAvaliacaoResultante(
                $motorAvaliacao->getValorAvaliacaoResultante(
                    $objetoAvaliadoEntity,
                    $restDto,
                    []
                )
            );
            // incrementa a quantidade de avaliacoes
            $objetoAvaliadoEntity->setQuantidadeAvaliacoes(1);
        } else {
            // recupera o motor de avaliacao
            $motorAvaliacao = $this
                ->avaliacaoManager
                ->getAvaliacao($restDto->getClasse(), []);

            $objetoAvaliadoDTO = $this
                ->objetoAvaliadoResource
                ->getDtoForEntity($objetoAvaliadoEntity->getId(), ObjetoAvaliadoDTO::class);

            // acrescenta o valor da avaliacao atual
            $objetoAvaliadoDTO->setAvaliacaoResultante(
                    $motorAvaliacao->getValorAvaliacaoResultante(
                        $objetoAvaliadoEntity,
                        $restDto,
                        []
                    )
            );
            // incrementa a quantidade de avaliacoes
            $objetoAvaliadoDTO->setQuantidadeAvaliacoes(
                (int) $objetoAvaliadoEntity->getQuantidadeAvaliacoes() + 1
            );
            // atualiza e sobrescreve o objeto avaliado entity
            $objetoAvaliadoEntity = $this
                ->objetoAvaliadoResource
                ->update($objetoAvaliadoEntity->getId(), $objetoAvaliadoDTO, $transactionId);
        }

        $restDto->setObjetoAvaliado($objetoAvaliadoEntity);
    }

    public function getOrder(): int
    {
        return 1;
    }
}
