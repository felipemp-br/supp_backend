<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Pipes/Lembrete/Pipe0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Mapper\Pipes\Lembrete;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Lembrete as LembreteDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Lembrete;
use SuppCore\AdministrativoBackend\Mapper\Pipes\PipeInterface;

/**
 * Class Pipe0001.
 * Utilizado para converter as tags e caracteres especiais html do lembrete para minúsculo. 
 * Exemplo: 
 *      &NBSP; -> &nbsp; 
 *      <P> -> <p>
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Pipe0001 implements PipeInterface
{
    /**
     * Pipe0001 constructor.
     */
    public function __construct() {}

    public function supports(): array
    {
        return [
            LembreteDTO::class => [
                'onCreateDTOFromEntity',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|LembreteDTO|null $restDto
     * @param EntityInterface|Lembrete       $entity
     *
     * @return void
     */
    public function execute(RestDtoInterface|LembreteDTO|null &$restDto, EntityInterface|Lembrete $entity): void
    {   
        $restDto->setConteudo(preg_replace_callback(
            '/(<[^>]+>)|(&[a-zA-Z]+;)/i', 
            fn($matches) => strtolower($matches[0]),
            $restDto->getConteudo()
        ));
    }

    public function getOrder(): int
    {
        return 1;
    }
}
