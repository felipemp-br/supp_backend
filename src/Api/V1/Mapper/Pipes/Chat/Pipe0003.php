<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Chat/Pipe0003.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Chat;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ChatParticipanteResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Chat as ChatEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Pipe0003.
 */
class Pipe0003 implements PipeInterface
{
    /**
     * Pipe0003 constructor.
     *
     * @param RequestStack             $requestStack
     * @param TransactionManager       $transactionManager
     * @param ChatParticipanteResource $chatParticipanteResource
     */
    public function __construct(private RequestStack $requestStack,
                                private TransactionManager $transactionManager,
                                private ChatParticipanteResource $chatParticipanteResource)
    {
    }

    public function supports(): array
    {
        return [
            ChatDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param ChatDTO|RestDtoInterface|null $restDTO
     * @param ChatEntity|EntityInterface    $entity
     *
     * @throws Exception
     */
    public function execute(
        ChatDTO | RestDtoInterface | null &$restDTO,
        ChatEntity | EntityInterface $entity
    ): void {
        $transactionId = $this->transactionManager->getCurrentTransactionId();
        $request = $this->requestStack->getCurrentRequest();
        $usuarioId = null;

        if ($transactionId) {
            $usuarioId = $this->transactionManager
                ->getContext('chat_participante', $transactionId)?->getValue();
        }

        if ($request?->get('context')) {
            $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));

            if (isset($context->chat_participante)) {
                $usuarioId = $context?->chat_participante;
            }
        }

        if ($usuarioId) {
            $chatParticipante = $entity->getParticipantes()
                ->filter(fn ($participante) => $participante->getUsuario()->getId() === $usuarioId)
                ->first();

            if ($chatParticipante) {
                $chatParticipanteDTO = $this->chatParticipanteResource
                    ->getDtoMapperManager()
                    ->getMapper($this->chatParticipanteResource->getDtoClass())
                    ->convertEntityToDto(
                        $chatParticipante,
                        $this->chatParticipanteResource->getDtoClass(),
                        [
                            'usuario',
                        ]
                    );
                $restDTO->setChatParticipante($chatParticipanteDTO);

                if ($entity->getUltimaMensagem()
                    && $entity->getUltimaMensagem()->getCriadoEm() < $chatParticipante->getCriadoEm()) {
                    $restDTO->setUltimaMensagem(null);
                }
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
