<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Documento/Trigger0001.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Dossie;

use DateTime;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\OrigemDados;
use SuppCore\AdministrativoBackend\Api\V1\Resource\OrigemDadosResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Dossie as DossieEntity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Integracao\Dossie\DossieManager;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class Trigger0001.
 *
 * @descSwagger=Ação para chamar os serviços de consulta e extração de dossies
 * @classeSwagger=Trigger0001
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0003 implements TriggerInterface
{
    /**
     * Trigger0001 constructor.
     *
     * @param DossieManager $dossieManager
     * @param TransactionManager $transactionManager
     * @param OrigemDadosResource $origemDadosResource
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        private readonly DossieManager $dossieManager,
        private readonly TransactionManager $transactionManager,
        private readonly OrigemDadosResource $origemDadosResource,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    /**
     * @return array
     */
    public function supports(): array
    {
        return [
            DossieDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|DossieDTO   $restDto
     * @param EntityInterface|DossieEntity $entity
     * @param string                       $transactionId
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(RestDtoInterface | DossieDTO $restDto, EntityInterface | DossieEntity $entity, string $transactionId): void
    {
        /**
         * @var DossieDTO $restDto
         */
        $origemDadosDTO = new OrigemDados();
        $origemDadosDTO->setStatus(DossieEntity::EM_SINCRONIZACAO);
        $origemDadosDTO->setFonteDados($restDto->getTipoDossie()->getFonteDados());
        $origemDadosDTO->setIdExterno(
            "{$restDto->getTipoDossie()->getFonteDados()}:{$restDto->getNumeroDocumentoPrincipal()}"
        );
        $origemDadosDTO->setServico($restDto->getTipoDossie()->getFonteDados());
        $origemDadosDTO->setDataHoraUltimaConsulta(new DateTime());
        $origemDadosEntity = $this
            ->origemDadosResource
            ->create($origemDadosDTO, $transactionId);
        $restDto->setOrigemDados($origemDadosEntity);

        $geradorDossie = $this
            ->dossieManager
            ->getGeradorDossiePorTipoDossie($restDto->getTipoDossie());

        if (!$restDto->getTipoDossie()->getDatalake()) {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            $this->transactionManager->addAsyncDispatch(
                $geradorDossie->getMessageClass(
                    $entity->getUuid(),
                    $this->tokenStorage->getToken()?->getUser()?->getId(),
                    $restDto->getSobDemanda()
                ),
                $transactionId
            );
        }
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 3;
    }
}
