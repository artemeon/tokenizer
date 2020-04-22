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

    /** @var string  */
    private $propertyName;

    /** @var mixed */
    private $index;

    private function __construct(&$data, string $propertyName, $index)
    {
        $this->data = &$data;
        $this->propertyName = $propertyName;
        $this->index = $index;
    }

    public static function fromArray(array &$data, $index = null): self
    {
        return new self($data, '',  $index ?? max(array_keys($data)) + 1);
    }

    public static function fromObject(stdClass &$data, string $propertyName): self
    {
        return new self($data, $propertyName, null);
    }

    /**
     * @return bool
     */
    public function propertyExists(): bool
    {
        if ($this->propertyName !== '') {
            return property_exists($this->data, $this->propertyName);
        }

        return isset($this->data[$this->index]);
    }

    /**
     * @return mixed|null
     */
    public function &getPropertyValue()
    {
        if ($this->propertyName !== '') {
            return $this->data->{$this->propertyName};
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
    public function getPropertyName(): string
    {
        return $this->propertyName;
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
    public function isArrayNode(): bool
    {
        if (is_array($this->getPropertyValue())) {
            return true;
        }

        return is_array($this->data);
    }
}