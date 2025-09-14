<?php
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Entity\Interfaces;

use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Etiqueta;

/**
 * VinculacaoEtiquetaInterface.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
interface VinculacaoEtiquetaInterface
{
    /**
     * @return Etiqueta|null
     */
    public function getEtiqueta(): ?EntityInterface;

    /**
     * @param bool $sugestao
     *
     * @return self
     */
    public function setSugestao(bool $sugestao): self;

    /**
     * @return bool|null
     */
    public function getSugestao(): ?bool;

    /**
     * @return string|null
     */
    public function getAcoesExecucaoSugestao(): ?string;

    /**
     * @return EntityInterface|null
     */
    public function getUsuarioAprovacaoSugestao(): ?EntityInterface;

    /**
     * @param bool $privada
     *
     * @return self
     */
    public function setPrivada(bool $privada): self;

    /**
     * @return bool|null
     */
    public function getPrivada(): ?bool;
}
