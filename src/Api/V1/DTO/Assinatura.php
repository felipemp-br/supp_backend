<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Assinatura.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital as ComponenteDigitalDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\OrigemDados;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital as ComponenteDigitalEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Assinatura.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/assinatura/{id}',
    jsonLDType: 'Assinatura',
    jsonLDContext: '/api/doc/#model-Assinatura'
)]
#[Form\Form]
class Assinatura extends RestDto
{
    use IdUuid;
    use Blameable;
    use Softdeleteable;
    use Timeblameable;
    use OrigemDados;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $algoritmoHash = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ],
        methods: [new Form\Method('createMethod')]
    )]
    #[Serializer\Exclude]
    #[OA\Property(type: 'string')]
    protected ?string $plainPassword = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $assinatura = null;

    /*#[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]*/
    #[OA\Property(type: 'string', maxLength: 20)]
    #[DTOMapper\Property]
    protected ?string $padrao = null;

    #[OA\Property(type: 'string', maxLength: 20)]
    #[DTOMapper\Property]
    protected ?string $protocolo = null;


    #[OA\Property(type: 'string')]
    protected ?string $assinadoPor = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $cadeiaCertificadoPEM = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $cadeiaCertificadoPkiPath = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\DateTimeType',
        options: [
            'widget' => 'single_text',
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraAssinatura = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ComponenteDigital',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: ComponenteDigitalDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital')]
    protected ?EntityInterface $componenteDigital = null;

    #[Serializer\Exclude]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\ComponenteDigital',
            'multiple' => true,
            'required' => false,
        ]
    )]
    public $componentesDigitaisBloco = [];

    public function getAlgoritmoHash(): ?string
    {
        return $this->algoritmoHash;
    }

    public function setAlgoritmoHash(?string $algoritmoHash): self
    {
        $this->setVisited('algoritmoHash');

        $this->algoritmoHash = $algoritmoHash;

        return $this;
    }

    public function getAssinatura(): ?string
    {
        return $this->assinatura;
    }

    public function setAssinatura(?string $assinatura): self
    {
        $this->setVisited('assinatura');

        $this->assinatura = $assinatura;

        return $this;
    }

    public function getPadrao(): ?string
    {
        return $this->padrao;
    }

    public function setPadrao(?string $padrao): self
    {
        $this->setVisited('padrao');
        $this->padrao = $padrao;
        return $this;
    }

    public function getProtocolo(): ?string
    {
        return $this->protocolo;
    }

    public function setProtocolo(?string $protocolo): self
    {
        $this->setVisited('protocolo');
        $this->protocolo = $protocolo;
        return $this;
    }


    public function getCadeiaCertificadoPEM(): ?string
    {
        return $this->cadeiaCertificadoPEM;
    }

    public function setCadeiaCertificadoPEM(?string $cadeiaCertificadoPEM): self
    {
        $this->setVisited('cadeiaCertificadoPEM');

        $this->cadeiaCertificadoPEM = $cadeiaCertificadoPEM;

        return $this;
    }

    public function getCadeiaCertificadoPkiPath(): ?string
    {
        return $this->cadeiaCertificadoPkiPath;
    }

    public function setCadeiaCertificadoPkiPath(?string $cadeiaCertificadoPkiPath): self
    {
        $this->setVisited('cadeiaCertificadoPkiPath');

        $this->cadeiaCertificadoPkiPath = $cadeiaCertificadoPkiPath;

        return $this;
    }

    public function getDataHoraAssinatura(): ?DateTime
    {
        return $this->dataHoraAssinatura;
    }

    public function setDataHoraAssinatura(?DateTime $dataHoraAssinatura): self
    {
        $this->setVisited('dataHoraAssinatura');

        $this->dataHoraAssinatura = $dataHoraAssinatura;

        return $this;
    }


    /**
     * @return ComponenteDigitalEntity|ComponenteDigitalDTO|null
     */
    public function getComponenteDigital(): ?EntityInterface
    {
        return $this->componenteDigital;
    }

    public function setComponenteDigital(?EntityInterface $componenteDigital): self
    {
        $this->setVisited('componenteDigital');

        $this->componenteDigital = $componenteDigital;

        return $this;
    }

    public function getAssinadoPor(): ?string
    {
        return $this->assinadoPor;
    }

    public function setAssinadoPor(?string $assinadoPor): self
    {
        $this->setVisited('assinadoPor');

        $this->assinadoPor = $assinadoPor;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @return $this
     */
    public function setPlainPassword(?string $plainPassword = null): self
    {
        if (null !== $plainPassword) {
            $this->setVisited('plainPassword');

            $this->plainPassword = $plainPassword;
        }

        return $this;
    }

    public function addComponentesDigitaisBloco(?EntityInterface $componentesDigitaisBloco): self
    {
        $this->componentesDigitaisBloco[] = $componentesDigitaisBloco;

        return $this;
    }

    /**
     * @return self
     */
    public function resetComponentesDigitaisBloco()
    {
        $this->componentesDigitaisBloco = [];

        return $this;
    }

    /**
     * @return array
     */
    public function getComponentesDigitaisBlocoBloco()
    {
        return $this->componentesDigitaisBloco;
    }
}
