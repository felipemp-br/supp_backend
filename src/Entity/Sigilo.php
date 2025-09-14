<?php

declare(strict_types=1);
/**
 * /src/Entity/Sigilo.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
use DMS\Filter\Rules as Filter;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Sigilo.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_sigilo')]
class Sigilo implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Gedmo\Versioned]
    #[ORM\Column(type: 'boolean', nullable: false)]
    protected bool $desclassificado = false;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $observacao = null;

    /**
     * 1. A 1ª parte do CIDIC deve prever número deposições que atendam ao
     * Número Único de Protocolo–NUP, que é um código exclusivamente numérico;
     * 2. A 2ª parte do CIDIC,separada da 1ª parte por um “.”, iniciará sempre
     * por um caracter alfabético (“U”, “S” ou “R”) e deverá prever até o
     * máximo de 39 posições, com caracteres alfanuméricos e separadores;
     * 3. Os separadores utilizados serão: “.” e “/” para as datas;
     * 4. Para as informações classificadas no grau reservado e secreto,
     * a 2ª parte do CIDIC terá sempre 28 posições com caracteres alfanuméricos
     * e separadores;
     * 5. Para as informações classificadas no grau ultrassecreto, a 2ª parte do
     * CIDIC terá 28 posições com caracteres alfanuméricos e separadores,
     * enquanto não ocorrer prorrogação do prazo do sigilo;
     * 6. Quando ocorrer a prorrogação do prazo de sigilo da informação
     * classificada no grau ultrasecreto, a nova data deverá constar no final da
     * 2ª parte do CIDIC, totalizando as 39 posições com caracteres
     * alfanuméricos e separadores;.
     */
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'codigo_indexacao', type: 'string', nullable: true)]
    protected ?string $codigoIndexacao = null;

    #[Assert\NotBlank(message: 'Campo não pode estar em branco.')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Gedmo\Versioned]
    #[ORM\Column(name: 'fundamento_legal', type: 'string', nullable: false)]
    protected string $fundamentoLegal;

    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'razoes_classif_sigilo', type: 'string', nullable: true)]
    protected ?string $razoesClassificacaoSigilo = null;

    #[Assert\NotNull(message: 'A data/hora de validade do sigilo não pode ser nula!')]
    #[ORM\Column(name: 'data_hora_validade_sigilo', type: 'datetime', nullable: false)]
    protected DateTime $dataHoraValidadeSigilo;

    #[Assert\NotNull(message: 'A data/hora de início do sigilo não pode ser nula!')]
    #[ORM\Column(name: 'data_hora_inicio_sigilo', type: 'datetime', nullable: false)]
    protected DateTime $dataHoraInicioSigilo;

    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[Assert\Range(notInRangeMessage: 'Campo ser entre {{ min }} e {{ max }}', min: 0, max: 4)]
    #[ORM\Column(name: 'nivel_acesso', type: 'integer', nullable: false)]
    protected int $nivelAcesso;

    #[ORM\ManyToOne(targetEntity: 'ModalidadeCategoriaSigilo')]
    #[ORM\JoinColumn(name: 'mod_categoria_sigilo_id', referencedColumnName: 'id', nullable: true)]
    protected ?ModalidadeCategoriaSigilo $modalidadeCategoriaSigilo = null;

    #[Assert\NotNull(message: 'Campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'TipoSigilo')]
    #[ORM\JoinColumn(name: 'tipo_sigilo_id', referencedColumnName: 'id', nullable: false)]
    protected TipoSigilo $tipoSigilo;

    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'sigilos')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: true)]
    protected ?Processo $processo = null;

    #[ORM\ManyToOne(targetEntity: 'Documento', inversedBy: 'sigilos')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: true)]
    protected ?Documento $documento = null;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getDesclassificado(): bool
    {
        return $this->desclassificado;
    }

    public function setDesclassificado(bool $desclassificado): self
    {
        $this->desclassificado = $desclassificado;

        return $this;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): self
    {
        $this->observacao = $observacao;

        return $this;
    }

    public function getCodigoIndexacao(): ?string
    {
        return $this->codigoIndexacao;
    }

    public function setCodigoIndexacao(?string $codigoIndexacao): self
    {
        $this->codigoIndexacao = $codigoIndexacao;

        return $this;
    }

    public function getFundamentoLegal(): string
    {
        return $this->fundamentoLegal;
    }

    public function setFundamentoLegal(string $fundamentoLegal): self
    {
        $this->fundamentoLegal = $fundamentoLegal;

        return $this;
    }

    public function getRazoesClassificacaoSigilo(): ?string
    {
        return $this->razoesClassificacaoSigilo;
    }

    public function setRazoesClassificacaoSigilo(?string $razoesClassificacaoSigilo): self
    {
        $this->razoesClassificacaoSigilo = $razoesClassificacaoSigilo;

        return $this;
    }

    public function getDataHoraValidadeSigilo(): DateTime
    {
        return $this->dataHoraValidadeSigilo;
    }

    public function setDataHoraValidadeSigilo(DateTime $dataHoraValidadeSigilo): self
    {
        $this->dataHoraValidadeSigilo = $dataHoraValidadeSigilo;

        return $this;
    }

    public function getDataHoraInicioSigilo(): DateTime
    {
        return $this->dataHoraInicioSigilo;
    }

    public function setDataHoraInicioSigilo(DateTime $dataHoraInicioSigilo): self
    {
        $this->dataHoraInicioSigilo = $dataHoraInicioSigilo;

        return $this;
    }

    public function getNivelAcesso(): int
    {
        return $this->nivelAcesso;
    }

    public function setNivelAcesso(int $nivelAcesso): self
    {
        $this->nivelAcesso = $nivelAcesso;

        return $this;
    }

    public function getModalidadeCategoriaSigilo(): ?ModalidadeCategoriaSigilo
    {
        return $this->modalidadeCategoriaSigilo;
    }

    public function setModalidadeCategoriaSigilo(?ModalidadeCategoriaSigilo $modalidadeCategoriaSigilo): self
    {
        $this->modalidadeCategoriaSigilo = $modalidadeCategoriaSigilo;

        return $this;
    }

    public function getTipoSigilo(): TipoSigilo
    {
        return $this->tipoSigilo;
    }

    public function setTipoSigilo(TipoSigilo $tipoSigilo): self
    {
        $this->tipoSigilo = $tipoSigilo;

        return $this;
    }

    public function getProcesso(): ?Processo
    {
        return $this->processo;
    }

    public function setProcesso(?Processo $processo): self
    {
        $this->processo = $processo;

        return $this;
    }

    public function getDocumento(): ?Documento
    {
        return $this->documento;
    }

    public function setDocumento(?Documento $documento): self
    {
        $this->documento = $documento;

        return $this;
    }

    public function getOrigemDados(): ?OrigemDados
    {
        return $this->origemDados;
    }

    public function setOrigemDados(?OrigemDados $origemDados): self
    {
        $this->origemDados = $origemDados;

        return $this;
    }
}
