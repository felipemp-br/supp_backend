<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Notificacao.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use DMS\Filter\Rules as Filter;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeNotificacao as ModalidadeNotificacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoNotificacao as TipoNotificacaoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Notificacao.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/notificacao/{id}',
    jsonLDType: 'Notificacao',
    jsonLDContext: '/api/doc/#model-Notificacao'
)]
#[Form\Form]
class Notificacao extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $remetente = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O destinatário da notificação não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $destinatario = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ModalidadeNotificacao',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'A modalidade da notificação não pode ser nula!')]
    #[OA\Property(ref: new Model(type: ModalidadeNotificacaoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ModalidadeNotificacao')]
    protected ?EntityInterface $modalidadeNotificacao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraExpiracao = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraLeitura = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'Conteúdo não pode estar em branco.')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $conteudo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected ?bool $urgente = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $contexto = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\TipoNotificacao',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: TipoNotificacaoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\TipoNotificacao')]
    protected ?EntityInterface $tipoNotificacao = null;

    /**
     * @return UsuarioDTO|UsuarioEntity|EntityInterface|int|null
     */
    public function getRemetente(): ?EntityInterface
    {
        return $this->remetente;
    }

    /**
     * @param UsuarioDTO|UsuarioEntity|EntityInterface|int|null $remetente
     */
    public function setRemetente(?EntityInterface $remetente): self
    {
        $this->setVisited('remetente');

        $this->remetente = $remetente;

        return $this;
    }

    /**
     * @return UsuarioDTO|UsuarioEntity|EntityInterface|int|null
     */
    public function getDestinatario(): ?EntityInterface
    {
        return $this->destinatario;
    }

    /**
     * @param UsuarioDTO|UsuarioEntity|EntityInterface|int|null $destinatario
     */
    public function setDestinatario(?EntityInterface $destinatario): self
    {
        $this->setVisited('destinatario');

        $this->destinatario = $destinatario;

        return $this;
    }

    public function getModalidadeNotificacao(): ?EntityInterface
    {
        return $this->modalidadeNotificacao;
    }

    public function setModalidadeNotificacao(?EntityInterface $modalidadeNotificacao): self
    {
        $this->setVisited('modalidadeNotificacao');

        $this->modalidadeNotificacao = $modalidadeNotificacao;

        return $this;
    }

    public function getDataHoraExpiracao(): ?DateTime
    {
        return $this->dataHoraExpiracao;
    }

    public function setDataHoraExpiracao(?DateTime $dataHoraExpiracao): self
    {
        $this->setVisited('dataHoraExpiracao');

        $this->dataHoraExpiracao = $dataHoraExpiracao;

        return $this;
    }

    public function getDataHoraLeitura(): ?DateTime
    {
        return $this->dataHoraLeitura;
    }

    public function setDataHoraLeitura(?DateTime $dataHoraLeitura): self
    {
        $this->setVisited('dataHoraLeitura');

        $this->dataHoraLeitura = $dataHoraLeitura;

        return $this;
    }

    public function getConteudo(): ?string
    {
        return $this->conteudo;
    }

    public function setConteudo(?string $conteudo): self
    {
        $this->setVisited('conteudo');

        $this->conteudo = $conteudo;

        return $this;
    }

    public function getUrgente(): ?bool
    {
        return $this->urgente;
    }

    public function setUrgente(?bool $urgente): self
    {
        $this->setVisited('urgente');

        $this->urgente = $urgente;

        return $this;
    }

    public function getContexto(): ?string
    {
        return $this->contexto;
    }

    public function setContexto(?string $contexto): self
    {
        $this->setVisited('contexto');

        $this->contexto = $contexto;

        return $this;
    }

    /**
     * @return Tipo|null
     */
    public function getTipoNotificacao(): ?EntityInterface
    {
        return $this->tipoNotificacao;
    }

    public function setTipoNotificacao(?EntityInterface $tipoNotificacao): self
    {
        $this->setVisited('tipoNotificacao');

        $this->tipoNotificacao = $tipoNotificacao;

        return $this;
    }
}
