<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\DTO;

use DateTime;
// Não é mais estritamente necessário importar TarefaDTO e UsuarioDTO aqui
// se estivermos usando o FQCN completo nas anotações 'ref'.
// No entanto, pode ser mantido por clareza ou se usado em outro lugar no DTO.
// use SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa as TarefaDTO;
// use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\DTO\RestDto;
use SuppCore\AdministrativoBackend\DTO\Traits\IdUuid;
use SuppCore\AdministrativoBackend\DTO\Traits\Timeblameable;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Form\Attributes as Form;
use SuppCore\AdministrativoBackend\Mapper\Attributes as DTOMapper;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;
// use JMS\Serializer\Annotation as Serializer; // Descomente se precisar de anotações específicas do JMS Serializer

/**
 * Class TarefaMensagem.
 *
 * DTO para representar uma mensagem no chat de uma tarefa.
 */
#[DTOMapper\JsonLD(
    jsonLDId: '/v1/administrativo/tarefa_mensagem/{id}', // Ajuste o path se o seu padrão for diferente
    jsonLDType: 'TarefaMensagem',
    jsonLDContext: '/api/doc/#model-TarefaMensagem' // Verifique se este é o contexto correto da sua documentação
)]
#[Form\Form]
class TarefaMensagem extends RestDto
{
    use IdUuid;
    use Timeblameable; // Fornece criadoEm, atualizadoEm, criadoPor, atualizadoPor

    /**
     * Tarefa à qual esta mensagem pertence.
     * No POST, o ID da tarefa é obtido da URL. Este campo é para respostas GET.
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Tarefa',
            'required' => false, // Não é obrigatório no corpo do DTO de entrada, pois vem da URL
        ]
    )]
    #[OA\Property(
        description: "Tarefa associada",
        ref: \SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa::class
    )]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Tarefa')]
    protected ?EntityInterface $tarefa = null;

    /**
     * Usuário que enviou a mensagem.
     * No POST, é preenchido com o usuário logado. Este campo é para respostas GET.
     */
    #[Form\Field(
        'Symfony\Bridge\Doctrine\Form\Type\EntityType',
        options: [
            'class' => 'SuppCore\AdministrativoBackend\Entity\Usuario',
            'required' => false, // Não é obrigatório no corpo do DTO de entrada
        ]
    )]
    #[OA\Property(
        description: "Usuário remetente",
        ref: \SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario::class
    )]
    #[DTOMapper\Property(dtoClass: 'SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario')]
    protected ?EntityInterface $usuario = null;

    #[OA\Property(description: "ID do usuário remetente", type: "integer", readOnly: true)]
    protected ?int $usuarioId = null;
    /**
     * Nome do usuário que enviou a mensagem.
     * Geralmente preenchido automaticamente na resposta.
     */
    #[OA\Property(description: "Nome do usuário que enviou a mensagem", type: "string", maxLength: 255, readOnly: true)]
    #[DTOMapper\Property]
    protected ?string $usuarioNome = null;

    /**
     * Conteúdo da mensagem.
     * Campo principal para a criação de uma nova mensagem.
     */
    #[Form\Field(
        'Symfony\Component\Form\Extension\Core\Type\TextareaType',
        options: [
            'required' => true, // Obrigatório ao criar
        ]
    )]
    #[Assert\NotBlank(message: "O conteúdo da mensagem não pode estar em branco.")]
    #[Assert\Length(
        min: 1,
        max: 4000, // Ajuste conforme necessário
        minMessage: "O conteúdo da mensagem deve ter no mínimo {{ limit }} caractere.",
        maxMessage: "O conteúdo da mensagem deve ter no máximo {{ limit }} caracteres."
    )]
    #[OA\Property(description: "Conteúdo da mensagem", type: "string", maxLength: 4000)]
    #[DTOMapper\Property]
    protected ?string $conteudo = null;

    /**
     * Data e hora em que a mensagem foi enviada.
     * Preenchido automaticamente na criação; apenas para leitura nas respostas.
     * Considere usar $criadoEm do trait Timeblameable se for equivalente.
     */
    #[OA\Property(description: "Data e hora do envio da mensagem", type: "string", format: "date-time", readOnly: true)]
    #[DTOMapper\Property]
    protected ?DateTime $dataHoraEnvio = null;


    // --- Getters e Setters ---

    public function getTarefa(): ?EntityInterface
    {
        return $this->tarefa;
    }

    public function setTarefa(?EntityInterface $tarefa): self
    {
        $this->setVisited('tarefa');
        $this->tarefa = $tarefa;
        return $this;
    }

    public function getUsuario(): ?EntityInterface
    {
        return $this->usuario;
    }

    public function setUsuario(?EntityInterface $usuario): self
    {
        $this->setVisited('usuario');
        $this->usuario = $usuario;
        return $this;
    }

    public function getUsuarioNome(): ?string
    {
        return $this->usuarioNome;
    }

    public function setUsuarioNome(?string $usuarioNome): self
    {
        $this->setVisited('usuarioNome');
        $this->usuarioNome = $usuarioNome;
        return $this;
    }

    public function getConteudo(): ?string
    {
        return $this->conteudo;
    }

    public function setConteudo(?string $conteudo): self
    {
        $this->setVisited('conteudo');
        $this->conteudo = $conteudo;
        return $this;
    }

    public function getDataHoraEnvio(): ?DateTime
    {
        return $this->dataHoraEnvio;
    }

    public function setDataHoraEnvio(?DateTime $dataHoraEnvio): self
    {
        $this->setVisited('dataHoraEnvio');
        $this->dataHoraEnvio = $dataHoraEnvio;
        return $this;
    }


    public function getUsuarioId(): ?int { return $this->usuarioId; }
    public function setUsuarioId(?int $usuarioId): self { $this->setVisited('usuarioId'); $this->usuarioId = $usuarioId; return $this; }
}