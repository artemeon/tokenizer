<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Node;

use Artemeon\Tokenizer\Interpreter\Operation\Operation;
use stdClass;

class StdClassNode implements Node
{
    /** @var mixed */
    private $data;

    /** @var string */
    private $property;

    private function __construct(&$data, string $property)
    {
        $this->data = &$data;
        $this->property = $property;
    }

    /**
     * Named constructor to create an instance based on the given stdClass
     */
    public static function fromObject(stdClass &$data, string $propertyName): self
    {
        return new self($data, $propertyName);
    }

    /**
     * @inheritDoc
     */
    public function targetExists(): bool
    {
        if ($this->property === '') {
            return false;
        }

        return property_exists($this->data, $this->property);
    }

    /**
     * @return bool
     */
    public function hasIndex(): bool
    {
        return $this->property !== '';
    }

    /**
     * @inheritDoc
     */
    public function &getTarget()
    {
        if (!$this->hasIndex()) {
            return null;
        }

        return $this->data->{$this->property};
    }

    /**
     * @inheritDoc
     */
    public function &getData(): stdClass
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getIndex(): string
    {
        return $this->property;
    }

    /**
     * @inheritDoc
     */
    public function getDataType(): string
    {
        // If no reference to the target data exist we use stdClass
        if (!$this->targetExists()) {
            return Operation::TYPE_STD_CLASS;
        }

        // Resolve type of target data and determine operation type
        return is_array($this->getTarget()) ? Operation::TYPE_ARRAY : Operation::TYPE_STD_CLASS;
    }
}