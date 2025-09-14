<?php

declare(strict_types=1);

/**
 * /src/Entity/VinculacaoEtiqueta.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity\Traits;

use DateTime;
use DMS\Filter\Rules as Filter;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use SuppCore\AdministrativoBackend\Entity\Etiqueta;
use SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral;
use SuppCore\AdministrativoBackend\Entity\RegraEtiqueta;
use SuppCore\AdministrativoBackend\Entity\Setor;
use SuppCore\AdministrativoBackend\Entity\Usuario;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait VinculacaoEtiqueta.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
trait VinculacaoEtiqueta
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Etiqueta', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'etiqueta_id', referencedColumnName: 'id', nullable: false)]
    protected ?Etiqueta $etiqueta = null;

    #[Assert\Length(max: 4000, maxMessage: 'O campo deve ter no máximo {{ limit }} caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $conteudo = null;

    #[ORM\Column(name: 'data_expiracao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraExpiracao = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    protected bool $privada = false;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Usuario', inversedBy: 'vinculacoesEtiquetas')]
    #[ORM\JoinColumn(name: 'usuario_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuario = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Setor', inversedBy: 'vinculacoesEtiquetas')]
    #[ORM\JoinColumn(name: 'setor_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $setor = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Setor')]
    #[ORM\JoinColumn(name: 'unidade_id', referencedColumnName: 'id', nullable: true)]
    protected ?Setor $unidade = null;

    #[ORM\ManyToOne(
        targetEntity: 'SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral',
        inversedBy: 'vinculacoesEtiquetas'
    )]
    #[ORM\JoinColumn(name: 'mod_orgao_central_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeOrgaoCentral $modalidadeOrgaoCentral = null;

    #[ORM\Column(name: 'object_class', type: 'text', nullable: true)]
    protected ?string $objectClass = null;

    #[ORM\Column(name: 'object_uuid', type: 'text', nullable: true)]
    protected ?string $objectUuid = null;

    #[ORM\Column(name: 'extension_object_class', type: 'text', nullable: true)]
    protected ?string $extensionObjectClass = null;

    #[ORM\Column(name: 'extension_object_uuid', type: 'text', nullable: true)]
    protected ?string $extensionObjectUuid = null;

    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $label = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\RegraEtiqueta')]
    #[ORM\JoinColumn(name: 'regra_etiqueta_origem_id', referencedColumnName: 'id', nullable: true)]
    protected ?RegraEtiqueta $regraEtiquetaOrigem = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    protected ?bool $sugestao = null;

    #[ORM\Column(name: 'data_hora_aprov_sugestao', type: 'datetime', nullable: true)]
    protected ?DateTime $dataHoraAprovacaoSugestao = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Usuario')]
    #[ORM\JoinColumn(name: 'usuario_aprov_sugestao_id', referencedColumnName: 'id', nullable: true)]
    protected ?Usuario $usuarioAprovacaoSugestao = null;

    #[ORM\Column(name: 'acoes_execucao_sugestao', type: 'text', nullable: true)]
    protected ?string $acoesExecucaoSugestao = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getEtiqueta(): ?Etiqueta
    {
        return $this->etiqueta;
    }

    public function setEtiqueta(?Etiqueta $etiqueta): self
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getSetor(): ?Setor
    {
        return $this->setor;
    }

    public function setSetor(?Setor $setor): self
    {
        $this->setor = $setor;

        return $this;
    }

    public function getUnidade(): ?Setor
    {
        return $this->unidade;
    }

    public function setUnidade(?Setor $unidade): self
    {
        $this->unidade = $unidade;

        return $this;
    }

    public function getModalidadeOrgaoCentral(): ?ModalidadeOrgaoCentral
    {
        return $this->modalidadeOrgaoCentral;
    }

    public function setModalidadeOrgaoCentral(?ModalidadeOrgaoCentral $modalidadeOrgaoCentral): self
    {
        $this->modalidadeOrgaoCentral = $modalidadeOrgaoCentral;

        return $this;
    }

    public function getConteudo(): ?string
    {
        return $this->conteudo;
    }

    public function setConteudo(?string $conteudo): self
    {
        $this->conteudo = $conteudo;

        return $this;
    }

    public function getPrivada(): bool
    {
        return $this->privada;
    }

    public function setPrivada(bool $privada): self
    {
        $this->privada = $privada;

        return $this;
    }

    public function getDataHoraExpiracao(): ?DateTime
    {
        return $this->dataHoraExpiracao;
    }

    public function setDataHoraExpiracao(?DateTime $dataHoraExpiracao): self
    {
        $this->dataHoraExpiracao = $dataHoraExpiracao;

        return $this;
    }

    public function getObjectClass(): ?string
    {
        return $this->objectClass;
    }

    public function setObjectClass(?string $objectClass): self
    {
        $this->objectClass = $objectClass;

        return $this;
    }

    public function getObjectUuid(): ?string
    {
        return $this->objectUuid;
    }

    public function setObjectUuid(?string $objectUuid): self
    {
        $this->objectUuid = $objectUuid;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getRegraEtiquetaOrigem(): ?RegraEtiqueta
    {
        return $this->regraEtiquetaOrigem;
    }

    public function setRegraEtiquetaOrigem(?RegraEtiqueta $regraEtiquetaOrigem): self
    {
        $this->regraEtiquetaOrigem = $regraEtiquetaOrigem;

        return $this;
    }

    public function getSugestao(): ?bool
    {
        return $this->sugestao;
    }

    public function setSugestao(?bool $sugestao): self
    {
        $this->sugestao = $sugestao;

        return $this;
    }

    public function getDataHoraAprovacaoSugestao(): ?DateTime
    {
        return $this->dataHoraAprovacaoSugestao;
    }

    public function setDataHoraAprovacaoSugestao(?DateTime $dataHoraAprovacaoSugestao): self
    {
        $this->dataHoraAprovacaoSugestao = $dataHoraAprovacaoSugestao;

        return $this;
    }

    public function getUsuarioAprovacaoSugestao(): ?Usuario
    {
        return $this->usuarioAprovacaoSugestao;
    }

    public function setUsuarioAprovacaoSugestao(?Usuario $usuarioAprovacaoSugestao): self
    {
        $this->usuarioAprovacaoSugestao = $usuarioAprovacaoSugestao;

        return $this;
    }

    public function getAcoesExecucaoSugestao(): ?string
    {
        return $this->acoesExecucaoSugestao;
    }

    public function setAcoesExecucaoSugestao(?string $acoesExecucaoSugestao): self
    {
        $this->acoesExecucaoSugestao = $acoesExecucaoSugestao;

        return $this;
    }

    public function getExtensionObjectClass(): ?string
    {
        return $this->extensionObjectClass;
    }

    public function setExtensionObjectClass(?string $extensionObjectClass): self
    {
        $this->extensionObjectClass = $extensionObjectClass;

        return $this;
    }

    public function getExtensionObjectUuid(): ?string
    {
        return $this->extensionObjectUuid;
    }

    public function setExtensionObjectUuid(?string $extensionObjectUuid): self
    {
        $this->extensionObjectUuid = $extensionObjectUuid;

        return $this;
    }
}
