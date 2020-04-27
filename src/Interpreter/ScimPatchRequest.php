<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

class ScimPatchRequest
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

    public static function forAdd(string $path, $value)
    {
        return new self('add', $path, $value);
    }

    public static function forReplace(string $path, $value)
    {
        return new self('replace', $path, $value);
    }

    public static function forRemove($path)
    {
        return new self('remove', $path, '');
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