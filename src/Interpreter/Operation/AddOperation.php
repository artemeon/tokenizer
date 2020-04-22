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

namespace Artemeon\Tokenizer\Interpreter\Operation;

use Artemeon\Tokenizer\Interpreter\JsonNode;
use Artemeon\Tokenizer\Interpreter\ScimException;

class AddOperation implements Operation
{
    /** @var mixed */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function processArray(JsonNode $jsonNode)
    {
        $target = &$jsonNode->getTargetValue();

        if (!is_array($this->value)) {
            throw new ScimException('Given value must be an array');
        }

        foreach ($this->value as $value) {
            $target[] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function processObject(JsonNode $jsonNode)
    {
        // Overwrite value if property already exists
        if ($jsonNode->targetExists()) {
            $target = &$jsonNode->getTargetValue();
            $target = is_object($this->value) ? $this->mergeComplexType($target) : $this->value;
            return;
        }

        // Add a new property with the given value
        if ($jsonNode->hasTargetName()) {
            $target = &$jsonNode->getData();
            $target->{$jsonNode->getTargetName()} = $this->value;
            return;
        }

        // Merge the given object properties into the root resource
        $target = &$jsonNode->getData();
        $target = $this->mergeComplexType($target);
    }

    /**
     * Merge the given object properties into the target object
     *
     * @throws ScimException
     */
    private function mergeComplexType(&$target): object
    {
        if (!is_object($this->value)) {
            throw new ScimException('Complex value required');
        }

        return (object) array_merge((array) $target, (array) $this->value);
    }
}