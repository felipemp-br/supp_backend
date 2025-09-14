<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/SolicitacaoAutomatizada.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Setor as SetorDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Ativo;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Enums\StatusExibicaoSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Enums\StatusSolicitacaoAutomatizada;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SolicitacaoAutomatizada.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/solicitacao_automatizada/{id}',
    jsonLDType: 'SolicitacaoAutomatizada',
    jsonLDContext: '/api/doc/#model-SolicitacaoAutomatizada'
)]
#[Form\Form]
class SolicitacaoAutomatizada extends RestDto
{
    use IdUuid;
    use Ativo;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

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
            'class' => 'SuppCore\AdministrativoBackend\Entity\Tarefa',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: Tarefa::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa')]
    protected ?EntityInterface $tarefaAnalise = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Tarefa',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: Tarefa::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa')]
    protected ?EntityInterface $tarefaDadosCumprimento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Tarefa',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: Tarefa::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa')]
    protected ?EntityInterface $tarefaAcompanhamentoCumprimento = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\DadosFormulario',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: DadosFormulario::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\DadosFormulario')]
    protected ?EntityInterface $dadosFormulario = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Pessoa',
            'required' => true,
        ]
    )]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa')]
    protected ?EntityInterface $beneficiario = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\TipoSolicitacaoAutomatizada',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: TipoSolicitacaoAutomatizada::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\TipoSolicitacaoAutomatizada')]
    protected ?EntityInterface $tipoSolicitacaoAutomatizada = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\EnumType',
        options: [
            'required' => false,
            'class' => StatusSolicitacaoAutomatizada::class,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    #[Serializer\Exclude()]
    /**
     * Propriedade excluida da serialização, utilize a virtual property self::getStatus.
     */
    protected ?StatusSolicitacaoAutomatizada $status = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\EnumType',
        options: [
            'required' => false,
            'class' => StatusExibicaoSolicitacaoAutomatizada::class,
        ]
    )]
    #[DTOMapper\Property]
    #[Serializer\Exclude()]
    /**
     * Propriedade excluida da serialização, utilize a virtual property self::getStatus.
     */
    protected ?StatusExibicaoSolicitacaoAutomatizada $statusExibicao = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: UsuarioDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuarioResponsavel = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Setor',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: SetorDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Setor')]
    protected ?EntityInterface $setorResponsavel = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $observacao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: false)]
    #[DTOMapper\Property]
    protected bool $urgente = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[DTOMapper\Property]
    protected bool $sugestaoDeferimento = true;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[DTOMapper\Property]
    protected bool $erroAnaliseSumaria = false;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\CheckboxType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'boolean', default: true)]
    #[DTOMapper\Property]
    protected bool $protocoloExterno = true;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $dossiesNecessarios = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $analisesDossies = null;

    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $dadosTipoSolicitacao = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $dadosCumprimento = null;

    #[OA\Property(ref: new Model(type: Documento::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Documento')]
    protected ?EntityInterface $resultadoSolicitacao = null;

    #[Serializer\SkipWhenEmpty]
    #[DTOMapper\Property(
        dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie',
        dtoGetter: 'getDossies',
        dtoSetter: 'addDossies',
        collection: true
    )]
    protected $dossies = [];

    /**
     * @return ?EntityInterface
     */
    public function getProcesso(): ?EntityInterface
    {
        return $this->processo;
    }

    /**
     * @param ?EntityInterface $processo
     *
     * @return self
     */
    public function setProcesso(?EntityInterface $processo): self
    {
        $this->setVisited('processo');
        $this->processo = $processo;

        return $this;
    }

    /**
     * @return ?EntityInterface
     */
    public function getDadosFormulario(): ?EntityInterface
    {
        return $this->dadosFormulario;
    }

    /**
     * @param ?EntityInterface $dadosFormulario
     *
     * @return self
     */
    public function setDadosFormulario(?EntityInterface $dadosFormulario): self
    {
        $this->setVisited('dadosFormulario');
        $this->dadosFormulario = $dadosFormulario;

        return $this;
    }

    /**
     * @return ?EntityInterface
     */
    public function getTipoSolicitacaoAutomatizada(): ?EntityInterface
    {
        return $this->tipoSolicitacaoAutomatizada;
    }

    /**
     * @param ?EntityInterface $tipoSolicitacaoAutomatizada
     *
     * @return self
     */
    public function setTipoSolicitacaoAutomatizada(?EntityInterface $tipoSolicitacaoAutomatizada): self
    {
        $this->setVisited('tipoSolicitacaoAutomatizada');
        $this->tipoSolicitacaoAutomatizada = $tipoSolicitacaoAutomatizada;

        return $this;
    }

    /**
     * @return ?StatusSolicitacaoAutomatizada
     */
    public function getStatus(): ?StatusSolicitacaoAutomatizada
    {
        return $this->status;
    }

    /**
     * @param StatusSolicitacaoAutomatizada|null $status
     *
     * @return self
     */
    public function setStatus(
        ?StatusSolicitacaoAutomatizada $status
    ): self {
        $this->setVisited('status');
        $this->setVisited('statusExibicao');
        $this->status = $status;
        $this->statusExibicao = StatusExibicaoSolicitacaoAutomatizada::fromStatusSolicitacao(
            $status
        );

        return $this;
    }

    /**
     * @return ?EntityInterface
     */
    public function getUsuarioResponsavel(): ?EntityInterface
    {
        return $this->usuarioResponsavel;
    }

    /**
     * @param ?EntityInterface $usuarioResponsavel
     *
     * @return self
     */
    public function setUsuarioResponsavel(?EntityInterface $usuarioResponsavel): self
    {
        $this->setVisited('usuarioResponsavel');
        $this->usuarioResponsavel = $usuarioResponsavel;

        return $this;
    }

    /**
     * @return ?EntityInterface
     */
    public function getSetorResponsavel(): ?EntityInterface
    {
        return $this->setorResponsavel;
    }

    /**
     * @param ?EntityInterface $setorResponsavel
     *
     * @return self
     */
    public function setSetorResponsavel(?EntityInterface $setorResponsavel): self
    {
        $this->setVisited('setorResponsavel');
        $this->setorResponsavel = $setorResponsavel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    /**
     * @param string|null $observacao
     *
     * @return self
     */
    public function setObservacao(?string $observacao): self
    {
        $this->setVisited('observacao');
        $this->observacao = $observacao;

        return $this;
    }

    /**
     * @return bool
     */
    public function getUrgente(): bool
    {
        return $this->urgente;
    }

    /**
     * @param bool $urgente
     *
     * @return self
     */
    public function setUrgente(bool $urgente): self
    {
        $this->setVisited('urgente');
        $this->urgente = $urgente;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDossiesNecessarios(): ?string
    {
        return $this->dossiesNecessarios;
    }

    /**
     * @param string|null $dossiesNecessarios
     *
     * @return self
     */
    public function setDossiesNecessarios(?string $dossiesNecessarios): self
    {
        $this->setVisited('dossiesNecessarios');
        $this->dossiesNecessarios = $dossiesNecessarios;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAnalisesDossies(): ?string
    {
        return $this->analisesDossies;
    }

    /**
     * @param string|null $analisesDossies
     *
     * @return self
     */
    public function setAnalisesDossies(?string $analisesDossies): self
    {
        $this->setVisited('analisesDossies');
        $this->analisesDossies = $analisesDossies;

        return $this;
    }

    /**
     * Metodo necessário para o jms serializer serializar o valor do enum e não o objeto.
     *
     * @return string
     */
    #[Serializer\VirtualProperty()]
    #[Serializer\SerializedName('status')]
    public function getStatusValue(): string
    {
        return $this->status->value;
    }

    /**
     * Metodo necessário para o jms serializer serializar o valor do enum e não o objeto.
     *
     * @return string
     */
    #[Serializer\VirtualProperty()]
    #[Serializer\SerializedName('statusExibicao')]
    public function getStatusExibicaoValue(): string
    {
        return $this->statusExibicao->value;
    }

    /**
     * @return string|null
     */
    public function getDadosTipoSolicitacao(): ?string
    {
        return $this->dadosTipoSolicitacao;
    }

    /**
     * @param string|null $dadosTipoSolicitacao
     *
     * @return $this
     */
    public function setDadosTipoSolicitacao(?string $dadosTipoSolicitacao): self
    {
        $this->setVisited('dadosTipoSolicitacao');
        $this->dadosTipoSolicitacao = $dadosTipoSolicitacao;

        return $this;
    }

    /**
     * @return bool
     */
    public function getProtocoloExterno(): bool
    {
        return $this->protocoloExterno;
    }

    /**
     * @param bool $protocoloExterno
     * @return $this
     */
    public function setProtocoloExterno(bool $protocoloExterno): self
    {
        $this->protocoloExterno = $protocoloExterno;

        return $this;
    }

    /**
     * @return EntityInterface|null
     */
    public function getResultadoSolicitacao(): ?EntityInterface
    {
        return $this->resultadoSolicitacao;
    }

    /**
     * @param EntityInterface|null $resultadoSolicitacao
     * @return $this
     */
    public function setResultadoSolicitacao(?EntityInterface $resultadoSolicitacao): self
    {
        $this->resultadoSolicitacao = $resultadoSolicitacao;

        return $this;
    }

    /**
     * @return array
     */
    public function getDossies(): array
    {
        return $this->dossies;
    }

    /**
     * @param Dossie $dossie
     * @return $this
     */
    public function addDossies(Dossie $dossie): self
    {
        $this->dossies[] = $dossie;

        return $this;
    }

    /**
     * @return EntityInterface|null
     */
    public function getBeneficiario(): ?EntityInterface
    {
        return $this->beneficiario;
    }

    /**
     * @param EntityInterface|null $beneficiario
     *
     * @return $this
     */
    public function setBeneficiario(?EntityInterface $beneficiario): self
    {
        $this->setVisited('beneficiario');
        $this->beneficiario = $beneficiario;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSugestaoDeferimento(): bool
    {
        return $this->sugestaoDeferimento;
    }

    /**
     * @param bool $sugestaoDeferimento
     *
     * @return $this
     */
    public function setSugestaoDeferimento(bool $sugestaoDeferimento): self
    {
        $this->setVisited('sugestaoDeferimento');
        $this->sugestaoDeferimento = $sugestaoDeferimento;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDadosCumprimento(): ?string
    {
        return $this->dadosCumprimento;
    }

    /**
     * @param string|null $dadosCumprimento
     *
     * @return $this
     */
    public function setDadosCumprimento(?string $dadosCumprimento): self
    {
        $this->setVisited('dadosCumprimento');
        $this->dadosCumprimento = $dadosCumprimento;

        return $this;
    }

    /**
     * @return EntityInterface|null
     */
    public function getTarefaAnalise(): ?EntityInterface
    {
        return $this->tarefaAnalise;
    }

    /**
     * @param EntityInterface|null $tarefaAnalise
     * @return $this
     */
    public function setTarefaAnalise(?EntityInterface $tarefaAnalise): self
    {
        $this->setVisited('tarefaAnalise');
        $this->tarefaAnalise = $tarefaAnalise;

        return $this;
    }

    /**
     * @return EntityInterface|null
     */
    public function getTarefaDadosCumprimento(): ?EntityInterface
    {
        return $this->tarefaDadosCumprimento;
    }

    /**
     * @param EntityInterface|null $tarefaDadosCumprimento
     *
     * @return $this
     */
    public function setTarefaDadosCumprimento(?EntityInterface $tarefaDadosCumprimento): self
    {
        $this->setVisited('tarefaDadosCumprimento');
        $this->tarefaDadosCumprimento = $tarefaDadosCumprimento;

        return $this;
    }

    public function getErroAnaliseSumaria(): bool
    {
        return $this->erroAnaliseSumaria;
    }

    public function setErroAnaliseSumaria(bool $erroAnaliseSumaria): self
    {
        $this->setVisited('erroAnaliseSumaria');
        $this->erroAnaliseSumaria = $erroAnaliseSumaria;

        return $this;
    }

    /**
     * Return statusExibicaoSolicitacaoAutomatizada.
     *
     * @return StatusExibicaoSolicitacaoAutomatizada|null
     */
    public function getStatusExibicao(): ?StatusExibicaoSolicitacaoAutomatizada
    {
        return $this->statusExibicao;
    }

    /**
     * Return tarefaAcompanhamentoCumprimento.
     *
     * @return EntityInterface|null
     */
    public function getTarefaAcompanhamentoCumprimento(): ?EntityInterface
    {
        return $this->tarefaAcompanhamentoCumprimento;
    }

    /**
     * Set tarefaAcompanhamentoCumprimento.
     *
     * @param EntityInterface|null $tarefaAcompanhamentoCumprimento
     *
     * @return SolicitacaoAutomatizada
     */
    public function setTarefaAcompanhamentoCumprimento(
        ?EntityInterface $tarefaAcompanhamentoCumprimento
    ): SolicitacaoAutomatizada {
        $this->setVisited('tarefaAcompanhamentoCumprimento');
        $this->tarefaAcompanhamentoCumprimento = $tarefaAcompanhamentoCumprimento;

        return $this;
    }
}
