<?php

declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use SuppCore\AdministrativoBackend\Api\V1\DTO\ComponenteDigital;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Workflow as WorkflowDTO;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Workflow as Entity;
use SuppCore\AdministrativoBackend\Repository\WorkflowRepository as Repository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Process\Process;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Workflow\DefinitionBuilder;
use Symfony\Component\Workflow\Dumper\GraphvizDumper;
use Symfony\Component\Workflow\Transition;

/**
 * Class WorkflowResource.
 *
 * @author  Felipe Pena <felipe.pena@datainfo.inf.br>
 *
 * @codingStandardsIgnoreStart
 *
 * @method Repository  getRepository(): Repository
 * @method Entity[]    find(array $criteria = null, array $orderBy = null, int $limit = null, int $offset = null, array $search = null, array $populate = null): array
 * @method Entity|null findOne(int $id, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null, bool $throwExceptionIfNotFound = null): ?EntityInterface
 * @method Entity      create(RestDtoInterface $dto, string $transactionId, bool $skipValidation = null): EntityInterface
 * @method Entity      update(int $id, RestDtoInterface $dto, string $transactionId, bool $skipValidation = null): EntityInterface
 * @method Entity      delete(int $id, string $transactionId): EntityInterface
 * @method Entity      save(EntityInterface $entity, string $transactionId, bool $skipValidation = null): EntityInterface
 *
 * @codingStandardsIgnoreEnd
 */
class WorkflowResource extends RestResource
{
/** @noinspection MagicMethodsValidityInspection */

    /**
     * AcaoResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(WorkflowDTO::class);
    }

    /**
     * Método que gera a imagem de visualização do workflow no formato base64.
     *
     * @param $id
     */
    public function generateWorkflowImage($id): ComponenteDigital
    {
        $workflow = $this->findOne($id);

        if (!$workflow) {
            throw new NotFoundHttpException('Workflow não encontrado');
        }

        $definitionBuilder = new DefinitionBuilder();
        $arrPlaces = [];
        $arrTransitions = [];        

        foreach ($workflow->getTransicoesWorkflow() as $transicaoWorkflow) {
            $arrPlaces[$transicaoWorkflow->getEspecieTarefaFrom()->getId()] = $transicaoWorkflow
                ->getEspecieTarefaFrom()->getNome();
            $arrPlaces[$transicaoWorkflow->getEspecieTarefaTo()->getId()] = $transicaoWorkflow
                ->getEspecieTarefaTo()->getNome();

            $arrTransitions[] = new Transition(
                $transicaoWorkflow->getEspecieAtividade()->getNome(),
                $transicaoWorkflow->getEspecieTarefaFrom()->getNome(),
                $transicaoWorkflow->getEspecieTarefaTo()->getNome(),
            );
        }

        $initialPlace = $arrPlaces[$workflow->getEspecieTarefaInicial()->getId()] ?? null;

        $definition = $definitionBuilder
            ->addPlaces($arrPlaces)
            ->addTransitions($arrTransitions)
            ->setInitialPlaces($initialPlace)
            ->build();

        $dumper = new GraphvizDumper();
        $dump = $dumper->dump($definition);
        $process = Process::fromShellCommandline('echo $workflowdata | dot -Tpdf');
        $process->setEnv(['workflowdata' => $dump]);
        $resultCode = $process->run();
        $process->wait();
        $content = $process->getOutput();

        if (0 !== $resultCode) {
            throw new \RuntimeException('Erro ao gerar imagem do Workflow');
        }

        $componenteDigital = new ComponenteDigital();
        $componenteDigital->setConteudo($content)
            ->setDataHoraSoftwareCriacao(new \DateTime())
            ->setMimetype('application/pdf')
            ->setExtensao('.pdf')
            ->setFileName('workflow_'.$workflow->getId().'.pdf')
            ->setTamanho(
                (int) ceil(
                    (strlen($componenteDigital->getEncodedConteudo()) * 3 / 4)
                - substr_count(substr($componenteDigital->getEncodedConteudo(), -2), '=')
                )
            );

        return $componenteDigital;
    }
}
