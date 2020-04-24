<?php

/*
 * This file is part of the Artemeon Core - Web Application Framework.
 *
 * (c) Artemeon <www.artemeon.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use stdClass;

class JsonNode
{
    /** @var mixed */
    private $data;

    /** @var string */
    private $targetName;

    /** @var mixed */
    private $index;

    private function __construct(&$data, string $propertyName, $index)
    {
        $this->data = &$data;
        $this->targetName = $propertyName;
        $this->index = $index;
    }

    /**
     * Named constructor to create an instance based on the given array
     *
     * @param int|string $index
     */
    public static function fromArray(array &$data, $index = null): self
    {
        return new self($data, '', $index ?? max(array_keys($data)) + 1);
    }

    /**
     * Named constructor to create an instance based on the given stdClass
     */
    public static function fromObject(stdClass &$data, string $propertyName): self
    {
        return new self($data, $propertyName, null);
    }

    /**
     * @return bool
     */
    public function targetExists(): bool
    {
        if ($this->targetName === '' && $this->index === null) {
            return false;
        }

        if ($this->targetName !== '') {
            return property_exists($this->data, $this->targetName);
        }

        return isset($this->data[$this->index]);
    }

    public function hasTargetName(): bool
    {
        return $this->targetName !== '';
    }

    /**
     * @return mixed|null
     */
    public function &getTargetValue()
    {
        if ($this->targetName !== '') {
            return $this->data->{$this->targetName};
        }

        return $this->data[$this->index];
    }

    /**
     * @return mixed
     */
    public function &getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getTargetName(): string
    {
        return $this->targetName;
    }

    /**
     * @return int|string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return bool
     */
    public function isArray(): bool
    {
        if (is_array($this->data)) {
            return true;
        };

        if (property_exists($this->data, $this->targetName)) {
            return is_array($this->getTargetValue());
        }

        return false;
    }
}