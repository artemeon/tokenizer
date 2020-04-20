<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\AttributeExpression;
use Artemeon\Tokenizer\Interpreter\EqualsFilterExpression;
use Artemeon\Tokenizer\Interpreter\Expression;
use Artemeon\Tokenizer\Interpreter\StringExpression;
use Artemeon\Tokenizer\Interpreter\SubAttributeExpression;
use Artemeon\Tokenizer\Interpreter\Exception\UnexpectedTokenException;
use Artemeon\Tokenizer\Interpreter\Exception\UnexpectedTokenValueException;
use Artemeon\Tokenizer\Interpreter\SyntaxTree;
use Artemeon\Tokenizer\Tokenizer\ScimGrammar;
use Artemeon\Tokenizer\Tokenizer\Token;
use Artemeon\Tokenizer\Tokenizer\TokenStream;
use Exception;

class Parser
{
    /** @var TokenStream */
    private $tokenStream;

    /** @var Expression[] */
    private $expressions = [];

    private function __construct(TokenStream $tokenStream)
    {
        $this->tokenStream = $tokenStream;
    }

    /**
     * Named constructor to create an instance based on the given TokenStream
     */
    public static function fromTokenStream(TokenStream $tokenStream): self
    {
        return new self($tokenStream);
    }

    /**
     * @return Expression[]
     * @throws UnexpectedTokenValueException
     * @throws UnexpectedTokenException
     */
    public function parse(): SyntaxTree
    {
        while ($this->tokenStream->valid()) {
            $token = $this->tokenStream->current();

            switch ($token->getType()) {
                case ScimGrammar::TYPE_ATTRIBUTE:
                    $this->expressions[] = new AttributeExpression($token->getValue());
                    $this->tokenStream->next();
                    break;
                case ScimGrammar::TYPE_SUB_ATTRIBUTE:
                    $this->expressions[] = new SubAttributeExpression($token->getValue());
                    $this->tokenStream->next();
                    break;
                case ScimGrammar::TYPE_FILTER_START:
                    $this->expressions[] = $this->parseFilter();
                    $this->tokenStream->next();
                    break;
                default:
                    $this->tokenStream->next();
            }
        }

        return SyntaxTree::fromArray($this->expressions);
    }

    /**
     * @throws UnexpectedTokenException
     * @throws UnexpectedTokenValueException
     * @throws Exception
     */
    private function parseFilter(): Expression
    {
        $this->tokenStream->next();

        $attributeToken = $this->tokenStream->expectType(ScimGrammar::TYPE_ATTRIBUTE);
        $operatorToken = $this->tokenStream->expectTypeAndValue(ScimGrammar::TYPE_OPERATOR_EQUALS, 'eq');
        $valueToken = $this->tokenStream->expectType(ScimGrammar::TYPE_STRING);
        $valueExpression = new StringExpression($valueToken->getValue());

        return $this->parseOperatorToken($operatorToken, $attributeToken, $valueExpression);
    }

    /**
     * @param Token $operatorToken
     * @param Expression $attributeToken
     * @param Expression $valueExpression
     * @throws Exception
     */
    private function parseOperatorToken(
        Token $operatorToken,
        Token $attributeToken,
        Expression $valueExpression
    ): Expression {
        switch ($operatorToken->getType()) {
            case ScimGrammar::TYPE_OPERATOR_EQUALS:
                return new EqualsFilterExpression($attributeToken->getValue(), $valueExpression);
            default:
                throw new Exception('Not supportet Operator');
        }
    }
}
