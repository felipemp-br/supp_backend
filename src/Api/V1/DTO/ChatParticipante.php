<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/ChatParticipanteMensagem.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Chat as ChatDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Class ChatParticipante.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/chat_participante/{id}',
    jsonLDType: 'ChatParticipante',
    jsonLDContext: '/api/doc/#model-ChatParticipante'
)]
#[Form\Form]
class ChatParticipante extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;

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
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $administrador = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $ultimaVisualizacao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $mensagensNaoLidas = null;

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

    public function getAdministrador(): ?bool
    {
        return $this->administrador;
    }

    /**
     * @return $this
     */
    public function setAdministrador(?bool $administrador): self
    {
        $this->administrador = $administrador;
        $this->setVisited('administrador');

        return $this;
    }

    public function getUltimaVisualizacao(): ?DateTime
    {
        return $this->ultimaVisualizacao;
    }

    /**
     * @return $this
     */
    public function setUltimaVisualizacao(?DateTime $ultimaVisualizacao): self
    {
        $this->ultimaVisualizacao = $ultimaVisualizacao;
        $this->setVisited('ultimaVisualizacao');

        return $this;
    }

    public function getMensagensNaoLidas(): ?int
    {
        return $this->mensagensNaoLidas;
    }

    /**
     * @return $this
     */
    public function setMensagensNaoLidas(?int $mensagensNaoLidas): self
    {
        $this->mensagensNaoLidas = $mensagensNaoLidas;
        $this->setVisited('mensagensNaoLidas');

        return $this;
    }
}
