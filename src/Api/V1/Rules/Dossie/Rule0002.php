<?php
/**
 * @noinspection LongLine
 * @phpcs:disable Generic.Files.LineLength.TooLong
 */
declare(strict_types=1);

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Dossie;

use SuppCore\AdministrativoBackend\Api\V1\DTO\Dossie as DossieDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\PessoaResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\Dossie;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;

/**
 * Class Rule0002.
 *
 * @descSwagger=Verifica se existe pessoa cadastrada para o documento principal informado.
 * @classeSwagger=Rule0002
 */
class Rule0002 implements RuleInterface
{
    /**
     * Rule0002 constructor.
     *
     * @param RulesTranslate $rulesTranslate
     * @param PessoaResource $pessoaResource
     */
    public function __construct(
        private RulesTranslate $rulesTranslate,
        private PessoaResource $pessoaResource
    ) {
    }

    public function supports(): array
    {
        return [
            DossieDTO::class => [
                'assertCreate',
            ],
        ];
    }

    /**
     * @param RestDtoInterface|DossieDTO|null $restDto
     * @param EntityInterface|Dossie          $entity
     * @param string                          $transactionId
     *
     * @return bool
     *
     * @throws RuleException
     */
    public function validate(RestDtoInterface | DossieDTO | null $restDto, EntityInterface | Dossie $entity, string $transactionId): bool
    {
        // fornece pessoa e numeroDocumentoPrincipal
        // pessoa e numero documento principal devem bater
        if ($restDto->getPessoa() && $restDto->getNumeroDocumentoPrincipal()) {
            if ($restDto->getNumeroDocumentoPrincipal() !== $restDto->getPessoa()->getNumeroDocumentoPrincipal()) {
                $this->rulesTranslate->throwException('dossie', '0002a');
            }
        }

        // nao fornece numero documento principal e a pessoa nao tem um (ou nao tem pessoa)
        if (!$restDto->getNumeroDocumentoPrincipal() && !$restDto->getPessoa()?->getNumeroDocumentoPrincipal()) {
            $this->rulesTranslate->throwException('dossie', '0002b');
        }

        // fornece um numero documento principal, nao fornece uma pessoa e eu nao consigo encontrar a pessoa
        // ou consigo encontrar mas ela nao tem numero de documento principal
        if ($restDto->getNumeroDocumentoPrincipal() && !$restDto->getPessoa()) {
            $pessoa = $this
                ->pessoaResource
                ->findOneBy(['numeroDocumentoPrincipal' => $restDto->getNumeroDocumentoPrincipal()]);

            if (!$pessoa) {
                $this->rulesTranslate->throwException('dossie', '0002c');
            }
        }

        return true;
    }

    public function getOrder(): int
    {
        return 1;
    }
}
