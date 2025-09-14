<?php

declare(strict_types=1);

/**
 * /src/Api/V1/Rules/Assinatura/Rule0006.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Api\V1\Rules\Assinatura;

use Doctrine\Common\Annotations\AnnotationException;
use ReflectionException;
use SuppCore\AdministrativoBackend\Api\V1\DTO\Assinatura as AssinaturaDTO;
use SuppCore\AdministrativoBackend\Api\V1\Resource\ComponenteDigitalResource;
use SuppCore\AdministrativoBackend\DTO\RestDtoInterface;
use SuppCore\AdministrativoBackend\Entity\EntityInterface;
use SuppCore\AdministrativoBackend\Enums\AssinaturaPadrao;
use SuppCore\AdministrativoBackend\Helpers\AssinaturaHelper;
use SuppCore\AdministrativoBackend\Helpers\AssinaturaLogHelper;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use SuppCore\AdministrativoBackend\Rules\RuleInterface;
use SuppCore\AdministrativoBackend\Rules\RulesTranslate;
use SuppCore\AdministrativoBackend\Transaction\TransactionManager;
use SuppCore\AdministrativoBackend\Utils\AssinaturaService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Process\Process;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function ord;
use function sprintf;
use function is_int;

/**
 * Class Rule0006.
 *
 * @descSwagger=Assinatura digital deve ser válida e utilizar o padrão PKCS7 ou OPENSSL_ALGO_SHA256 ou OPENSSL_ALGO_SMD5
 *
 * @classeSwagger=Rule0006
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class Rule0006 implements RuleInterface
{

    /**
     * Rule0006 constructor.
     *
     * @param ParameterBagInterface $parameterBag
     * @param RulesTranslate $rulesTranslate
     * @param ComponenteDigitalResource $componenteDigitalResource
     * @param TransactionManager $transactionManager
     * @param AssinaturaLogHelper $logger
     * @param AssinaturaService $assinaturaService
     * @param AssinaturaHelper $assinaturaHelper
     */
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly RulesTranslate $rulesTranslate,
        private readonly ComponenteDigitalResource $componenteDigitalResource,
        private readonly TransactionManager $transactionManager,
        private readonly AssinaturaLogHelper $logger,
        private readonly AssinaturaService $assinaturaService,
        private readonly AssinaturaHelper $assinaturaHelper,
    ) {
    }

    /**
     * @return array[]
     */
    public function supports(): array
    {
        return [
            AssinaturaDTO::class => [
                'beforeCreate',
                // 'skipWhenCommand',
            ],
        ];
    }


    /**
     * @param RestDtoInterface|AssinaturaDTO|null $restDto
     * @param EntityInterface                     $entity
     * @param string                              $transactionId
     *
     * @return bool
     *
     * @throws AnnotationException
     * @throws LoaderError
     * @throws ReflectionException
     * @throws RuleException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function validate(
        RestDtoInterface|AssinaturaDTO|null $restDto,
        EntityInterface $entity,
        string $transactionId
    ): bool {
        /* @var AssinaturaDTO $restDto */

        if ($this->transactionManager->getContext('clonarAssinatura', $transactionId)) {
            return true;
        }

        // Não validar AssinaturaDTO quando null
        if (null === $restDto || null === $restDto->getComponenteDigital()) {
            return true;
        }

        // Se for cadeia_teste, não validar assinatura (permite testes sem um certificado válido)
        if ('cadeia_teste' === $restDto->getCadeiaCertificadoPEM()) {
            return true;
        }

        // Se existir origem de dados, não validar assinatura
        if (null !== $restDto->getOrigemDados()) {
            return true;
        }

        // Assinatura a ser avaliada
        $assinaturaBase64 = $restDto->getAssinatura();

        if (empty($assinaturaBase64)) {
            $this->rulesTranslate->throwException(
                'assinatura',
                '0006',
                ['Não foi possível localizar a assinatura.']
            );
        }

        $assinatura = base64_decode($assinaturaBase64);

        if (false === $assinatura) {
            $this->rulesTranslate->throwException(
                'assinatura',
                '0006',
                ['Não foi possível decodificar a assinatura.']
            );
        }

        if ($this->isPkcs7($assinatura)) {
            // Assinatura padrão PKCS7

            $hash = $restDto->getComponenteDigital()->getHash();

            if (empty($hash)) {
                $this->rulesTranslate->throwException(
                    'assinatura',
                    '0006',
                    ['Erro ao recuperar o Hash do componente digital. Tente novamente!']
                );
            }

            if (empty($restDto->getPadrao()) || AssinaturaPadrao::CAdES->value === $restDto->getPadrao()) {
                // CAdES

                if (!$this->assinaturaHelper->isValidCAdES($hash, $assinaturaBase64)) {
                    $this->rulesTranslate->throwException(
                        'assinatura',
                        '0006',
                        [
                            'Assinatura inválida no documento! '.
                            $this->assinaturaService->getDocumentoLocation($restDto->getComponenteDigital())
                        ]
                    );
                }
            } else {
                // PAdES

                // recupera o conteudo do componente digital
                $conteudo = $restDto->getComponenteDigital()->getConteudo();

                // caso o conteúdo não esteja presente no componente digital, realizar download
                if (empty($conteudo) && is_int($restDto->getComponenteDigital()->getId())) {
                    $conteudo = $this->componenteDigitalResource
                        ->download($restDto->getComponenteDigital()->getId(), $transactionId)
                        ?->getConteudo();
                }

                // Se o conteúdo não estiver presente no componente digital nem no retorno do download
                if (empty($conteudo)) {
                    $this->rulesTranslate->throwException(
                        'assinatura',
                        '0006',
                        ['Não foi possível recuperar o conteúdo do componente digital. Tente novamente!']
                    );
                }

                // Se encontrou alguma assinatura inválida ou erro no processamento
                if (!$this->assinaturaHelper->isValidPAdES(base64_encode($conteudo))) {
                    $this->rulesTranslate->throwException(
                        'assinatura',
                        '0006',
                        [
                            'Existe assinatura inválida no documento! '.
                            $this->assinaturaService->getDocumentoLocation($restDto->getComponenteDigital())
                        ]
                    );
                }
            }
        } else {
            // Assinatura diferente do padrão PKCS7
            /** @var AssinaturaDTO $assinaturaDTO */
            $assinaturaDTO = $restDto;
            $aCertChain = explode('-----END CERTIFICATE-----', $assinaturaDTO->getCadeiaCertificadoPEM());
            $fisrtCert = $aCertChain[0].'-----END CERTIFICATE-----';
            $pubkeyid = openssl_pkey_get_public($fisrtCert);

            if (!$pubkeyid) {
                $this->rulesTranslate->throwException(
                    'assinatura',
                    '0006',
                    ['Não foi possível recuperar a chave pública (assinatura incompatível com o padrão PKCS7).']
                );
            }

            $algoritmo = match (mb_strtoupper($assinaturaDTO->getAlgoritmoHash())) {
                'SHA256WITHRSA' => OPENSSL_ALGO_SHA256,
                'MD5WITHRSA' => OPENSSL_ALGO_MD5,
                default => $this->rulesTranslate->throwException(
                    'assinatura',
                    '0006',
                    [
                        'O algoritmo Hash ('
                        .$assinaturaDTO->getAlgoritmoHash()
                        .' não é suportado (assinatura incompatível com o padrão PKCS7).'
                    ]
                )
            };

            // recupera o conteudo do componente digital
            $conteudo = $restDto->getComponenteDigital()->getConteudo();

            // caso o conteúdo não esteja presente no componente digital, realizar download
            if (empty($conteudo) && is_int($restDto->getComponenteDigital()->getId())) {
                $conteudo = $this->componenteDigitalResource
                    ->download($restDto->getComponenteDigital()->getId(), $transactionId)
                    ?->getConteudo();
            }

            // Se o conteúdo não estiver presente no componente digital nem no retorno do download
            if (empty($conteudo)) {
                $this->rulesTranslate->throwException(
                    'assinatura',
                    '0006',
                    ['Não foi possível recuperar o conteúdo do componente digital. Tente novamente!']
                );
            }

            $valid = openssl_verify(
                $conteudo,
                $assinatura,
                $pubkeyid,
                $algoritmo
            );

            if (0 === $valid) {
                $this->rulesTranslate->throwException(
                    'assinatura',
                    '0006',
                    ['Assinatura inválida (incompatível com o padrão PKCS7).']
                );
            } elseif (false === $valid || -1 === $valid) {
                $this->rulesTranslate->throwException(
                    'assinatura',
                    '0006',
                    ['Erro ao validar assinatura (incompatível com o padrão PKCS7).']
                );
            }
        }

        return true;
    }

    /**
     * @param string $assinatura
     *
     * @return bool
     */
    public function isPkcs7(string $assinatura): bool
    {
        $byteSignature = '30 80 06 09 2A 86 48 86 F7 0D 01 07 02';
        $hex_ary = [];
        $ok = false;
        foreach (str_split(substr($assinatura, 0, 15)) as $chr) {
            $hex_ary[] = sprintf('%02X', ord($chr));
        }
        $s = '';
        foreach ($hex_ary as $item) {
            $s .= $item;
            if ($s === $byteSignature) {
                $ok = true;
                break;
            }
            $s .= ' ';
        }

        return $ok;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return 6;
    }
}
