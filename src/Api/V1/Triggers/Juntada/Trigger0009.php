<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Compartilhamento/Trigger0009.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Compartilhamento;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Notificacao;
use SuppCore\AdministrativoBackend\Api\V1\Resource\NotificacaoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\TipoNotificacaoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Compartilhamento as CompartilhamentoEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\TipoNotificacao as TipoNotificacaoEntity;
use SuppCore\AdministrativoBackend\Repository\CompartilhamentoRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeNotificacaoRepository;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0009.
 *
 * @descSwagger=Gera uma notificação para quem possuir um processo aberto quando o documento é juntado!
 * @classeSwagger=Trigger0009
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0009 implements TriggerInterface
{
    private CompartilhamentoRepository $compartilhamentoRepository;

    private ModalidadeNotificacaoRepository $modalidadeNotificacaoRepository;

    private NotificacaoResource $notificacaoResource;

    private TipoNotificacaoResource $tipoNotificacaoResource;

    /**
     * Trigger0009 constructor.
     */
    public function __construct(
        NotificacaoResource $notificacaoResource,
        CompartilhamentoRepository $compartilhamentoRepository,
        ModalidadeNotificacaoRepository $modalidadeNotificacaoRepository,
        TipoNotificacaoResource $tipoNotificacaoResource,
        private ParameterBagInterface $parameterBag
    ) {
        $this->notificacaoResource = $notificacaoResource;
        $this->modalidadeNotificacaoRepository = $modalidadeNotificacaoRepository;
        $this->compartilhamentoRepository = $compartilhamentoRepository;
        $this->tipoNotificacaoResource = $tipoNotificacaoResource;
    }

    public function supports(): array
    {
        return [
            Juntada::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param Juntada|RestDtoInterface|null          $restDto
     * @param CompartilhamentoEntity|EntityInterface $entity
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        // Usuários que já foram notificados
        $alreadySent = [];

        $tipoNotificacao = $this->tipoNotificacaoResource
        ->findOneBy(['nome' => $this->parameterBag->get('constantes.entidades.tipo_notificacao.const_1')]);

        $modalidadeNotificacao = $this->modalidadeNotificacaoRepository
        ->findOneBy(['valor' => $this->parameterBag->get('constantes.entidades.modalidade_notificacao.const_1')]);

        /** @var Compartilhamento $compartilhamentoAberto */
        foreach ($this->compartilhamentoRepository->findUsuarioByProcesso(
            $restDto->getVolume()->getProcesso()->getId()
        ) as $compartilhamentoAberto) {        

            // Pula a notificação para o usuário que já foi notificado
            if(in_array($compartilhamentoAberto->getUsuario()->getId(), $alreadySent) ) {
                continue;
            }

            $notificacaoDTO = new Notificacao();
            $contexto = json_encode(['id' => $compartilhamentoAberto->getProcesso()->getId()]);
            $notificacaoDTO->setDestinatario($compartilhamentoAberto->getUsuario());
            $notificacaoDTO->setModalidadeNotificacao($modalidadeNotificacao);
            $notificacaoDTO->setTipoNotificacao($tipoNotificacao);
            $notificacaoDTO->setContexto($contexto);
            $notificacaoDTO->setConteudo(
                'HOUVE UMA JUNTADA NO NUP '.
                $restDto->getVolume()->getProcesso()->getNUPFormatado().
                ', O QUAL VOCÊ POSSUI UM ACOMPANHAMENTO ABERTO!'
            );

            $this->notificacaoResource->create($notificacaoDTO, $transactionId);

            // Adiciona o usuário que foi notificado em um processo
            $alreadySent[] = $compartilhamentoAberto->getUsuario()->getId();
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
