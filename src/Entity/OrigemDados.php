<?php

declare(strict_types=1);
/**
 * /src/Entity/OrigemDados.php.
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
use SuppCore\AdministrativoBackend\Entity\Traits\Timestampable;
use SuppCore\AdministrativoBackend\Entity\Traits\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class OrigemDados.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[ORM\Entity]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
#[Gedmo\Loggable]
#[ORM\Table(name: 'ad_origem_dados')]
class OrigemDados implements EntityInterface
{
    // Traits
    use Blameable;
    use Timestampable;
    use Id;
    use Uuid;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[ORM\Column(name: 'id_externo', type: 'string', nullable: true)]
    protected ?string $idExterno = null;

    #[Gedmo\Versioned]
    #[ORM\Column(name: 'data_hora_ultima_consulta', type: 'datetime', nullable: false)]
    protected DateTime $dataHoraUltimaConsulta;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[ORM\Column(type: 'string', nullable: false)]
    protected string $servico;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\NotBlank(message: 'O campo não pode estar em branco!')]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[ORM\Column(name: 'fonte_dados', type: 'string', nullable: false)]
    protected string $fonteDados;

    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[Assert\Length(max: 255, maxMessage: 'O campo deve ter no máximo 255 caracteres!')]
    #[ORM\Column(name: 'msg_ultima_consulta', type: 'string', nullable: true)]
    protected ?string $mensagemUltimaConsulta = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    protected int $status = 0;

    /**
     * Constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->setUuid();
    }

    public function getIdExterno(): ?string
    {
        return $this->idExterno;
    }

    public function setIdExterno(?string $idExterno = null): self
    {
        $this->idExterno = $idExterno;

        return $this;
    }

    public function getDataHoraUltimaConsulta(): DateTime
    {
        return $this->dataHoraUltimaConsulta;
    }

    public function setDataHoraUltimaConsulta(DateTime $dataHoraUltimaConsulta): self
    {
        $this->dataHoraUltimaConsulta = $dataHoraUltimaConsulta;

        return $this;
    }

    public function getServico(): string
    {
        return $this->servico;
    }

    public function setServico(string $servico): self
    {
        $this->servico = $servico;

        return $this;
    }

    public function getFonteDados(): string
    {
        return $this->fonteDados;
    }

    public function setFonteDados(string $fonteDados): self
    {
        $this->fonteDados = $fonteDados;

        return $this;
    }

    public function getMensagemUltimaConsulta(): ?string
    {
        return $this->mensagemUltimaConsulta;
    }

    public function setMensagemUltimaConsulta(?string $mensagemUltimaConsulta): self
    {
        $this->mensagemUltimaConsulta = $mensagemUltimaConsulta;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
