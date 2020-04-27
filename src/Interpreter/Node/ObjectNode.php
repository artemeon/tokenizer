<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Node;

use stdClass;

class ObjectNode implements Node
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
        if ($this->property === '') {
            return null;
        }

        return $this->data->{$this->property};
    }

    /**
     * @inheritDoc
     */
    public function &getData()
    {
        return $this->data;
    }

    public function getIndex(): string
    {
        return $this->property;
    }

    /**
     * @inheritDoc
     */
    public function isArray(): bool
    {
        if (!$this->targetExists()) {
            return false;
        }

        return is_array($this->getTarget());
    }
}