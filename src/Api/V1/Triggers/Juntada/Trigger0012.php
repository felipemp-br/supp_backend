<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Juntada;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Class Trigger0012.
 *
 * @descSwagger  =Envia o email para o usuário externo se for feita uma juntada em NUP de Protocolo Eletrônico
 * @classeSwagger=Trigger0012
 *
 * @author       Felipe Pena <felipe.pena@datainfo.inf.br>
 */
class Trigger0012 implements TriggerInterface
{
    private ParameterBagInterface $parameterBag;
    private MailerInterface $mailer;

    /**
     * Trigger0001 constructor.
     */
    public function __construct(
        ParameterBagInterface $parameterBag,
        MailerInterface $mailer,
    ) {
        $this->mailer = $mailer;
        $this->parameterBag = $parameterBag;
    }

    public function supports(): array
    {
        return [
            JuntadaDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|null $restDto
     * @param EntityInterface       $entity
     * @param string                $transactionId
     *
     * @throws TransportExceptionInterface
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if ($restDto->getVolume()->getProcesso()->getProtocoloEletronico() &&
        $restDto->getCriadoPor() !== $restDto->getVolume()->getProcesso()->getCriadoPor()) {
            $destinatario = $restDto->getVolume()->getProcesso()->getCriadoPor();

            if ($destinatario) {
                $message = (new Email())
                    ->subject('Protocolo Eletrônico!')
                    ->from($this->parameterBag->get('supp_core.administrativo_backend.email_suporte'))
                    ->to($destinatario->getEmail())
                    ->text('Movimentação no Protocolo Eletrônico do SAPIENS! 
Informamos que houve movimentação no NUP '.$restDto->getVolume()->getProcesso()->getNUPFormatado().
                        ' do Procoloto Eletrônico. 
                        
                        Por favor, acesse o sistema em '.
                        $this->parameterBag->get('supp_core.administrativo_backend.url_sistema_frontend'));

                $this->mailer->send($message);
            }
        }
    }

    public function getOrder(): int
    {
        return 2;
    }
}
