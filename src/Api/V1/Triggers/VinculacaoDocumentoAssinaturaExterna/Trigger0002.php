<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/VinculacaoDocumentoAssinaturaExterna/Trigger0002.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\VinculacaoDocumentoAssinaturaExterna;

use Exception;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoDocumentoAssinaturaExterna;
use SuppCore\AdministrativoBackend\Entity\VinculacaoDocumentoAssinaturaExterna as VinculacaoDocumentoAssinaturaExternaEntity;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

/**
 * Class Trigger0002.
 *
 * @descSwagger=Envia solicitação por e-mail!
 * @classeSwagger=Trigger0002
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0002 implements TriggerInterface
{
    /**
     * Trigger0002 constructor.
     */
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly ParameterBagInterface $parameterBag,
        private readonly MailerInterface $mailer,
        private readonly Environment $twig,
        private readonly TransactionManager $transactionManager
    ) { }

    public function supports(): array
    {
        return [
            VinculacaoDocumentoAssinaturaExterna::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param VinculacaoDocumentoAssinaturaExterna|RestDtoInterface|null $restDto
     * @param VinculacaoDocumentoAssinaturaExternaEntity|EntityInterface|null $entity
     *
     * @throws Exception
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $email = $entity->getUsuario() ? $entity->getUsuario()->getEmail() : $entity->getEmail();

        $mensagem = $this->transactionManager->getContext('mensagem', $transactionId);

        $linkSistema = $this->parameterBag->get('supp_core.administrativo_backend.url_sistema_frontend');

        $link =  "$linkSistema/documento-externo/" . $entity->getDocumento()->getId();

        $message = (new Email())
            ->subject('Solicitação de Assinatura de Documento pelo '.$this->parameterBag
                ->get('supp_core.administrativo_backend.nome_sistema'))
            ->from($this->parameterBag->get('supp_core.administrativo_backend.email_suporte'))
            ->to($email)
            ->html(
                $this->twig->render(
                    $this->parameterBag->get('supp_core.administrativo_backend.template_envio_documento_assinatura_externa_email'),
                    [
                        'sistema' => $this->parameterBag->get('supp_core.administrativo_backend.nome_sistema'),
                        'ambiente' => $this->parameterBag->get(
                            'supp_core.administrativo_backend.kernel_environment_mapping'
                        )[$this->parameterBag->get('kernel.environment')],
                        'link' => $link,
                        'mensagem' => $mensagem?->getValue()
                    ]
                ),
            );

        $this->mailer->send($message);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
