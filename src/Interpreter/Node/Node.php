<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Node;

use stdClass;

/**
 * Interface for scim path result nodes found by the interpreter
 *
 * Each result node should contain the raw node data and a reference (pointer)
 * to the targeted data
 */
interface Node
{
    /**
     * Checks if the index to the target data cant be resolved
     */
    public function targetExists(): bool;

    /**
     * Return the resolved target data or null on failure
     *
     * @return mixed|null
     */
    public function getTarget();

    /**
     * Returns the unresolved node data
     *
     * @return array|stdClass
     */
    public function getData();

    /**
     * Checks if the resolved target data is a array
     *
     * @return string
     */
    public function getDataType(): string;

    /**
     * Return the index (pointer) to the target data
     */
    public function getIndex(): string;

    /**
     * Checks is the index (pointer) to the target data is set
     */
    public function hasIndex(): bool;
}