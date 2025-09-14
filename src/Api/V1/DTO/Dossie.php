<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Dossie.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Documento as DocumentoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa as PessoaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada as SolicitacaoAutomatizadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDossie as TipoDossieDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\OrigemDados;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Entity\TipoDossie as TipoDossieEntity;
use SuppCore\AdministrativoBackend\Enums\DossieVisibilidade;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Dossie.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/dossie',
    jsonLDType: 'Dossie',
    jsonLDContext: '/api/doc/#model-Dossie'
)]
#[Form\Form]
class Dossie extends RestDto
{
    use IdUuid;
    use Blameable;
    use Softdeleteable;
    use Timeblameable;
    use OrigemDados;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[DTOMapper\Property]
    protected ?string $numeroDocumentoPrincipal = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Pessoa',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: PessoaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa')]
    protected ?EntityInterface $pessoa = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\SolicitacaoAutomatizada',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SolicitacaoAutomatizadaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\SolicitacaoAutomatizada')]
    protected ?EntityInterface $solicitacaoAutomatizada = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\TipoDossie',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: TipoDossieDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\TipoDossie')]
    protected ?EntityInterface $tipoDossie = null;

    #[OA\Property(type: 'string', format: 'date-time')]
    #[DTOMapper\Property]
    protected ?DateTime $dataConsulta = null;

    #[DTOMapper\Property]
    protected mixed $conteudo = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Documento',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: DocumentoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $documento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Processo',
            'required' => true,
        ]
    )]
    #[OA\Property(ref: new Model(type: ProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Processo')]
    protected ?EntityInterface $processo = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[Serializer\Exclude]
    protected ?bool $sobDemanda = false;

    #[DTOMapper\Property]
    protected ?string $protocoloRequerimento = null;

    #[DTOMapper\Property]
    protected ?string $statusRequerimento = null;

    #[DTOMapper\Property]
    protected ?string $fonteDados = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Choice(
        callback: [DossieVisibilidade::class, 'enumValues'],
        message: 'Valor deve ser de 0 até 2',
    )]
    #[DTOMapper\Property]
    protected ?int $visibilidade = null;

    #[DTOMapper\Property]
    protected ?int $versao = null;

    public function getConteudo(): mixed
    {
        return $this->conteudo;
    }

    public function setConteudo(mixed $conteudo): self
    {
        $this->setVisited('conteudo');
        $this->conteudo = $conteudo;

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

    public function getPessoa(): EntityInterface|PessoaDTO|PessoaEntity|null
    {
        return $this->pessoa;
    }

    public function setPessoa(?EntityInterface $pessoa): self
    {
        $this->setVisited('pessoa');

        $this->pessoa = $pessoa;

        return $this;
    }

    public function getTipoDossie(): EntityInterface|TipoDossieDTO|TipoDossieEntity|null
    {
        return $this->tipoDossie;
    }

    /**
     * @noinspection PhpUnused
     */
    public function setTipoDossie(TipoDossieDTO|TipoDossieEntity $tipoDossie): self
    {
        $this->setVisited('tipoDossie');

        $this->tipoDossie = $tipoDossie;

        return $this;
    }

    /**
     * @noinspection PhpUnused
     */
    public function getVisibilidade(): ?int
    {
        return $this->visibilidade;
    }

    public function setVisibilidade(?int $visibilidade): self
    {
        $this->setVisited('visibilidade');

        $this->visibilidade = $visibilidade;

        return $this;
    }

    /**
     * @noinspection PhpUnused
     */
    public function getVersao(): ?int
    {
        return $this->versao;
    }

    public function setVersao(?int $versao): self
    {
        $this->setVisited('versao');

        $this->versao = $versao;

        return $this;
    }

    public function getDocumento(): ?EntityInterface
    {
        return $this->documento;
    }

    public function setDocumento(?EntityInterface $documento): self
    {
        $this->setVisited('documento');

        $this->documento = $documento;

        return $this;
    }

    public function getProtocoloRequerimento(): ?string
    {
        return $this->protocoloRequerimento;
    }

    public function setProtocoloRequerimento(?string $protocoloRequerimento): self
    {
        $this->setVisited('protocoloRequerimento');

        $this->protocoloRequerimento = $protocoloRequerimento;

        return $this;
    }

    public function getStatusRequerimento(): ?string
    {
        return $this->statusRequerimento;
    }

    public function setStatusRequerimento(?string $statusRequerimento): self
    {
        $this->setVisited('statusRequerimento');

        $this->statusRequerimento = $statusRequerimento;

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

    public function getFonteDados(): ?string
    {
        return $this->fonteDados;
    }

    public function setFonteDados(?string $fonteDados): self
    {
        $this->setVisited('fonteDados');

        $this->fonteDados = $fonteDados;

        return $this;
    }

    public function getDataConsulta(): ?DateTime
    {
        return $this->dataConsulta;
    }

    public function setDataConsulta(?DateTime $dataConsulta): self
    {
        $this->setVisited('dataConsulta');

        $this->dataConsulta = $dataConsulta;

        return $this;
    }

    public function getSobDemanda(): ?bool
    {
        return $this->sobDemanda;
    }

    public function setSobDemanda(?bool $sobDemanda): self
    {
        $this->setVisited('sobDemanda');

        $this->sobDemanda = $sobDemanda;

        return $this;
    }

    /**
     * @return EntityInterface|null
     */
    public function getSolicitacaoAutomatizada(): ?EntityInterface
    {
        return $this->solicitacaoAutomatizada;
    }

    /**
     * @param EntityInterface|null $solicitacaoAutomatizada
     *
     * @return $this
     */
    public function setSolicitacaoAutomatizada(?EntityInterface $solicitacaoAutomatizada): self
    {
        $this->setVisited('solicitacaoAutomatizada');
        $this->solicitacaoAutomatizada = $solicitacaoAutomatizada;

        return $this;
    }
}
