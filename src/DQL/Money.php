<?php
declare(strict_types=1);

#codificação UTF-8

namespace SuppCore\AdministrativoBackend\DQL;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\OraclePlatform;
use Doctrine\ORM\Query\AST\ASTException;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Exception;

/**
 *
 */
class Money extends FunctionNode
{
    private Node|string|null $firstExpression = null;

    /**
     * @throws QueryException
     */
    public function parse(Parser $parser): void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->firstExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param SqlWalker $sqlWalker
     * @return string
     * @throws ASTException
     * @throws Exception
     */
    public function getSql(SqlWalker $sqlWalker): string
    {
        if ($sqlWalker->getConnection()->getDatabasePlatform() instanceof MySQLPlatform) {
            return "FORMAT(".
                $this->firstExpression->dispatch($sqlWalker).
                ", 2, 'pt_BR')";
        }
        elseif ($sqlWalker->getConnection()->getDatabasePlatform() instanceof OraclePlatform) {
            return "TO_CHAR(".
                $this->firstExpression->dispatch($sqlWalker).
                ",'FM999G999G999G999D90', 'nls_numeric_characters='',.''')";
        }
        else {
            throw new Exception("Banco de Dados não identificado na função getSql da classe DQL/Money.");
        }
    }
}
