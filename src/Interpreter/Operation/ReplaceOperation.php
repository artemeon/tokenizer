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

class ReplaceOperation implements Operation
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
        // Replace existing array entry
        if ($jsonNode->targetExists()) {
            $target = &$jsonNode->getTargetValue();
            $target = $this->value;
            return;
        }

        // Add a new entry
        $target = &$jsonNode->getData();
        $target[] = $this->value;
    }

    /**
     * @inheritDoc
     */
    public function processObject(JsonNode $jsonNode)
    {
        // Replace existing property
        if ($jsonNode->targetExists()) {
            $target = &$jsonNode->getTargetValue();
            $target = is_object($target) ? $this->mergeComplexType($target) : $this->value;
            return;
        }

        // Add new properties
        $target = &$jsonNode->getData();
        $target->{$jsonNode->getTargetName()} = is_object($target) ? $this->mergeComplexType($target) : $this->value;
    }

    /**
     * Merge the given object properties into the target object
     *
     * @throws ScimException
     */
    private function &mergeComplexType(&$target): object
    {
        if (!is_object($this->value)) {
            throw new ScimException('Complex value required');
        }

        return (object) array_merge((array) $target, (array) $this->value);
    }
}