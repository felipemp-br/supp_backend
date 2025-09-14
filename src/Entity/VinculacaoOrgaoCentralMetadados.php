<?php

declare(strict_types=1);
/**
 * /src/Entity/VinculacaoOrgaoCentralMetadados.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;

/**
 * Class VinculacaoOrgaoCentralMetadados.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[ORM\Table(name: 'ad_vinc_org_cent_metadados')]
#[ORM\UniqueConstraint(
    columns: [
        'tese_id',
        'mod_orgao_central_id',
        'documento_id',
        'modelo_id',
        'repositorio_id',
        'assunto_administrativo_id',
        'especie_setor_id',
        'especie_processo_id',
        'especie_documento_id',
        'apagado_em',
    ]
)]
class VinculacaoOrgaoCentralMetadados implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[ORM\ManyToOne(
        targetEntity: 'SuppCore\AdministrativoBackend\Entity\Tese',
        inversedBy: 'vinculacoesOrgaoCentralMetadados'
    )]
    #[ORM\JoinColumn(name: 'tese_id', referencedColumnName: 'id', nullable: false)]
    protected Tese $tese;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\ModalidadeOrgaoCentral')]
    #[ORM\JoinColumn(name: 'mod_orgao_central_id', referencedColumnName: 'id', nullable: false)]
    protected ?ModalidadeOrgaoCentral $modalidadeOrgaoCentral = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Documento')]
    #[ORM\JoinColumn(name: 'documento_id', referencedColumnName: 'id', nullable: true)]
    protected ?Documento $documento = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Modelo')]
    #[ORM\JoinColumn(name: 'modelo_id', referencedColumnName: 'id', nullable: true)]
    protected ?Modelo $modelo = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\Repositorio')]
    #[ORM\JoinColumn(name: 'repositorio_id', referencedColumnName: 'id', nullable: true)]
    protected ?Repositorio $repositorio = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\AssuntoAdministrativo')]
    #[ORM\JoinColumn(name: 'assunto_administrativo_id', referencedColumnName: 'id', nullable: true)]
    protected ?AssuntoAdministrativo $assuntoAdministrativo = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\EspecieSetor')]
    #[ORM\JoinColumn(name: 'especie_setor_id', referencedColumnName: 'id', nullable: true)]
    protected ?EspecieSetor $especieSetor = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\EspecieProcesso')]
    #[ORM\JoinColumn(name: 'especie_processo_id', referencedColumnName: 'id', nullable: true)]
    protected ?EspecieProcesso $especieProcesso = null;

    #[ORM\ManyToOne(targetEntity: 'SuppCore\AdministrativoBackend\Entity\EspecieDocumento')]
    #[ORM\JoinColumn(name: 'especie_documento_id', referencedColumnName: 'id', nullable: true)]
    protected ?EspecieDocumento $especieDocumento = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getTese(): Tese
    {
        return $this->tese;
    }

    public function setTese(Tese $tese): self
    {
        $this->tese = $tese;

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

    public function getDocumento(): ?Documento
    {
        return $this->documento;
    }

    public function setDocumento(?Documento $documento): self
    {
        $this->documento = $documento;

        return $this;
    }

    public function getModelo(): ?Modelo
    {
        return $this->modelo;
    }

    public function setModelo(?Modelo $modelo): self
    {
        $this->modelo = $modelo;

        return $this;
    }

    public function getRepositorio(): ?Repositorio
    {
        return $this->repositorio;
    }

    public function setRepositorio(?Repositorio $repositorio): self
    {
        $this->repositorio = $repositorio;

        return $this;
    }

    public function getAssuntoAdministrativo(): ?AssuntoAdministrativo
    {
        return $this->assuntoAdministrativo;
    }

    public function setAssuntoAdministrativo(?AssuntoAdministrativo $assuntoAdministrativo): self
    {
        $this->assuntoAdministrativo = $assuntoAdministrativo;

        return $this;
    }

    public function getEspecieSetor(): ?EspecieSetor
    {
        return $this->especieSetor;
    }

    public function setEspecieSetor(?EspecieSetor $especieSetor): self
    {
        $this->especieSetor = $especieSetor;

        return $this;
    }

    public function getEspecieProcesso(): ?EspecieProcesso
    {
        return $this->especieProcesso;
    }

    public function setEspecieProcesso(?EspecieProcesso $especieProcesso): self
    {
        $this->especieProcesso = $especieProcesso;

        return $this;
    }

    public function getEspecieDocumento(): ?EspecieDocumento
    {
        return $this->especieDocumento;
    }

    public function setEspecieDocumento(?EspecieDocumento $especieDocumento): self
    {
        $this->especieDocumento = $especieDocumento;

        return $this;
    }
}
