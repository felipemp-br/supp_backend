<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Usuario/Trigger0004.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Usuario;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class Trigger0004.
 *
 * @descSwagger=Envia email para o usuário que teve a senha resetada!
 * @classeSwagger=Trigger0004
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0004 implements TriggerInterface
{
    private Environment $twig;

    private MailerInterface $mailer;

    private ParameterBagInterface $parameterBag;

    /**
     * Trigger0004 constructor.
     */
    public function __construct(
        Environment $twig,
        MailerInterface $mailer,
        ParameterBagInterface $parameterBag
    ) {
        $this->twig = $twig;
        $this->mailer = $mailer;
        $this->parameterBag = $parameterBag;
    }

    public function supports(): array
    {
        return [
            UsuarioDTO::class => [
                'afterResetaSenha',
            ],
        ];
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        $message = (new Email())
            ->subject('Reset de senha no '.$this->parameterBag
                ->get('supp_core.administrativo_backend.nome_sistema'))
            ->from($this->parameterBag->get('supp_core.administrativo_backend.email_suporte'))
            ->to($entity->getEmail())
            ->html(
                $this->twig->render(
                    $this->parameterBag->get('supp_core.administrativo_backend.template_email_reset_senha'),
                    [
                        'sistema' => $this->parameterBag->get('supp_core.administrativo_backend.nome_sistema'),
                        'ambiente' => $this->parameterBag->get(
                            'supp_core.administrativo_backend.kernel_environment_mapping'
                        )[$this->parameterBag->get('kernel.environment')],
                        'url' => $this->parameterBag->get('supp_core.administrativo_backend.url_sistema_frontend'),
                        'username' => $entity->getUsername(),
                        'nome' => $entity->getNome(),
                        'senha' => $restDto->getPlainPassword(),
                    ]
                )
            );

        $this->mailer->send($message);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
