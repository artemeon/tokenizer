<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use Artemeon\Tokenizer\Tokenizer\Grammar;

/**
 * Class to register all supported scim grammar tokens
 */
class ScimGrammar extends Grammar
{
    // Registered grammar tokens
    public const TYPE_OPERATOR_EQUALS = "OPERATOR_EQUALS";
    public const TYPE_ATTRIBUTE       = "ATTRIBUTE";
    public const TYPE_SUB_ATTRIBUTE   = "SUB_ATTRIBUTE";
    public const TYPE_STRING          = "STRING";
    public const TYPE_NUMERIC         = "NUMERIC";
    public const TYPE_FILTER_START    = "FILTER_START";
    public const TYPE_FILTER_END      = "FILTER_END";
    public const TYPE_WHITESPACE      = "WHITESPACE";
    public const TYPE_NEWLINE         = "NEWLINE";

    // Allowed data type tokes for filter expressions
    public const ARR_ALLOWED_FILTER_VALUES = [
        self::TYPE_NUMERIC,
        self::TYPE_STRING
    ];

    /**
     * @inheritDoc
     */
    protected function registerPattern(): array
    {
        return [
            self::TYPE_OPERATOR_EQUALS => '/^\beq\b/',
            self::TYPE_ATTRIBUTE       => '/^\b[a-zA-Z]+\b/',
            self::TYPE_SUB_ATTRIBUTE   => '/^\.\w+/',
            self::TYPE_STRING          => '/^"([^"]+)"/',
            self::TYPE_NUMERIC         => '/^[+-]?\d+/',
            self::TYPE_FILTER_START    => '/^\[{1}/',
            self::TYPE_FILTER_END      => '/^\]{1}/',
            self::TYPE_WHITESPACE      => '/^ {1}/',
            self::TYPE_NEWLINE         => '/^\r\n/',
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
