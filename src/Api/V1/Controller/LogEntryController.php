<?php

declare(strict_types=1);
/**
 * /src/Controller/LogEntryController.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Tool\Wrapper\EntityWrapper;
use Knp\Component\Pager\PaginatorInterface;
use OpenApi\Attributes as OA;
use SuppCore\AdministrativoBackend\Annotation\RestApiDoc;
use SuppCore\AdministrativoBackend\Entity\ComponenteDigital;
use SuppCore\AdministrativoBackend\Rest\Controller;
use SuppCore\AdministrativoBackend\Rest\RequestHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
#[Route(path: '/v1/administrativo/logEntry')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[OA\Tag(name: 'LogEntry')]
class LogEntryController extends Controller
{
    public bool $noResource = true;

    private readonly PaginatorInterface $paginator;    

    public function __construct(
        PaginatorInterface $paginator
    ) {
        $this->paginator = $paginator;
    }

    /**
     * Endpoint action para localizar um log no GEDMO.
     */
    #[Route(path: '/logentry', methods: ['GET'])]
    #[IsGranted('ROLE_COLABORADOR')]
    #[RestApiDoc]
    public function getLogAction(
        Request $request,
        EntityManagerInterface $em,
        ?array $allowedHttpMethods = null
    ): Response {
        $allowedHttpMethods ??= ['GET'];

        // Make sure that we have everything we need to make this work
        $this->validateRestMethod($request, $allowedHttpMethods);

        $criteria = RequestHandler::getCriteria($request);
        $limit = RequestHandler::getLimit($request);
        $offset = RequestHandler::getOffset($request);
        $orderBy = RequestHandler::getOrderBy($request);

        $unidade = false;
        if (('unidadeResponsavel' === $criteria['target']) || ('unidade' === $criteria['target'])) {
            $criteria['target'] = 'setorResponsavel';
            $unidade = true;
        }

        if (isset($criteria['entity']) && isset($criteria['id']) && isset($criteria['target'])) {
            if (array_key_exists('softdeleteable', $em->getFilters()->getEnabledFilters())) {
                $em->getFilters()->disable('softdeleteable');
            }

            $repo = $em->getRepository('Gedmo\Loggable\Entity\LogEntry');

            $entity = $em->find($criteria['entity'], $criteria['id']);
            
            if(count($orderBy) == 0) {
                $logs = $repo->getLogEntries($entity);
            } else {
                $wrapped = new EntityWrapper($entity, $em);
                $objectClass = $wrapped->getMetadata()->getName();
                $logs = $repo->findBy(['objectClass' => $objectClass, 'objectId' => $criteria['id']], $orderBy);
            }

            $nomeFuncaoObj = 'get'.ucfirst((string) $criteria['target']);
            $tipoObjeto = '';
            if (is_object($entity->{$nomeFuncaoObj}())) {
                $tipoObjeto = implode('', array_slice(explode('\\', get_class($entity->{$nomeFuncaoObj}())), -1));
            }

            $result = [];

            foreach ($logs as $log) {
                $data = $log->getData();

                $criteriaUser = [
                    'username' => $log->getUsername(),
                ];

                $usuario = $em->getRepository('SuppCore\AdministrativoBackend\Entity\Usuario')->findOneBy(
                    $criteriaUser
                );

                $logId = $log->getId();

                if (null !== $usuario) {
                    $nomeUsuario = $usuario->getNome().' ('.substr(
                        (string) $log->getUsername(),
                        0,
                        5
                    ).'******)';
                } else {
                    $nomeUsuario = 'SISTEMA SUPP';
                }

                if (isset($criteria['target']) && isset($data[$criteria['target']])) {
                    if (!is_object($entity->{$nomeFuncaoObj}())) {
                        $valor = $data[$criteria['target']] ? $data[$criteria['target']] : '';
                    } elseif ('DateTime' === $tipoObjeto) {
                        $valor = $data[$criteria['target']] ? $data[$criteria['target']]->format('d/m/Y H:i:s') : '';
                    } else {
                        $obj = $em->getRepository("SuppCore\AdministrativoBackend\\Entity\\".$tipoObjeto)
                            ->find($data[$criteria['target']]['id']);
                        if (method_exists($obj, 'getNome')) {
                            $valor = $obj->getNome();
                        } elseif (method_exists($obj, 'getValor')) {
                            $valor = $obj->getValor();
                        } else {
                            $valor = '';
                        }

                        if ($unidade) {
                            $valor = $obj->getUnidade()->getNome() ? $obj->getUnidade()->getNome() : '';
                        }
                    }

                    $result[] = [
                        'logId' => $logId,
                        'valor' => $valor,
                        'loggedAt' => $log->getLoggedAt()->format('Y-m-d\TH:i:s'),
                        'username' => $nomeUsuario,
                        'objectId' => $entity->getId(),
                        'action' => $log->getAction()
                    ];
                }
            }

            $pagination = $this->paginator->paginate($result, ($offset / $limit) + 1, $limit);

            $result = [];
            $result['entities'] = [];
            $result['total'] = $pagination->getTotalItemCount();

            foreach ($pagination->getItems() as $r) {
                /* @var ComponenteDigital $entity */
                $result['entities'][] = $r;
            }

            $response = new Response();

            $response->setContent(json_encode($result));

            if (!array_key_exists('softdeleteable', $em->getFilters()->getEnabledFilters())) {
                $em->getFilters()->enable('softdeleteable');
            }

            return $response;
        }
    }
}
