<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Tarefa/Trigger0024.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Tarefa;

use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoEtiquetaResource;
use SuppCore\AdministrativoBackend\Entity\Tarefa as TarefaEntity;
use SuppCore\AdministrativoBackend\Repository\VinculacaoEtiquetaRepository;
use SuppCore\AdministrativoBackend\Rest\Message\PushMessage;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerReadInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0024.
 *
 * @descSwagger=Realiza o push de vinculacoes etiquetas
 * @classeSwagger=Trigger0024
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0024 implements TriggerReadInterface
{
    /**
     * Trigger0024 constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(
        private RequestStack $requestStack,
        private VinculacaoEtiquetaRepository $vinculacaoEtiquetaRepository,
        private TokenStorageInterface $tokenStorage,
        private TransactionManager $transactionManager
    ) {
    }

    public function supports(): array
    {
        return [
            TarefaEntity::class => [
                'afterFind',
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
        if (!$this->tokenStorage->getToken() ||
            !$this->tokenStorage->getToken()->getUser() ||
            !$this->tokenStorage->getToken()->getUser()->getUserIdentifier() ||
            !$this->requestStack->getCurrentRequest() ||
            !$this->requestStack->getCurrentRequest()->get('context')
        ) {
            return;
        }

        $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));

        if ($context && isset($context->push) && ('vinculacoesEtiquetas.etiqueta' === $context->push)) {
            $tarefasId = [];
            /** @var TarefaEntity $tarefa */
            foreach ($result['entities'] as $tarefa) {
                $tarefasId[] = $tarefa->getId();
            }
            if (count($tarefasId)) {
                $resultArray = $this->vinculacaoEtiquetaRepository->findByUuidByTarefaId($tarefasId);
                foreach ($resultArray as $r) {
                    $pushMessage = new PushMessage();
                    $pushMessage->setUuid($r['vinculacaoEtiquetaUuid']);
                    $pushMessage->setResource(VinculacaoEtiquetaResource::class);
                    $pushMessage->setChannel($this->tokenStorage->getToken()->getUser()->getUserIdentifier());
                    $pushMessage->setPopulate(['etiqueta']);
                    $pushMessage->setOperation('AddChildData');
                    $pushMessage->setParentType('tarefa');
                    $pushMessage->setParentId($r['tarefaId']);
                    $this->transactionManager->addAsyncDispatch(
                        $pushMessage,
                        $this->transactionManager->getCurrentTransactionId()
                    );
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
