<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use Artemeon\Tokenizer\Interpreter\Operation\AddOperation;
use Artemeon\Tokenizer\Interpreter\Operation\RemoveOperation;
use Artemeon\Tokenizer\Interpreter\Operation\ReplaceOperation;

/**
 * DTO to execute scim patch request
 */
class ScimPatch
{
    /** @var string */
    private $op;

    /** @var string */
    private $path;

    /** @var mixed */
    private $value;

    public function __construct(string $op, string $path, $value)
    {
        $this->op = $op;
        $this->path = $path;
        $this->value = $value;
    }

    /**
     * Named constructor to create an instance for add operations
     *
     * @param mixed $value
     */
    public static function forAdd(string $path, $value)
    {
        return new self(AddOperation::NAME, $path, $value);
    }

    /**
     * Named constructor to create an instance for replace operations
     *
     * @param mixed $value
     */
    public static function forReplace(string $path, $value)
    {
        return new self(ReplaceOperation::NAME, $path, $value);
    }

    /**
     * Named constructor to create an instance for remove operations
     */
    public static function forRemove(string $path)
    {
        return new self(RemoveOperation::NAME, $path, null);
    }

    public function getOp(): string
    {
        return $this->op;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function hasPath(): bool
    {
        return $this->path !== '';
    }
}