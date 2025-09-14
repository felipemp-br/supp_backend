<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0028.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoProcesso;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ProcessoResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoProcessoResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0028.
 *
 * @descSwagger=Os processos vinculados por apensamento ou anexação também receber uma data prevista de transição
 * @classeSwagger=Trigger0034
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0034 implements TriggerInterface
{

    /**
     * Trigger0034 constructor.
     */
    public function __construct(
        protected VinculacaoProcessoResource $vinculacaoProcessoResource,
        protected ProcessoResource $processoResource,
        protected ParameterBagInterface $parameterBag,
    ) {
    }

    public function supports(): array
    {
        return [
            Processo::class => [
                'beforeUpdate',
                'beforePatch'
            ],
        ];
    }

    /**
     * @throws OptimisticLockException
     * @throws AnnotationException
     * @throws ORMException
     * @throws ReflectionException
     */
    public function execute(
        Processo|RestDtoInterface|null $restDto, 
        ProcessoEntity|EntityInterface $entity, 
        string $transactionId): void
    {
        if($restDto->getDataHoraProximaTransicao() !== $entity->getDataHoraProximaTransicao()) {
            $vinculacoesProcessos = $this->vinculacaoProcessoResource
                ->getRepository()->findBy(['processo'=>$restDto->getId()]);

            foreach ($vinculacoesProcessos as $vinculacoes){
                /**
                 * REMISSÃO é a mera referência a outro NUP, por necessidade gerencial, sem qualquer consequência
                 * para os documentos ou tramitações. Podem estar em setores e até em unidades diferentes.
                 * Em suma, é apenas um apontamento no sentido de que o outro NUP, de alguma forma,
                 * se correlaciona com o principal.
                 *
                 * Sendo assim, para remissão os NUPs vinculados não são atualizados.
                 */
                if($vinculacoes->getModalidadeVinculacaoProcesso()->getValor() ===
                    $this->parameterBag->get('constantes.entidades.modalidade_vinculacao_processo.const_1')) {
                    continue;
                }

                $processoVinculado = $this->processoResource->getDtoForEntity(
                    $vinculacoes->getProcessoVinculado()->getId(),
                    Processo::class
                );

                $processoVinculado->setDataHoraProximaTransicao($restDto->getDataHoraProximaTransicao());

                $this->processoResource->update(
                    $processoVinculado->getId(),
                    $processoVinculado,
                    $transactionId
                );
            }
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
