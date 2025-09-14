<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoEtiqueta.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Interfaces\VinculacaoEtiquetaInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VinculacaoEtiqueta.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_vinc_etiqueta')]
class VinculacaoEtiqueta implements EntityInterface, VinculacaoEtiquetaInterface
{
    // Traits
    use Traits\VinculacaoEtiqueta;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Etiqueta', inversedBy: 'vinculacoesEtiquetas')]
    #[ORM\JoinColumn(name: 'etiqueta_id', referencedColumnName: 'id', nullable: false)]
    protected ?Etiqueta $etiqueta = null;

    #[ORM\ManyToOne(targetEntity: 'Tarefa', inversedBy: 'vinculacoesEtiquetas')]
    #[ORM\JoinColumn(name: 'tarefa_id', referencedColumnName: 'id', nullable: true)]
    protected ?Tarefa $tarefa = null;

    #[ORM\ManyToOne(targetEntity: 'Documento', inversedBy: 'vinculacoesEtiquetas')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: true)]
    protected ?Documento $documento = null;

    #[ORM\ManyToOne(targetEntity: 'Processo', inversedBy: 'vinculacoesEtiquetas')]
    #[ORM\JoinColumn(name: 'processo_id', referencedColumnName: 'id', nullable: true)]
    protected ?Processo $processo = null;

    #[ORM\ManyToOne(targetEntity: 'Relatorio', inversedBy: 'vinculacoesEtiquetas')]
    #[ORM\JoinColumn(name: 'relatorio_id', referencedColumnName: 'id', nullable: true)]
    protected ?Relatorio $relatorio = null;

    #[ORM\ManyToOne(targetEntity: 'DocumentoAvulso', inversedBy: 'vinculacoesEtiquetas')]
    #[ORM\JoinColumn(name: 'documento_avulso_id', referencedColumnName: 'id', nullable: true)]
    protected ?DocumentoAvulso $documentoAvulso = null;

    public function getTarefa(): ?Tarefa
    {
        return $this->tarefa;
    }

    public function setTarefa(?Tarefa $tarefa): self
    {
        $this->tarefa = $tarefa;

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

    public function getProcesso(): ?Processo
    {
        return $this->processo;
    }

    public function setProcesso(?Processo $processo): self
    {
        $this->processo = $processo;

        return $this;
    }

    public function getRelatorio(): ?Relatorio
    {
        return $this->relatorio;
    }

    public function setRelatorio(?Relatorio $relatorio): self
    {
        $this->relatorio = $relatorio;

        return $this;
    }

    public function getDocumentoAvulso(): ?DocumentoAvulso
    {
        return $this->documentoAvulso;
    }

    public function setDocumentoAvulso(?DocumentoAvulso $documentoAvulso): self
    {
        $this->documentoAvulso = $documentoAvulso;

        return $this;
    }
}
