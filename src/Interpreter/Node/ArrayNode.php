<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Node;

use Artemeon\Tokenizer\Interpreter\Operation\Operation;

/**
 * Class for filtered results.
 *
 * Contains the unfiltered data (array) an a reference (index) to the data
 * targeted by the filter
 */
class ArrayNode implements Node
{
    /** @var array */
    private $data;

    /** @var mixed */
    private $index;

    private function __construct(array &$data, int $index)
    {
        $this->data = &$data;
        $this->index = $index;
    }

    /**
     * Named constructor to create an instance based on the given array
     */
    public static function fromArray(array &$data, int $index): self
    {
        return new self($data, $index);
    }

    /**
     * @inheritDoc
     */
    public function targetExists(): bool
    {
        return isset($this->data[$this->index]);
    }

    /**
     * @inheritDoc
     */
    public function &getTarget()
    {
        if (!$this->targetExists()) {
            return null;
        }

        return $this->data[$this->index];
    }

    /**
     * @inheritDoc
     */

    public function &getData(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getDataType(): string
    {
        return Operation::TYPE_ARRAY;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(): string
    {
        return (string) $this->index;
    }

    /**
     * @inheritDoc
     */
    public function hasIndex(): bool
    {
        return $this->index !== null;
    }
}