<?php

declare(strict_types=1);
/**
 * /src/Entity/DocumentoIdentificador.php.
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
 * Class DocumentoIdentificador.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_doc_identificador')]
class DocumentoIdentificador implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'codigo_documento', type: 'string', nullable: false)]
    protected string $codigoDocumento;

    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(name: 'emissor_documento', type: 'string', nullable: false)]
    protected string $emissorDocumento;

    #[ORM\Column(name: 'data_emissao', type: 'date', nullable: true)]
    protected ?DateTime $dataEmissao = null;

    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[ORM\Column(type: 'string', nullable: true)]
    protected ?string $nome = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'ModalidadeDocumentoIdentificador')]
    #[ORM\JoinColumn(name: 'mod_doc_identificador_id', referencedColumnName: 'id', nullable: false)]
    protected ModalidadeDocumentoIdentificador $modalidadeDocumentoIdentificador;

    #[ORM\ManyToOne(targetEntity: 'OrigemDados', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'origem_dados_id', referencedColumnName: 'id', nullable: true)]
    protected ?OrigemDados $origemDados = null;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'Pessoa', inversedBy: 'documentosIdentificadores')]
    #[ORM\JoinColumn(name: 'pessoa_id', referencedColumnName: 'id', nullable: false)]
    protected Pessoa $pessoa;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getCodigoDocumento(): string
    {
        return $this->codigoDocumento;
    }

    public function setCodigoDocumento(string $codigoDocumento): self
    {
        $this->codigoDocumento = $codigoDocumento;

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

    public function getPessoa(): Pessoa
    {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa): self
    {
        $this->pessoa = $pessoa;

        return $this;
    }

    public function getEmissorDocumento(): string
    {
        return $this->emissorDocumento;
    }

    public function setEmissorDocumento(string $emissorDocumento): self
    {
        $this->emissorDocumento = $emissorDocumento;

        return $this;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getModalidadeDocumentoIdentificador(): ModalidadeDocumentoIdentificador
    {
        return $this->modalidadeDocumentoIdentificador;
    }

    public function setModalidadeDocumentoIdentificador(
        ModalidadeDocumentoIdentificador $modalidadeDocumentoIdentificador
    ): self {
        $this->modalidadeDocumentoIdentificador = $modalidadeDocumentoIdentificador;

        return $this;
    }

    public function getDataEmissao(): ?DateTime
    {
        return $this->dataEmissao;
    }

    public function setDataEmissao(?DateTime $dataEmissao): self
    {
        $this->dataEmissao = $dataEmissao;

        return $this;
    }
}
