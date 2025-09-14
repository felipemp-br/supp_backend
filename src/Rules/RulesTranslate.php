<?php

declare(strict_types=1);
/**
 * /src/Rules/RulesManager.php.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */

namespace SuppCore\AdministrativoBackend\Rules;

use LogicException;
use SuppCore\AdministrativoBackend\Rules\Exceptions\RuleException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use function vsprintf;

/**
 * Class RulesManager.
 *
 * @author Advocacia-Geral da União <supp@agu.gov.br>
 */
class RulesTranslate
{
    protected array $rulesConfig = [];

    /**
     * @return array
     */
    public function getRulesConfig(): array
    {
        return $this->rulesConfig;
    }

    /**
     * @param array $rulesConfig
     */
    public function setRulesConfig(array $rulesConfig): void
    {
        $this->rulesConfig = $rulesConfig;
    }

    /**
     * RulesManager constructor.
     *
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->rulesConfig = $params->get('rules');
    }

    /**
     * @param string|null       $domain
     * @param string|null       $codeError
     * @param array|null        $params
     *
     * @throws RuleException
     */
    public function throwException(?string $domain, ?string $codeError, ?array $params = null): void
    {
        $e = new RuleException($this->translate($domain, $codeError, $params));
        $e->setDomain($domain);
        $e->setErrorCode($codeError);
        throw $e;
    }

    /**
     * @param string|null $domain
     * @param string|null $codeError
     * @param array|null $params
     * @return string
     */
    public function translate(?string $domain, ?string $codeError, ?array $params = null): string
    {
        if (!array_key_exists($domain, $this->rulesConfig['messages'])) {
            throw new LogicException('Domínio '.$domain.' não encontrado na tabela de erros!');
        }

        if (!array_key_exists($codeError, $this->rulesConfig['messages'][$domain])) {
            throw new LogicException('Código de Erro '.$codeError.' não encontrado no Domínio '.$domain.'!');
        }
        // traduz o código de erro
        $message = $this->rulesConfig['messages'][$domain][$codeError];

        if ($params) {
            $message = vsprintf($message, $params);
        }

        return $message;
    }
}
