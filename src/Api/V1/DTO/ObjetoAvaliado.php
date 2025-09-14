<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/ObjetoAvaliado.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;

/**
 * Class ObjetoAvaliado.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/objeto_avaliado/{id}',
    jsonLDType: 'ObjetoAvaliado',
    jsonLDContext: '/api/doc/#model-ObjetoAvaliado'
)]
#[Form\Form]
class ObjetoAvaliado extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $classe = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\NumberType',
        options: [
            'required' => true,
        ]
    )]
    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected ?int $objetoId = null;

    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected null|float $avaliacaoResultante = null;

    #[DTOMapper\Property]
    protected null|DateTime $dataUltimaAvaliacao = null;

    #[OA\Property(type: 'integer')]
    #[DTOMapper\Property]
    protected null|int $quantidadeAvaliacoes = null;

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(?string $classe): self
    {
        $this->setVisited('classe');
        $this->classe = $classe;

        return $this;
    }

    public function getObjetoId(): ?int
    {
        return $this->objetoId;
    }

    public function setObjetoId(?int $objetoId): self
    {
        $this->setVisited('objetoId');
        $this->objetoId = $objetoId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAvaliacaoResultante(): ?float
    {
        return $this->avaliacaoResultante;
    }

    /**
     * @param int|null $avaliacaoResultante
     */
    public function setAvaliacaoResultante(?float $avaliacaoResultante): self
    {
        $this->setVisited('avaliacaoResultante');
        $this->avaliacaoResultante = $avaliacaoResultante;

        return $this;
    }

    public function getDataUltimaAvaliacao(): ?DateTime
    {
        return $this->dataUltimaAvaliacao;
    }

    public function setDataUltimaAvaliacao(?DateTime $dataUltimaAvaliacao): self
    {
        $this->setVisited('dataUltimaAvaliacao');
        $this->dataUltimaAvaliacao = $dataUltimaAvaliacao;

        return $this;
    }

    public function getQuantidadeAvaliacoes(): ?int
    {
        return $this->quantidadeAvaliacoes;
    }

    public function setQuantidadeAvaliacoes(?int $quantidadeAvaliacoes): self
    {
        $this->setVisited('quantidadeAvaliacoes');
        $this->quantidadeAvaliacoes = $quantidadeAvaliacoes;

        return $this;
    }
}
