<?php
declare(strict_types=1);
/**
 * /src/Integracao/Dossie/DossieGerado.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Integracao\Dossie;

use SuppCore\AdministrativoBackend\Entity\Documento;

/**
 * Class DossieGerado.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class DossieGerado
{
    /**
     * DossieGerado constructor.
     * @param string $conteudo
     * @param int $versao
     * @param string|null $conteudoHtml
     * @param string|null $extensao
     */
    public function __construct(
        private string $conteudo,
        private int $versao,
        private ?string $conteudoHtml = null,
        private ?string $extensao = 'html',
        private ?string $protocolo = null,
        private ?string $status = null,
    ) {
    }

    /**
     * @return string
     */
    public function getConteudo(): string
    {
        return $this->conteudo;
    }

    /**
     * @param string $conteudo
     * @return self
     */
    public function setConteudo(string $conteudo): self
    {
        $this->conteudo = $conteudo;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getConteudoHtml(): ?string
    {
        return $this->conteudoHtml;
    }

    /**
     * @param string|null $conteudoHtml
     * @return self
     */
    public function setConteudoHtml(?string $conteudoHtml): self
    {
        $this->conteudoHtml = $conteudoHtml;

        return $this;
    }

    /**
     * @return int
     */
    public function getVersao(): int
    {
        return $this->versao;
    }

    /**
     * @param int $versao
     * @return self
     */
    public function setVersao(int $versao): self
    {
        $this->versao = $versao;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExtensao(): ?string
    {
        return $this->extensao;
    }

    /**
     * @param string|null $extensao
     * @return self
     */
    public function setExtensao(?string $extensao): self
    {
        $this->extensao = $extensao;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProtocolo(): ?string
    {
        return $this->protocolo;
    }

    /**
     * @param string|null $protocolo
     */
    public function setProtocolo(?string $protocolo): void
    {
        $this->protocolo = $protocolo;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
}
