<?php

declare(strict_types=1);
/**
 * /src/Entity/ObjetoAvaliado.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Entity;

use DateTime;
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
 * Class ObjetoAvaliado.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\SoftDeleteable(fieldName: 'apagadoEm')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_objeto_avaliado')]
#[ORM\Index(columns: ['classe'], name: 'idx_objeto_classe')]
#[ORM\Index(columns: ['objeto_id'], name: 'idx_objeto_id')]
class ObjetoAvaliado implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Softdeleteable;
    use Id;
    use Uuid;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'O campo deve ter no mínimo 3 caracteres!',
        maxMessage: 'O campo deve ter no máximo 255 caracteres!'
    )]
    #[ORM\Column(name: 'classe', type: 'string', nullable: false)]
    protected string $classe;

    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[ORM\Column(name: 'objeto_id', type: 'integer', nullable: false)]
    protected int $objetoId;

    #[ORM\Column(type: 'float', nullable: true)]
    protected null|float $avaliacaoResultante = null;

    #[ORM\Column(name: 'dt_ult_avaliacao', type: 'datetime', nullable: true)]
    protected null|DateTime $dataUltimaAvaliacao = null;

    #[ORM\Column(name: 'qtd_avaliacoes', type: 'integer', nullable: true)]
    protected null|int $quantidadeAvaliacoes = null;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getClasse(): string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getObjetoId(): int
    {
        return $this->objetoId;
    }

    public function setObjetoId(int $objetoId): self
    {
        $this->objetoId = $objetoId;

        return $this;
    }

    public function getAvaliacaoResultante(): ?float
    {
        return $this->avaliacaoResultante;
    }

    public function setAvaliacaoResultante(?float $avaliacaoResultante): self
    {
        $this->avaliacaoResultante = $avaliacaoResultante;

        return $this;
    }

    public function getDataUltimaAvaliacao(): ?DateTime
    {
        return $this->dataUltimaAvaliacao;
    }

    public function setDataUltimaAvaliacao(?DateTime $dataUltimaAvaliacao): self
    {
        $this->dataUltimaAvaliacao = $dataUltimaAvaliacao;

        return $this;
    }

    public function getQuantidadeAvaliacoes(): ?int
    {
        return $this->quantidadeAvaliacoes;
    }

    public function setQuantidadeAvaliacoes(?int $quantidadeAvaliacoes): self
    {
        $this->quantidadeAvaliacoes = $quantidadeAvaliacoes;

        return $this;
    }
}
