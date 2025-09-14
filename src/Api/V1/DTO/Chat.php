<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Chat.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem as ChatMensagemDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante as ChatParticipanteDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Chat.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(jsonLDId: '/v1/administrativo/chat/{id}', jsonLDType: 'Chat', jsonLDContext: '/api/doc/#model-Chat')]
#[Form\Form]
class Chat extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    use Ativo;

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
    protected ?string $nome = null;

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
    protected ?string $descricao = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ComponenteDigital',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ComponenteDigitalDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital')]
    protected ?EntityInterface $capa = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $grupo = false;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ChatMensagem',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ChatMensagemDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ChatMensagem')]
    protected ?EntityInterface $ultimaMensagem = null;

    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ChatParticipante',
        dtoGetter: 'getParticipantes',
        dtoSetter: 'addParticipante',
        collection: true
    )]
    protected $participantes = [];

    #[OA\Property(ref: new Model(type: ChatParticipanteDTO::class))]
    protected ?RestDto $chatParticipante = null;

    public function __construct()
    {
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    /**
     * @return $this
     */
    public function setNome(?string $nome): self
    {
        $this->nome = $nome;
        $this->setVisited('nome');

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    /**
     * @return $this
     */
    public function setDescricao(?string $descricao): self
    {
        $this->descricao = $descricao;
        $this->setVisited('descricao');

        return $this;
    }

    public function getCapa(): ?EntityInterface
    {
        return $this->capa;
    }

    public function setCapa(?EntityInterface $capa): self
    {
        $this->capa = $capa;

        $this->setVisited('capa');

        return $this;
    }

    public function getGrupo(): ?bool
    {
        return $this->grupo;
    }

    /**
     * @return $this
     */
    public function setGrupo(?bool $grupo): self
    {
        $this->grupo = $grupo;
        $this->setVisited('grupo');

        return $this;
    }

    public function getUltimaMensagem(): ?EntityInterface
    {
        return $this->ultimaMensagem;
    }

    /**
     * @return $this
     */
    public function setUltimaMensagem(?EntityInterface $ultimaMensagem): self
    {
        $this->ultimaMensagem = $ultimaMensagem;
        $this->setVisited('ultimaMensagem');

        return $this;
    }

    public function addParticipante(ChatParticipante $participante): self
    {
        $this->participantes[] = $participante;

        return $this;
    }

    public function getParticipantes(): array
    {
        return $this->participantes;
    }

    /**
     * @return $this
     */
    public function clearParticipantes(): self
    {
        $this->participantes = [];

        return $this;
    }

    public function getChatParticipante(): ?RestDto
    {
        return $this->chatParticipante;
    }

    public function setChatParticipante(?RestDto $chatParticipante): self
    {
        $this->chatParticipante = $chatParticipante;

        return $this;
    }
}
