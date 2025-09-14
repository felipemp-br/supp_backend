<?php

declare(strict_types=1);
/**
 * /src/RegrasEtiqueta/RegrasEtiquetaMessage.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\RegrasEtiqueta\Message;

use Ramsey\Uuid\Uuid as Ruuid;

/**
 * Class RegrasEtiquetaMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RegrasEtiquetaMessage
{
    private readonly string $messageUuid;
    private string $entityOrigemUuid;
    private string $entityOrigemName;
    private string $siglaMomentoDisparoRegraEtiqueta;
    private ?int $usuarioLogadoId = null;

    public function __construct()
    {
        $this->messageUuid = Ruuid::uuid4()->toString();
    }

    /**
     * Retorna o uuid da mensagem.
     *
     * @return string
     */
    public function getMessageUuid(): string
    {
        return $this->messageUuid;
    }

    /**
     * Retorna o uuid da entidade que originou o momento de disparo.
     *
     * @return string
     */
    public function getEntityOrigemUuid(): string
    {
        return $this->entityOrigemUuid;
    }

    /**
     * Define o uuid da entidade que originou o momento de disparo.
     *
     * @param string $entityOrigemUuid
     * @return self
     */
    public function setEntityOrigemUuid(string $entityOrigemUuid): self
    {
        $this->entityOrigemUuid = $entityOrigemUuid;

        return $this;
    }

    /**
     * Retorna o classname da entidade que originou o momento de disparo.
     *
     * @return string
     */
    public function getEntityOrigemName(): string
    {
        return $this->entityOrigemName;
    }

    /**
     * Define o classname da entidade que originou o momento de disparo.
     *
     * @param string $entityOrigemName
     * @return self
     */
    public function setEntityOrigemName(string $entityOrigemName): self
    {
        $this->entityOrigemName = $entityOrigemName;

        return $this;
    }

    /**
     * Retorna a sigla do momento disparo da regra de etiqueta.
     *
     * @return string
     */
    public function getSiglaMomentoDisparoRegraEtiqueta(): string
    {
        return $this->siglaMomentoDisparoRegraEtiqueta;
    }

    /**
     * Define a sigla do momento disparo da regra de etiqueta.
     * 
     * @param string $siglaMomentoDisparoRegraEtiqueta
     * @return self
     */
    public function setSiglaMomentoDisparoRegraEtiqueta(string $siglaMomentoDisparoRegraEtiqueta): self
    {
        $this->siglaMomentoDisparoRegraEtiqueta = $siglaMomentoDisparoRegraEtiqueta;

        return $this;
    }

    /**
     * Retorna o usuário logado.
     *
     * @return int|null
     */
    public function getUsuarioLogadoId(): ?int
    {
        return $this->usuarioLogadoId;
    }

    /**
     * Define o usuário logado.
     *
     * @param int|null $usuarioLogadoId
     * @return self
     */
    public function setUsuarioLogadoId(?int $usuarioLogadoId): self
    {
        $this->usuarioLogadoId = $usuarioLogadoId;

        return $this;
    }
}
