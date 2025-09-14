<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Triggers/Usuario/Trigger0007.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Triggers\Usuario;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Exception\ORMException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pessoa;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Usuario as UsuarioDTO;
use SuppCore\AdministrativoBackend\Api\V1\DTO\VinculacaoPessoaUsuario;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\Api\V1\Resource\VinculacaoPessoaUsuarioResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Entity\Pessoa as PessoaEntity;
use SuppCore\AdministrativoBackend\Entity\Usuario as UsuarioEntity;
use SuppCore\AdministrativoBackend\Repository\ModalidadeGeneroPessoaRepository;
use SuppCore\AdministrativoBackend\Repository\ModalidadeQualificacaoPessoaRepository;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Triggers\TriggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class Trigger0007.
 *
 * @descSwagger=Criar uma pessoa para o usuário cadastrado se não existir e realiza a vinculação!
 * @classeSwagger=Trigger0007
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Trigger0007 implements TriggerInterface
{
    /**
     * @param PessoaResource $pessoaResource
     * @param ModalidadeGeneroPessoaRepository $modalidadeGeneroPessoaRepository
     * @param ModalidadeQualificacaoPessoaRepository $modalidadeQualificacaoPessoaRepository
     * @param VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource
     * @param ParameterBagInterface $parameterBag
     * @param TransactionManager $transactionManager
     */
    public function __construct(
        private readonly PessoaResource $pessoaResource,
        private readonly ModalidadeGeneroPessoaRepository $modalidadeGeneroPessoaRepository,
        private readonly ModalidadeQualificacaoPessoaRepository $modalidadeQualificacaoPessoaRepository,
        private readonly VinculacaoPessoaUsuarioResource $vinculacaoPessoaUsuarioResource,
        private readonly ParameterBagInterface $parameterBag,
        private readonly TransactionManager $transactionManager
    ) {
    }

    public function supports(): array
    {
        return [
            UsuarioDTO::class => [
                'afterCreate',
            ],
        ];
    }

    /**
     * @param UsuarioDTO|RestDtoInterface|null $restDto
     * @param UsuarioEntity|EntityInterface $entity
     * @param string $transactionId
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function execute(
        UsuarioDTO|RestDtoInterface|null $restDto,
        UsuarioEntity|EntityInterface $entity,
        string $transactionId
    ): void {
        // já existe uma pessoa na base?
        $pessoaEntity = current(array_filter(
            $this->transactionManager->getScheduledEntities(PessoaEntity::class, $transactionId),
            fn (PessoaEntity $p) => $p->getNumeroDocumentoPrincipal() === $restDto->getUsername()
        ));

        $pessoaEntity = $pessoaEntity ?: $this->pessoaResource->getRepository()->findOneBy(
            ['numeroDocumentoPrincipal' => $restDto->getUsername()]
        );

        if (!$pessoaEntity) {
            $pessoaDTO = new Pessoa();
            $pessoaDTO->setNome($restDto->getNome());
            $pessoaDTO->setNumeroDocumentoPrincipal($restDto->getUsername());
            $pessoaDTO->setModalidadeGeneroPessoa(
                $this->modalidadeGeneroPessoaRepository->findOneBy(
                    ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_genero_pessoa.const_1')]
                )
            );
            $pessoaDTO->setModalidadeQualificacaoPessoa(
                $this->modalidadeQualificacaoPessoaRepository->findOneBy(
                    ['valor' => $this->parameterBag->get('constantes.entidades.modalidade_genero_pessoa.const_2')]
                )
            );

            $pessoaEntity = $this->pessoaResource->create(
                $pessoaDTO,
                $transactionId
            );
        }

        // vinculação
        $vinculacaoPessoaUsuarioDTO = new VinculacaoPessoaUsuario();
        $vinculacaoPessoaUsuarioDTO->setUsuarioVinculado($entity);
        $vinculacaoPessoaUsuarioDTO->setPessoa($pessoaEntity);

        $this->vinculacaoPessoaUsuarioResource->create(
            $vinculacaoPessoaUsuarioDTO,
            $transactionId
        );
    }

    public function getOrder(): int
    {
        return 1;
    }
}
