<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tramitacao/Trigger0008.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tramitacao;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoProcessoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use SuppCore\AdministrativoBackend\Barramento\Service\BarramentoSolicitacao;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0008.
 *
 * @descSwagger=Envia a remessa setando dataHoraRecebimento e setorAtual do processo
 * @classeSwagger=Trigger0008
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0008 implements TriggerInterface
{
    private BarramentoSolicitacao $barramentoSolicitacao;
    private ParameterBagInterface $parameterBag;
    private VinculacaoProcessoResource $vinculacaoProcessoResource;
    private TransactionManager $transactionManager;

    /**
     * Trigger0008 constructor.
     * @param BarramentoSolicitacao $barramentoSolicitacao
     * @param ParameterBagInterface $parameterBag
     * @param VinculacaoProcessoResource $vinculacaoProcessoResource
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        BarramentoSolicitacao $barramentoSolicitacao,
        ParameterBagInterface $parameterBag,
        VinculacaoProcessoResource $vinculacaoProcessoResource,
        TransactionManager $transactionManager
    ) {
        $this->barramentoSolicitacao = $barramentoSolicitacao;
        $this->parameterBag = $parameterBag;
        $this->vinculacaoProcessoResource = $vinculacaoProcessoResource;
        $this->transactionManager = $transactionManager;
    }

    /**
     * @return array
     */
    public function supports(): array
    {
        return [
            Tramitacao::class => [
                'afterCreate',
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
        if (!$this->transactionManager->getContext('criacaoProcessoBarramento', $transactionId)) {
            $estaApensado = $this->vinculacaoProcessoResource->getRepository()->estaApensada(
                $entity->getProcesso()->getId()
            );

            if ('barramento' === $entity->getMecanismoRemessa() && !$estaApensado) {
                $config = $this->parameterBag->get('integracao_barramento');

                $this->barramentoSolicitacao->enviarProcesso(
                    $config['config']['repositorio_siorg'],
                    $config['config']['id_organizacional'],
                    $entity->getPessoaDestino()->getVinculacaoPessoaBarramento()->getRepositorio(),
                    $entity->getPessoaDestino()->getVinculacaoPessoaBarramento()->getEstrutura(),
                    $entity->getUuid(),
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
