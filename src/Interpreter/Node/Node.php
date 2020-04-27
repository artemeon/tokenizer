<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Node;

interface Node
{
    /**
     * @return bool
     */
    public function targetExists(): bool;

    /**
     * @return mixed|null
     */
    public function getTarget();

    /**
     * @return mixed
     */
    public function getData();

    /**
     * @return bool
     */
    public function isArray(): bool;

    /**
     * @return string
     */
    public function getIndex(): string;

    /**
     * @return bool
     */
    public function hasIndex(): bool;
}