<?php

declare(strict_types=1);
/**
 * /src/Api/V1/Resource/EnderecoResource.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Resource;

use Doctrine\ORM\NoResultException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Endereco;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Estado;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Municipio;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Pais;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Endereco as Entity;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Repository\EnderecoRepository as Repository;
use SuppCore\AdministrativoBackend\Repository\MunicipioRepository as MunicipioRepository;
use SuppCore\AdministrativoBackend\Rest\RestResource;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class EnderecoResource.
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
class EnderecoResource extends RestResource
{
    private MunicipioRepository $municipioRepository;

    private MunicipioResource $municipioResource;

    private EstadoResource $estadoResource;

    private PaisResource $paisResource;

    /** @noinspection MagicMethodsValidityInspection */

    /**
     * EnderecoResource constructor.
     */
    public function __construct(
        Repository $repository,
        MunicipioResource $municipioResource,
        EstadoResource $estadoResource,
        PaisResource $paisResource,
        MunicipioRepository $municipioRepository,
        ValidatorInterface $validator
    ) {
        $this->setRepository($repository);
        $this->municipioRepository = $municipioRepository;
        $this->municipioResource = $municipioResource;
        $this->estadoResource = $estadoResource;
        $this->paisResource = $paisResource;
        $this->setValidator($validator);
        $this->setDtoClass(Endereco::class);
    }

    public function getDTOForEnderecoCorreios(
        string $cep
    ): RestDtoInterface {
        $action = 'https://buscacepinter.correios.com.br/app/endereco/carrega-cep-endereco.php';
        $ch = curl_init($action);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['endereco' => $cep, 'tipoCEP' => 'ALL']);
        $r = curl_exec($ch);
        curl_close($ch);

        $retorno_correios = json_decode($r, true);

        if (0 == $retorno_correios['total']) {
            throw new NoResultException('CEP não localizado!');
        }

        $enderecoDTO = new Endereco();
        $enderecoDTO->setMunicipio(new Municipio());
        $enderecoDTO->getMunicipio()->setEstado(new Estado());

        $dados = $retorno_correios['dados'][0];
        $enderecoDTO->setLogradouro(mb_strtoupper((trim(strip_tags($dados['logradouroDNEC']))), 'utf-8'));
        $enderecoDTO->setBairro(mb_strtoupper((trim(strip_tags($dados['bairro']))), 'utf-8'));
        $enderecoDTO->getMunicipio()->setNome(mb_strtoupper((trim(strip_tags($dados['localidade']))), 'utf-8'));
        $enderecoDTO->getMunicipio()->getEstado()->setUf(mb_strtoupper((trim(strip_tags($dados['uf']))), 'utf-8'));
        $enderecoDTO->setCep(mb_strtoupper((trim(strip_tags($dados['cep']))), 'utf-8'));

        $municipioEntity = $this->municipioRepository->
            findByNomeAndUf(
                $enderecoDTO->getMunicipio()->getNome(),
                $enderecoDTO->getMunicipio()->getEstado()->getUf());
        if ($municipioEntity) {
            $municiopioDTO = $this->municipioResource->getDtoForEntity($municipioEntity->getId(), Municipio::class);
            $estadoDTO = $this->estadoResource->getDtoForEntity($municipioEntity->getEstado()->getId(), Estado::class);
            $paisDTO = $this->paisResource->getDtoForEntity($municipioEntity->getEstado()->getPais()->getId(), Pais::class);
            $estadoDTO->setPais($paisDTO);
            $municiopioDTO->setEstado($estadoDTO);
            $enderecoDTO->setMunicipio($municiopioDTO);
        } else {
            throw new NoResultException('CEP não localizado!');
        }

        return $enderecoDTO;
    }
}
