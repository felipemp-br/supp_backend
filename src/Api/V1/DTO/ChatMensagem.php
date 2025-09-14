<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/ChatMensagemMensagem.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem as ChatMensagemDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChatMensagem.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/chat_mensagem/{id}',
    jsonLDType: 'ChatMensagem',
    jsonLDContext: '/api/doc/#model-ChatMensagem'
)]
#[Form\Form]
class ChatMensagem extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    /**
     * Nome.
     */
    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $mensagem = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ComponenteDigital',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ComponenteDigitalDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital')]
    protected ?EntityInterface $componenteDigital = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Chat',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ChatDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Chat')]
    protected ?EntityInterface $chat = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuario = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ChatMensagem',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ChatMensagemDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem')]
    protected ?EntityInterface $replyTo = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    protected ?EntityInterface $usuarioTo = null;

    public function getMensagem(): ?string
    {
        return $this->mensagem;
    }

    /**
     * @return $this
     */
    public function setMensagem(?string $mensagem): self
    {
        $this->mensagem = $mensagem;
        $this->setVisited('mensagem');

        return $this;
    }

    public function getComponenteDigital(): ?EntityInterface
    {
        return $this->componenteDigital;
    }

    public function setComponenteDigital(?EntityInterface $componenteDigital): self
    {
        $this->componenteDigital = $componenteDigital;

        $this->setVisited('componenteDigital');

        return $this;
    }

    public function getChat(): ?EntityInterface
    {
        return $this->chat;
    }

    /**
     * @return $this
     */
    public function setChat(?EntityInterface $chat): self
    {
        $this->chat = $chat;
        $this->setVisited('chat');

        return $this;
    }

    public function getUsuario(): ?EntityInterface
    {
        return $this->usuario;
    }

    /**
     * @return $this
     */
    public function setUsuario(?EntityInterface $usuario): self
    {
        $this->usuario = $usuario;
        $this->setVisited('usuario');

        return $this;
    }

    public function getReplyTo(): ?EntityInterface
    {
        return $this->replyTo;
    }

    /**
     * @return $this
     */
    public function setReplyTo(?EntityInterface $replyTo): self
    {
        $this->replyTo = $replyTo;
        $this->setVisited('replyTo');

        return $this;
    }

    public function getUsuarioTo(): ?EntityInterface
    {
        return $this->usuarioTo;
    }

    /**
     * @return $this
     */
    public function setUsuarioTo(?EntityInterface $usuarioTo): self
    {
        $this->usuarioTo = $usuarioTo;

        return $this;
    }
}
