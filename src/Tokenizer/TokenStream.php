<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Tokenizer;

use Artemeon\Tokenizer\Tokenizer\Exception\UnexpectedTokenException;
use Artemeon\Tokenizer\Tokenizer\Exception\UnexpectedTokenValueException;
use Iterator;
use SplDoublyLinkedList;

/**
 * Token collection with several helper function for parser
 */
class TokenStream implements Iterator
{
    /** @var SplDoublyLinkedList */
    private $tokenList;

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
     */
    public function lookAhead($index = 1): ?Token
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
     */
    public function expectType(string $expectedType): Token
    {
        $token = $this->current();

        if ($token->getType() !== $expectedType) {
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
     */
    public function expectTypeAndValue(string $expectedType, $expectedValue): Token
    {
        $token = $this->current();

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
     * Return the current token and throws an exception if the token type
     * and value does not match the given type and one of the expected values
     *
     * @throws UnexpectedTokenException
     * @throws UnexpectedTokenValueException
     */
    public function expectTypeAndValueIsOneOf(string $expectedType, array $expectedValues): Token
    {
        $token = $this->current();

        if ($token->getType() !== $expectedType) {
            throw UnexpectedTokenException::fromToken($token);
        }

        if (!in_array($token->getValue(), $expectedValues)) {
            throw UnexpectedTokenValueException::fromToken($token);
        }

        $this->next();
        return $token;
    }

    /**
     * Checks the type of the current token
     */
    public function checkType(string $type): bool
    {
        if ($this->tokenList->current() === null) {
            return false;
        }

        return $this->current()->getType() === $type;
    }

    /**
     * Checks the type and the value of the current token
     */
    public function checkTypeAndValue(string $type, $value): bool
    {
        $current = $this->current();

        if ($current === null) {
            return false;
        }

        return $current->getType() === $type && $current->getValue();
    }

    /**
     * Checks the type and if one of the given values matches the token value
     */
    public function checkTypeAndValueIsOneOf(string $type, array $values): bool
    {
        $current = $this->current();

        if ($current === null) {
            return false;
        }

        return $current->getType() === $type && in_array($current->getValue(), $values);
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
    public function key()
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
}
