<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\InteligenciaArtificial\Classificacao\Message;

/**
 * Class ClassificaDocumentoMessage.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class ClassificaDocumentoMessage
{
    private string $documentoUuid;

    /**
     * @param string $documentoUuid
     */
    public function __construct(string $documentoUuid)
    {
        $this->documentoUuid = $documentoUuid;
    }

    /**
     * @return string
     */
    public function getDocumentoUuid(): string
    {
        return $this->documentoUuid;
    }

    /**
     * @param string $documentoUuid
     *
     * @return ClassificaDocumentoMessage
     */
    public function setDocumentoUuid(string $documentoUuid): ClassificaDocumentoMessage
    {
        $this->documentoUuid = $documentoUuid;

        return $this;
    }
}
