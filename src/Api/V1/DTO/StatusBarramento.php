<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/StatusBarramento.php.
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso as DocumentoAvulsoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao as TramitacaoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Class StatusBarramento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Form\Form]
class StatusBarramento extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $idtComponenteDigital = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected int $idt;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Processo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Processo')]
    protected ?EntityInterface $processo = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\DocumentoAvulso',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: DocumentoAvulsoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\DocumentoAvulso')]
    protected ?EntityInterface $documentoAvulso = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Tramitacao',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: TramitacaoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tramitacao')]
    protected ?EntityInterface $tramitacao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected int $codSituacaoTramitacao;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $codigoErro = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $mensagemErro = null;

    public function getIdt(): int
    {
        return $this->idt;
    }

    public function setIdt(int $idt): self
    {
        $this->setVisited('idt');

        $this->idt = $idt;

        return $this;
    }

    public function getTramitacao(): ?EntityInterface
    {
        return $this->tramitacao;
    }

    public function setTramitacao(?EntityInterface $tramitacao): self
    {
        $this->setVisited('tramitacao');

        $this->tramitacao = $tramitacao;

        return $this;
    }

    public function getCodSituacaoTramitacao(): int
    {
        return $this->codSituacaoTramitacao;
    }

    public function setCodSituacaoTramitacao(int $codSituacaoTramitacao): self
    {
        $this->setVisited('codSituacaoTramitacao');

        $this->codSituacaoTramitacao = $codSituacaoTramitacao;

        return $this;
    }

    public function getIdtComponenteDigital(): ?int
    {
        return $this->idtComponenteDigital;
    }

    public function setIdtComponenteDigital(?int $idtComponenteDigital): self
    {
        $this->idtComponenteDigital = $idtComponenteDigital;

        $this->setVisited('idtComponenteDigital');

        return $this;
    }

    public function getDocumentoAvulso(): ?EntityInterface
    {
        return $this->documentoAvulso;
    }

    public function setDocumentoAvulso(?EntityInterface $documentoAvulso): self
    {
        $this->setVisited('documentoAvulso');

        $this->documentoAvulso = $documentoAvulso;

        return $this;
    }

    public function getProcesso(): ?EntityInterface
    {
        return $this->processo;
    }

    public function setProcesso(?EntityInterface $processo): self
    {
        $this->setVisited('processo');

        $this->processo = $processo;

        return $this;
    }

    public function getCodigoErro(): ?int
    {
        return $this->codigoErro;
    }

    public function setCodigoErro(?int $codigoErro): self
    {
        $this->setVisited('codigoErro');

        $this->codigoErro = $codigoErro;

        return $this;
    }

    public function getMensagemErro(): ?string
    {
        return $this->mensagemErro;
    }

    public function setMensagemErro(?string $mensagemErro): self
    {
        $this->setVisited('mensagemErro');

        $this->mensagemErro = $mensagemErro;

        return $this;
    }
}
