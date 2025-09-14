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
class Round extends FunctionNode
{
    private Node|string|null $firstExpression = null;
    private Node|string|null $secondExpression = null;

    /**
     * @throws QueryException
     */
    public function parse(Parser $parser): void
    {
        $lexer = $parser->getLexer();
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->firstExpression = $parser->ArithmeticPrimary();
        // parse second parameter if available
        if(Lexer::T_COMMA === $lexer->lookahead['type']){
            $parser->match(Lexer::T_COMMA);
            $this->secondExpression = $parser->ArithmeticPrimary();
        }
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
        if ($sqlWalker->getConnection()->getDatabasePlatform() instanceof MySQLPlatform){
            // use second parameter if parsed
            if (null !== $this->secondExpression) {
                return 'ROUND('
                    .$this->firstExpression->dispatch($sqlWalker)
                    .', '
                    .$this->secondExpression->dispatch($sqlWalker)
                    .')';
            }

            return 'ROUND('.$this->firstExpression->dispatch($sqlWalker).', 2)';
        }
        elseif ($sqlWalker->getConnection()->getDatabasePlatform() instanceof OraclePlatform) {
            // use second parameter if parsed
            if (null !== $this->secondExpression) {
                return 'ROUND('
                    .$this->firstExpression->dispatch($sqlWalker)
                    .', '
                    .$this->secondExpression->dispatch($sqlWalker)
                    .')';
            }

            return 'ROUND('.$this->firstExpression->dispatch($sqlWalker).')';
        }
        else {
            throw new Exception("Banco de Dados não identificado na função getSql da classe DQL/Money.");
        }
    }
}
