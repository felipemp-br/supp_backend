<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\AcoesEtiqueta;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * AbstractAcoesEtiquetaHandler.php.
 *
 * @template T
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class AbstractAcoesEtiquetaHandler
{
    protected SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     *
     * @return void
     */
    #[Required]
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    /**
     * Deserializa o contexto para o modelo especificado.
     *
     * @template TModel
     *
     * @param string               $context
     * @param class-string<TModel> $model
     *
     * @return TModel
     */
    protected function getModel(
        string $context,
        string $model,
    ): mixed {
        return $this->serializer->deserialize(
            $context,
            $model,
            'json',
        );
    }
}
