<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/InteressadoResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Interessado;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Interessado as Entity;
use SuppCore\AdministrativoBackend\Repository\InteressadoRepository as Repository;
use SuppCore\AdministrativoBackend\Repository\PessoaRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeInteressadoRepository;
use SuppCore\AdministrativoBackend\Repository\ProcessoRepository;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use SuppCore\AdministrativoBackend\Transaction\Context;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

/**
 * Class InteressadoResource.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
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
class InteressadoResource extends RestResource
{
    /** @noinspection MagicMethodsValidityInspection */

    /**
     * InteressadoResource constructor.
     */
    public function __construct(
        Repository $repository,
        ValidatorInterface $validator,
        protected PessoaRepository $pessoaRepository,
        protected ModalidadeInteressadoRepository $modalidadeInteressadoRepository,
        protected ProcessoRepository $processoRepository,
        protected PessoaResource $pessoaResource
    ) {
        $this->setRepository($repository);
        $this->setValidator($validator);
        $this->setDtoClass(Interessado::class);
    }

    /**
     * @param string $bloco
     * @return array
     */
    public function processaBloco(string $bloco, $type, $processoId, $transactionManager): array {
        $result['success'] = [];
        $result['error'] = [];
        $result['values'] = [];
        $return['total'] = 0;

        $transactionId = $transactionManager->begin();
        
        if($bloco != null) {
            if(str_contains($bloco, ',')) {
                $result['values'] = explode(',', $bloco);
            } else {
                $result['values'] = preg_split('/\r|\r\n|\n/', $bloco);
            }
            $result['total'] = count($result['values']);

            $processo = $this->processoRepository->find($processoId);
            $modalidadeInt = $this->modalidadeInteressadoRepository->find($type);

            foreach($result['values'] as $numeroDocumento) {
                $numeroDocumento = $this->sanitizeDocumento($numeroDocumento);
                $transactionManager->addContext(
                    new Context((strlen($numeroDocumento) == 11) ? 'cpf' : 'cnpj', $numeroDocumento),
                    $transactionId
                );
                $data = $this->pessoaResource->find(['numeroDocumentoPrincipal' => 'eq:' . $numeroDocumento], null, 1);

                try {
                    if($data['total'] > 0) {
                        $entity = new Entity();
                        $entity->setPessoa($data['entities'][0]);
                        $entity->setModalidadeInteressado($modalidadeInt);
                        $entity->setProcesso($processo);

                        $this->getRepository()->save($entity, $transactionId);
                        $result['success'][] = [ 'numeroDocumentoPrincipal' => $numeroDocumento, 'nome' => $data['entities'][0]->getNome() ];
                    } else {
                        $result['error'][] = $numeroDocumento;
                    }
                } catch(Throwable $e) {
                    $result['error'][] = $numeroDocumento;
                }   
            }
        }

        $transactionManager->commit($transactionId);

        return $result;
    }

    private function sanitizeDocumento(string $documento): string {
        return preg_replace('/\.|-/', '', trim($documento));
    }
}
