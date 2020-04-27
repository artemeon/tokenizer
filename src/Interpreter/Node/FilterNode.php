<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Node;

class FilterNode implements Node
{
    /** @var mixed */
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
        return $this->data[$this->index];
    }

    /**
     * @inheritDoc
     */

    public function &getData()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function isArray(): bool
    {
        return true;
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