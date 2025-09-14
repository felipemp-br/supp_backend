<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use SuppCore\AdministrativoBackend\Entity\Traits\Blameable;
use SuppCore\AdministrativoBackend\Entity\Traits\Id;
use SuppCore\AdministrativoBackend\Entity\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 *
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[UniqueEntity(fields: ['coordenador'], message: 'Coordenação já vinculada ao menu')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_vinc_menu_coordenador')]
class VinculacaoMenuCoordenador implements EntityInterface
{
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[ORM\OneToOne(targetEntity: 'Coordenador')]
    #[ORM\JoinColumn(name: 'coordenador_id', referencedColumnName: 'id', nullable: true)]
    protected ?Coordenador $coordenador = null;

    #[ORM\Column(name: 'modelos', type: 'boolean', nullable: false)]
    protected bool $modelos = true;

    #[ORM\Column(name: 'repositorios', type: 'boolean', nullable: false)]
    protected bool $repositorios = true;

    #[ORM\Column(name: 'etiquetas', type: 'boolean', nullable: false)]
    protected bool $etiquetas = true;

    #[ORM\Column(name: 'usuarios', type: 'boolean', nullable: false)]
    protected bool $usuarios = true;

    #[ORM\Column(name: 'unidades', type: 'boolean', nullable: false)]
    protected bool $unidades = true;

    #[ORM\Column(name: 'avisos', type: 'boolean', nullable: false)]
    protected bool $avisos = true;

    #[ORM\Column(name: 'teses', type: 'boolean', nullable: false)]
    protected bool $teses = true;

    #[ORM\Column(name: 'coordenadores', type: 'boolean', nullable: false)]
    protected bool $coordenadores = true;

    #[ORM\Column(name: 'setores', type: 'boolean', nullable: false)]
    protected bool $setores = true;

    #[ORM\Column(name: 'contas_emails', type: 'boolean', nullable: false)]
    protected bool $contasEmails = true;

    #[ORM\Column(name: 'competencias', type: 'boolean', nullable: false)]
    protected bool $competencias = true;
    #[ORM\Column(name: 'dominios', type: 'boolean', nullable: false)]
    protected bool $dominios = true;

    #[ORM\Column(name: 'gerenciamento_tarefas', type: 'boolean', nullable: false)]
    protected bool $gerenciamentoTarefas = true;

    public function __construct()
    {
        $this->setUuid();
    }

    public function getCoordenador(): ?Coordenador
    {
        return $this->coordenador;
    }

    public function setCoordenador(?Coordenador $coordenador): self
    {
        $this->coordenador = $coordenador;

        return $this;
    }

    public function getModelos(): bool
    {
        return $this->modelos;
    }

    public function setModelos(bool $modelos): self
    {
        $this->modelos = $modelos;

        return $this;
    }

    public function getRepositorios(): bool
    {
        return $this->repositorios;
    }

    public function setRepositorios(bool $repositorios): self
    {
        $this->repositorios = $repositorios;

        return $this;
    }

    public function getEtiquetas(): bool
    {
        return $this->etiquetas;
    }

    public function setEtiquetas(bool $etiquetas): self
    {
        $this->etiquetas = $etiquetas;

        return $this;
    }

    public function getUsuarios(): bool
    {
        return $this->usuarios;
    }

    public function setUsuarios(bool $usuarios): self
    {
        $this->usuarios = $usuarios;

        return $this;
    }

    public function getUnidades(): bool
    {
        return $this->unidades;
    }

    public function setUnidades(bool $unidades): self
    {
        $this->unidades = $unidades;

        return $this;
    }

    public function getAvisos(): bool
    {
        return $this->avisos;
    }

    public function setAvisos(bool $avisos): self
    {
        $this->avisos = $avisos;

        return $this;
    }

    public function getTeses(): bool
    {
        return $this->teses;
    }

    public function setTeses(bool $teses): self
    {
        $this->teses = $teses;

        return $this;
    }

    public function getCoordenadores(): bool
    {
        return $this->coordenadores;
    }

    public function setCoordenadores(bool $coordenadores): self
    {
        $this->coordenadores = $coordenadores;

        return $this;
    }

    public function getSetores(): bool
    {
        return $this->setores;
    }

    public function setSetores(bool $setores): self
    {
        $this->setores = $setores;

        return $this;
    }

    public function getContasEmails(): bool
    {
        return $this->contasEmails;
    }

    public function setContasEmails(bool $contasEmails): self
    {
        $this->contasEmails = $contasEmails;

        return $this;
    }

    public function getCompetencias(): bool
    {
        return $this->competencias;
    }

    public function setCompetencias(bool $competencias): self
    {
        $this->competencias = $competencias;

        return $this;
    }

    public function getDominios(): bool
    {
        return $this->dominios;
    }

    public function setDominios(bool $dominios): self
    {
        $this->dominios = $dominios;

        return $this;
    }

    public function getGerenciamentoTarefas(): bool
    {
        return $this->gerenciamentoTarefas;
    }

    public function setGerenciamentoTarefas(bool $gerenciamentoTarefas): self
    {
        $this->gerenciamentoTarefas = $gerenciamentoTarefas;

        return $this;
    }
}
