<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Usuario/Trigger0003.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Usuario;

use DateTime;

use function hash;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario;
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
 * Class Trigger0003.
 *
 * @descSwagger=Envia email de boas-vindas ao usuário cadastrado!
 * @classeSwagger=Trigger0003
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    private Environment $twig;

    private MailerInterface $mailer;

    private ParameterBagInterface $parameterBag;

    /**
     * Trigger0003 constructor.
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
            Usuario::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function execute(?RestDtoInterface $restDto, EntityInterface $entity, string $transactionId): void
    {
        if (false === $restDto->getEnabled()) {
            $dateTime = new DateTime();

            $token = hash('SHA256', $restDto->getUserName().''.$dateTime->format('Ymd'));

            $url = $this->parameterBag->get('supp_core.administrativo_backend.url_sistema_frontend')
                .'/auth/activate/'.$restDto->getUsername().'/'.$token;

            $message = (new Email())
                ->subject('Bem-vindo ao '.$this->parameterBag->get('supp_core.administrativo_backend.nome_sistema'))
                ->from($this->parameterBag->get('supp_core.administrativo_backend.email_suporte'))
                ->to($entity->getEmail())
                ->html(
                    $this->twig->render(
                        $this->parameterBag->get('supp_core.administrativo_backend.template_email_auto_cadastro'),
                        [
                            'sistema' => $this->parameterBag->get('supp_core.administrativo_backend.nome_sistema'),
                            'ambiente' => $this->parameterBag->get(
                                'supp_core.administrativo_backend.kernel_environment_mapping'
                            )[$this->parameterBag->get('kernel.environment')],
                            'url' => $url,
                        ]
                    )
                );
        } else {
            $message = (new Email())
                ->subject('Bem-vindo ao '.$this->parameterBag->get('supp_core.administrativo_backend.nome_sistema'))
                ->from($this->parameterBag->get('supp_core.administrativo_backend.email_suporte'))
                ->to($entity->getEmail())
                ->html(
                    $this->twig->render(
                        $this->parameterBag->get('supp_core.administrativo_backend.template_email_boa_vindas'),
                        [
                            'sistema' => $this->parameterBag->get('supp_core.administrativo_backend.nome_sistema'),
                            'ambiente' => $this->parameterBag->get(
                                'supp_core.administrativo_backend.kernel_environment_mapping'
                            )[$this->parameterBag->get('kernel.environment')],
                            'url' => $this->parameterBag->get('supp_core.administrativo_backend.url_sistema_frontend'),
                            'username' => $entity->getUsername(),
                            'nome' => $entity->getNome(),
                            'senha' => $entity->getPlainPassword(),
                        ]
                    )
                );
        }

        $this->mailer->send($message);
    }

    public function getOrder(): int
    {
        return 2;
    }
}
