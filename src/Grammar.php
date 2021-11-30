<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer;

use ArrayIterator;
use IteratorAggregate;

/**
 * Abstract base class for all individual grammar definitions. Use the abstract methods to configure your
 * own parser grammar syntax.
 */
abstract class Grammar implements IteratorAggregate
{
    /** @var string */
    public const UNMATCHED_KEY = 'UNMATCHED';

    /** @var string[] */
    protected array $pattern = [];

    /** @var string[] */
    protected array $ignoredTokenNames = [];

    public function __construct()
    {
        $this->pattern           = $this->registerPattern();
        $this->ignoredTokenNames = array_flip($this->registerIgnoredTokenNames());
    }

    /**
     * Checks if th the the token with the given name should be ignored
     */
    public function isTokenIgnored(string $tokenName): bool
    {
        return isset($this->ignoredTokenNames[$tokenName]);
    }

    /**
     * Must return the array with the pattern name as the key and the regular expression as the value
     *
     * @return string[] ["OPERATOR_EQUALS" => '/^\beq\b/']
     */
    abstract protected function registerPattern(): array;

    /**
     * Must return the array with all token names which should be ignored
     *
     * @return string[] ['WHITESPACE']
     */
    abstract protected function registerIgnoredTokenNames(): array;

    /**
     * @inheritDoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->pattern);
    }
}
