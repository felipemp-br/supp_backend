<?php

declare(strict_types=1);
/**
 * /src/Rest/Controller.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rest;

use SuppCore\AdministrativoBackend\Form\FormMapper;
use SuppCore\AdministrativoBackend\Rest\Traits\RestMethodHelper;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Class Controller.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
abstract class Controller implements ControllerInterface
{
    // Traits
    use RestMethodHelper;

    final public const METHOD_COUNT = 'countMethod';
    final public const METHOD_CREATE = 'createMethod';
    final public const METHOD_DELETE = 'deleteMethod';
    final public const METHOD_FIND = 'findMethod';
    final public const METHOD_FIND_ONE = 'findOneMethod';
    final public const METHOD_IDS = 'idsMethod';
    final public const METHOD_PATCH = 'patchMethod';
    final public const METHOD_UPDATE = 'updateMethod';

    public TransactionManager $transactionManager;
    protected FormMapper $formMapper;
    protected ParameterBagInterface $parameterBag;
    protected Stopwatch $stopwatch;
    protected CacheInterface $appCache;

    #[Required]
    public function setDependencies(
        TransactionManager $transactionManager,
        FormMapper $formMapper,
        ParameterBagInterface $parameterBag,
        CacheInterface $appCache,
        Stopwatch $stopwatch
    ) {
        $this->transactionManager = $transactionManager;
        $this->formMapper = $formMapper;
        $this->parameterBag = $parameterBag;
        $this->stopwatch = $stopwatch;
        $this->appCache = $appCache;
    }

    /**
     * Method to initialize REST controller.
     */
    protected function init(RestResourceInterface $resource, ResponseHandlerInterface $responseHandler): void
    {
        $this->resource = $resource;
        $this->responseHandler = $responseHandler;
        $this->responseHandler->setResource($this->resource);
    }
}
