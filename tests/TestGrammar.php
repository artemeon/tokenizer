<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Tests;

use Artemeon\Tokenizer\Grammar;

/**
 * Class to register all supported scim patch grammar tokens
 *
 * @since 7.2
 */
class TestGrammar extends Grammar
{
    // Registered grammar tokens
    public const TYPE_OPERATOR_EQUALS = "OPERATOR_EQUALS";
    public const TYPE_OPERATOR_AND = "OPERATOR_AND";
    public const TYPE_ATTRIBUTE = "ATTRIBUTE";
    public const TYPE_STRING = "STRING";
    public const TYPE_NUMERIC = "NUMERIC";
    public const TYPE_BOOLEAN = "BOOLEAN";
    public const TYPE_WHITESPACE = "WHITESPACE";

    protected function registerPattern(): array
    {
        return [
            self::TYPE_OPERATOR_EQUALS => '/^\beq\b/i',
            self::TYPE_OPERATOR_AND    => '/^\band\b/i',
            self::TYPE_BOOLEAN         => '/^\b(false|true)\b/',
            self::TYPE_STRING          => '/^"([^"]+)"/',
            self::TYPE_NUMERIC         => '/^[+-]?\d+/',
            self::TYPE_ATTRIBUTE       => '/^\b([a-zA-Z_-]|\d|)+\b/',
            self::TYPE_WHITESPACE      => '/^ {1}/',
        ];
    }

    protected function registerIgnoredTokenNames(): array
    {
        return [];
    }
}
