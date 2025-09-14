<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Chat/Pipe0002.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Chat;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\ChatParticipante;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Chat as ChatEntity;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Pipe0002.
 */
class Pipe0002 implements PipeInterface
{
    /**
     * Pipe0002 constructor.
     * @param RequestStack $requestStack
     * @param TransactionManager $transactionManager
     * @param ComponenteDigitalResource $componenteDigitalResource
     */
    public function __construct(private RequestStack $requestStack,
                                private TransactionManager $transactionManager,
                                private ComponenteDigitalResource $componenteDigitalResource)
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

        if (!$entity->getGrupo() && ($transactionId || $request?->get('context'))) {

            $chatIndividualUsuario = null;

            if ($request) {
                $context = json_decode($this->requestStack->getCurrentRequest()->get('context'));
                if (isset($context->chat_individual_usuario)) {
                    $chatIndividualUsuario = $context->chat_individual_usuario;
                }
            } else {
                $chatIndividualUsuario = $this->transactionManager
                    ->getContext('chat_individual_usuario', $transactionId)?->getValue();
            }

            if ($chatIndividualUsuario) {
                /** @var ChatParticipante|null $chatParticipante */
                $chatParticipante = $entity->getParticipantes()
                    ->filter(
                        fn(ChatParticipante $chatParticipante) =>
                            $chatParticipante->getUsuario()->getId() !== $chatIndividualUsuario
                    )
                    ->first();

                if ($chatParticipante) {
                    $restDTO->setNome($chatParticipante->getUsuario()->getNome());
                    $capa = $chatParticipante->getUsuario()->getImgPerfil();

                    if ($capa) {
                        $mapper = $this->componenteDigitalResource
                            ->getDtoMapperManager()
                            ->getMapper($this->componenteDigitalResource->getDtoClass());

                        $restDTO->setCapa(
                            $mapper->convertEntityToDto(
                                $capa,
                                $this->componenteDigitalResource->getDtoClass(),
                                []
                            )
                        );
                    }
                }
            }


        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
