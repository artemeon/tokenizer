<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Tokenizer;

class ScimGrammar extends Grammar
{
    public const TYPE_OPERATOR_EQUALS           = "OPERATOR_EQUALS";
    public const TYPE_OPERATOR_PRESENT          = "OPERATOR_PRESENT";
    public const TYPE_OPERATOR_GREATER_OR_EQUAL = "OPERATOR_GREATER_OR_EQUAL";
    public const TYPE_OPERATOR_LESS_THAN        = "OPERATOR_LESS_THAN";
    public const TYPE_ATTRIBUTE                 = "ATTRIBUTE";
    public const TYPE_SUB_ATTRIBUTE             = "SUB_ATTRIBUTE";
    public const TYPE_STRING                    = "STRING";
    public const TYPE_NUMERIC                   = "NUMERIC";
    public const TYPE_FILTER_START              = "FILTER_START";
    public const TYPE_FILTER_END                = "FILTER_END";
    public const TYPE_WHITESPACE                = "WHITESPACE";
    public const TYPE_NEWLINE                   = "NEWLINE";

    /**
     * @inheritDoc
     */
    protected function registerPattern(): array
    {
        return [
            self::TYPE_OPERATOR_EQUALS           => '/^\beq\b/',
            self::TYPE_OPERATOR_PRESENT          => '/^\bpr\b/',
            self::TYPE_OPERATOR_GREATER_OR_EQUAL => '/^\bge\b/',
            self::TYPE_OPERATOR_LESS_THAN        => '/^\blt\b/',
            self::TYPE_ATTRIBUTE                 => '/^\b[a-zA-Z]+\b/',
            self::TYPE_SUB_ATTRIBUTE             => '/^\.\w+/',
            self::TYPE_STRING                    => '/^"([^"]+)"/',
            self::TYPE_NUMERIC                   => '/^[+-]?\d+/',
            self::TYPE_FILTER_START              => '/^\[{1}/',
            self::TYPE_FILTER_END                => '/^\]{1}/',
            self::TYPE_WHITESPACE                => '/^ {1}/',
            self::TYPE_NEWLINE                   => '/^\r\n/',
        ];
    }

    /**
     * @inheritDoc
     */
    protected function registerIgnoredTokenNames(): array
    {
        return [
            self::TYPE_WHITESPACE,
        ];
    }
}
