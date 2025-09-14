<?php

declare(strict_types=1);
/**
 * /src/Api/V1/DTO/Desentranhamento.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DMS\Filter\Rules as Filter;
use JMS\Serializer\Annotation as Serializer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada as JuntadaDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\Blameable;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Softdeleteable;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class Desentranhamento.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/desentranhamento/{id}',
    jsonLDType: 'Desentranhamento',
    jsonLDContext: '/api/doc/#model-Desentranhamento'
)]
#[Form\Form]
class Desentranhamento extends RestDto
{
    use IdUuid;
    use Timeblameable;
    use Blameable;
    use Softdeleteable;
    public const TIPOS = ['processo_existente', 'novo_processo', 'arquivo'];

    #[Serializer\Exclude]
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Juntada',
            'multiple' => true,
            'required' => false,
        ]
    )]
    public $juntadasBloco = [];

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Juntada',
            'required' => true,
        ]
    )]
    #[Assert\NotNull(message: 'O campo não pode ser nulo!')]
    #[OA\Property(ref: new Model(type: JuntadaDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Juntada')]
    protected ?EntityInterface $juntada = null;

    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Processo',
            'required' => false,
        ]
    )]
    #[OA\Property(ref: new Model(type: ProcessoDTO::class))]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Processo')]
    protected ?EntityInterface $processoDestino = null;

    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => false,
        ]
    )]
    #[Assert\Length(max: 255, maxMessage: 'Campo deve ter no máximo {{ limit }} letras ou números')]
    #[Filter\StripTags]
    #[Filter\Trim]
    #[Filter\StripNewlines]
    #[Filter\ToUpper(encoding: 'UTF-8')]
    #[OA\Property(type: 'string')]
    #[DTOMapper\Property]
    protected ?string $observacao = null;

    #[Serializer\Exclude]
    #[Assert\Choice(['processo_existente', 'novo_processo', 'arquivo'])]
    #[OA\Property(type: 'string')]
    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextType',
        options: [
            'required' => true,
        ]
    )]
    protected ?string $tipo = null;

    public function __construct()
    {
    }

    public function getJuntada(): ?EntityInterface
    {
        return $this->juntada;
    }

    public function setJuntada(?EntityInterface $juntada): self
    {
        $this->setVisited('juntada');

        $this->juntada = $juntada;

        return $this;
    }

    public function getProcessoDestino(): ?EntityInterface
    {
        return $this->processoDestino;
    }

    public function setProcessoDestino(?EntityInterface $processoDestino): self
    {
        $this->setVisited('processoDestino');

        $this->processoDestino = $processoDestino;

        return $this;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): self
    {
        $this->setVisited('observacao');

        $this->observacao = $observacao;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): self
    {
        $this->setVisited('tipo');

        $this->tipo = $tipo;

        return $this;
    }

    #[Assert\Callback]
    public function isValid(ExecutionContextInterface $context): void
    {
        if ('processo_existente' === $this->getTipo() && !$this->getProcessoDestino()) {
            $context->buildViolation(
                'É necessário informar o processo existente para onde a o documento será enviado!'
            )
                ->atPath('id')
                ->addViolation();
        }
    }

    public function addJuntadasBloco(?EntityInterface $juntadasBloco): self
    {
        $this->juntadasBloco[] = $juntadasBloco;

        return $this;
    }

    /**
     * @return self
     */
    public function resetJuntadasBloco()
    {
        $this->juntadasBloco = [];

        return $this;
    }

    /**
     * @return array
     */
    public function getJuntadasBloco()
    {
        return $this->juntadasBloco;
    }
}
