<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer;

use Artemeon\Tokenizer\Exception\UnexpectedEndOfTokenStreamException;
use Artemeon\Tokenizer\Exception\UnexpectedTokenException;
use Artemeon\Tokenizer\Exception\UnexpectedTokenValueException;
use Iterator;
use SplDoublyLinkedList;

/**
 * Token collection with several helper function for parser
 */
class TokenStream implements Iterator
{
    private SplDoublyLinkedList $tokenList;

    public function __construct()
    {
        $this->tokenList = new SplDoublyLinkedList();
    }

    /**
     * Named constructor to create an instance based on the given array
     *
     * @param $tokens Token[]
     */
    public static function fromArray(array $tokens): self
    {
        $instance = new self();

        foreach ($tokens as $key => $token) {
            $instance->addToken($key, $token);
        }

        $instance->rewind();

        return $instance;
    }

    /**
     * Looks ahead from the current positions and returns the token
     * without modifying the index
     */
    public function lookAhead(int $index = 1): ?Token
    {
        $index = $this->tokenList->key() + $index;

        if (!$this->tokenList->offsetExists($index)) {
            return null;
        }

        return $this->tokenList->offsetGet($index);
    }

    /**
     * Return the current token and throws an exception if the type
     * does not match the given type
     *
     * @throws UnexpectedTokenException
     * @throws UnexpectedEndOfTokenStreamException
     */
    public function expectType(string $expectedType): Token
    {
        $token = $this->getCurrentToken();

        if (!$this->checkType($expectedType)) {
            throw UnexpectedTokenException::fromToken($token);
        }

        $this->next();
        return $token;
    }

    /**
     * Return the current token and throws an exception if the token type
     * and value does not match the given type and value
     *
     * @throws UnexpectedTokenException
     * @throws UnexpectedTokenValueException
     * @throws UnexpectedEndOfTokenStreamException
     */
    public function expectTypeAndValue(string $expectedType, mixed $expectedValue): Token
    {
        $token = $this->getCurrentToken();

        if ($token->getType() !== $expectedType) {
            throw UnexpectedTokenException::fromToken($token);
        }

        if ($token->getValue() !== $expectedValue) {
            throw UnexpectedTokenValueException::fromToken($token);
        }

        $this->next();
        return $token;
    }

    /**
     * Returns the current token and throws an exception if the token type
     * does not match on of the given expected types
     *
     * @param string[] $expectedTypes
     * @throws UnexpectedTokenException
     * @throws UnexpectedEndOfTokenStreamException
     */
    public function expectTypeIsOneOf(array $expectedTypes): Token
    {
        $token = $this->getCurrentToken();

        if (!in_array($token->getType(), $expectedTypes)) {
            throw UnexpectedTokenException::fromToken($token);
        }

        $this->next();
        return $token;
    }

    /**
     * Checks the type of the current token
     */
    public function checkType(string $type): bool
    {
        $token = $this->current();

        if ($token === null) {
            return false;
        }

        return $token->getType() === $type;
    }

    /**
     * Checks the type and the value of the current token
     */
    public function checkTypeAndValue(string $type, mixed $value): bool
    {
        $token = $this->current();

        if ($token === null) {
            return false;
        }

        return $token->getType() === $type && $token->getValue() === $value;
    }

    /**
     * Checks the type and if one of the given values matches the token value
     *
     * @param string[] $types
     */
    public function checkTypeIsOneOf(array $types): bool
    {
        $token = $this->current();

        if ($token === null) {
            return false;
        }

        return in_array($token->getType(), $types);
    }

    /**
     * Move pointer to the next token
     */
    public function next(): void
    {
        $this->tokenList->next();
    }

    /**
     * Returns the current Token
     */
    public function current(): ?Token
    {
        return $this->tokenList->current();
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return $this->tokenList->valid();
    }

    /**
     * @inheritDoc
     */
    public function key(): mixed
    {
        return $this->tokenList->key();
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->tokenList->rewind();
    }

    /**
     * Add a token to the list
     */
    private function addToken(int $index, Token $token)
    {
        $this->tokenList->add($index, $token);
    }

    /**
     * @throws UnexpectedEndOfTokenStreamException
     */
    private function getCurrentToken(): Token
    {
        $token = $this->current();

        if (!$token instanceof Token) {
            throw UnexpectedEndOfTokenStreamException::create();
        }

        return $token;
    }
}
