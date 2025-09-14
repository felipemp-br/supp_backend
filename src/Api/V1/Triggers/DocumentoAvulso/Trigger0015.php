<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/DocumentoAvulso/Trigger0015.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\DocumentoAvulso;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoSolicitacao;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0015.
 *
 * @descSwagger  =Envia o DocumentoAvulso via Barramento
 * @classeSwagger=Trigger0015
 *
 */
class Trigger0015 implements TriggerInterface
{
    private BarramentoSolicitacao $barramentoSolicitacao;
    private ParameterBagInterface $parameterBag;

    /**
     * Trigger0015 constructor.
     * @param BarramentoSolicitacao $barramentoSolicitacao
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(
        BarramentoSolicitacao $barramentoSolicitacao,
        ParameterBagInterface $parameterBag
    ) {
        $this->barramentoSolicitacao = $barramentoSolicitacao;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @return array
     */
    public function supports(): array
    {
        return [
            DocumentoAvulso::class => [
                'afterRemeter',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface $entity
     * @param string $transactionId
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ('barramento' === $entity->getMecanismoRemessa()) {
            $config = $this->parameterBag->get('integracao_barramento');
            if ($entity->getPessoaDestino()?->getVinculacaoPessoaBarramento() !== null) {
                $this->barramentoSolicitacao->enviarDocumento(
                    $config['config']['repositorio_siorg'],
                    $config['config']['id_organizacional'],
                    $entity->getPessoaDestino()->getVinculacaoPessoaBarramento()->getRepositorio(),
                    $entity->getPessoaDestino()->getVinculacaoPessoaBarramento()->getEstrutura(),
                    $entity->getId(),
                    $transactionId
                );
            }
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
