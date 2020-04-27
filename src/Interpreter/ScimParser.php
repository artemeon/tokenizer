<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use Artemeon\Tokenizer\Interpreter\Expression\AttributeExpression;
use Artemeon\Tokenizer\Interpreter\Expression\EqualsFilterExpression;
use Artemeon\Tokenizer\Interpreter\Expression\Expression;
use Artemeon\Tokenizer\Interpreter\Expression\StringExpression;
use Artemeon\Tokenizer\Interpreter\Expression\SubAttributeExpression;
use Artemeon\Tokenizer\Tokenizer\Exception\UnexpectedTokenException;
use Artemeon\Tokenizer\Tokenizer\Exception\UnexpectedTokenValueException;
use Artemeon\Tokenizer\Tokenizer\Token;
use Artemeon\Tokenizer\Tokenizer\TokenStream;

/**
 * ScimParser to parse the given TokenStream and translate it to a abstract syntax tree
 */
class ScimParser
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
     * Parse the given TokenStream
     *
     * @throws UnexpectedTokenValueException
     * @throws UnexpectedTokenException
     * @throws ScimException
     */
    public function parse(): ScimSyntaxTree
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
                    UnexpectedTokenException::fromToken($token);
            }
        }

        return ScimSyntaxTree::fromArray($this->expressions);
    }

    /**
     * Parse the filter token sequence
     *
     * @throws UnexpectedTokenException
     * @throws UnexpectedTokenValueException
     * @throws ScimException
     */
    private function parseFilter(): Expression
    {
        $this->tokenStream->next();

        $attributeToken = $this->tokenStream->expectType(ScimGrammar::TYPE_ATTRIBUTE);
        $operatorToken = $this->tokenStream->expectTypeAndValue(ScimGrammar::TYPE_OPERATOR_EQUALS, 'eq');
        $valueToken = $this->tokenStream->expectTypeIsOneOf(ScimGrammar::ARR_ALLOWED_FILTER_VALUES);
        $valueExpression = new StringExpression($valueToken->getValue());

        return $this->parseOperatorToken($operatorToken, $attributeToken, $valueExpression);
    }

    /**
     * Parse operator tokens
     *
     * @throws ScimException
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
                throw ScimException::forInvalidValue($attributeToken->getValue(), 'Operator not supported');
        }
    }
}
