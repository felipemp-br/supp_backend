<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/VinculacaoDocumento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;


use DateTime;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use DMS\Filter\Rules as Filter;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use SuppCore\AdministrativoBackend\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoDocumento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/vinculacao_documento_assinatura_externa/{id}',
    jsonLDType: 'VinculacaoDocumentoAssinaturaExterna',
    jsonLDContext: '/api/doc/#model-VinculacaoDocumentoAssinaturaExterna'
)]
#[AppAssert\DtoUniqueEntity(
    fieldMapping: [
        'documento' => 'documento',
        'usuario' => 'usuario',
        'numeroDocumentoPrincipal' => 'numeroDocumentoPrincipal',
    ],
    entityClass: 'SuppCore\AdministrativoBackend\Entity\VinculacaoDocumentoAssinaturaExterna',
    message: 'Já existe uma solicitação de assinatura do Documento'
)]
#[Form\Form]
class VinculacaoDocumentoAssinaturaExterna extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Documento',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: DocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected EntityInterface|null $usuario = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Filter\Digits]
    #[AppAssert\CpfCnpj]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $numeroDocumentoPrincipal = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Assert\Email(message: 'Email em formato inválido!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $email = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $expiraEm = null;

    protected ?bool $assinado = false;

    protected ?string $padraoAssinatura = null;

    public function setDocumento(?EntityInterface $documento): self
    {
        $this->setVisited('documento');

        $this->documento = $documento;

        return $this;
    }

    public function getDocumento(): ?EntityInterface
    {
        return $this->documento;
    }

    public function getUsuario(): EntityInterface|null
    {
        return $this->usuario;
    }

    /**
     * @return $this
     */
    public function setUsuario(EntityInterface|null $usuario): self
    {
        $this->usuario = $usuario;

        $this->setVisited('usuario');

        return $this;
    }

    public function getNumeroDocumentoPrincipal(): ?string
    {
        return $this->numeroDocumentoPrincipal;
    }

    /**
     * @param string|null $numeroDocumentoPrincipal
     *
     * @return Dossie
     */
    public function setNumeroDocumentoPrincipal(?string $numeroDocumentoPrincipal): self
    {
        $this->setVisited('numeroDocumentoPrincipal');

        $this->numeroDocumentoPrincipal = $numeroDocumentoPrincipal;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->setVisited('email');

        $this->email = $email;

        return $this;
    }

    public function getExpiraEm(): ?DateTime
    {
        return $this->expiraEm;
    }

    /**
     * @return $this
     */
    public function setExpiraEm(?DateTime $expiraEm): self
    {
        $this->setVisited('expiraEm');

        $this->expiraEm = $expiraEm;

        return $this;
    }

    public function getAssinado(): ?bool
    {
        return $this->assinado;
    }

    /**
     * @return $this
     */
    public function setAssinado(?bool $assinado): self
    {
        $this->setVisited('assinado');

        $this->assinado = $assinado;

        return $this;
    }

    public function getPadraoAssinatura(): ?string
    {
        return $this->padraoAssinatura;
    }

    /**
     * @return $this
     */
    public function setPadraoAssinatura(?string $padraoAssinatura): self
    {
        $this->setVisited('padraoAssinatura');

        $this->padraoAssinatura = $padraoAssinatura;

        return $this;
    }

}
