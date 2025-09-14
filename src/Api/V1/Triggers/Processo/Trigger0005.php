<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Processo/Trigger0005.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Processo;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Processo as ProcessoDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Processo as ProcessoEntity;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class Trigger0005.
 *
 * @descSwagger=Caso o processo seja novo a procedência será a instituição!
 * @classeSwagger=Trigger0005
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0005 implements TriggerInterface
{
    /**
     * Trigger0005 constructor.
     */
    public function __construct(
        private readonly PessoaResource $pessoaResource,
        private readonly ParameterBagInterface $parameterBag,
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public function supports(): array
    {
        return [
            ProcessoDTO::class => [
                'beforeCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|ProcessoDTO|null $restDto
     * @param EntityInterface|ProcessoEntity $entity
     * @param string $transactionId
     * @noinspection PhpUnusedParameterInspection
     */
    public function execute(
        RestDtoInterface|ProcessoDTO|null $restDto,
        EntityInterface|ProcessoEntity $entity,
        string $transactionId
    ): void {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $novoDossieColaborador =
            (ProcessoEntity::UA_DOSSIE === $restDto->getUnidadeArquivistica() ||
                ProcessoEntity::TP_NOVO === $restDto->getTipoProtocolo()) &&
            $this->tokenStorage->getToken()?->getUser()?->getColaborador();

        $protocoloExterno =
            $restDto->getProtocoloEletronico() &&
            $this->authorizationChecker->isGranted('ROLE_USUARIO_EXTERNO');

        if (!$restDto->getProcedencia() && ($novoDossieColaborador || $protocoloExterno)) {
            $cnpjInstituicao = $this->parameterBag->get('supp_core.administrativo_backend.cnpj_instituicao');
            $instituicao = $this->pessoaResource->getRepository()->findOneBy([
                'numeroDocumentoPrincipal' => $cnpjInstituicao,
            ]);
            $restDto->setProcedencia($instituicao);
        }
    }

    public function getOrder(): int
    {
        return 1;
    }
}
