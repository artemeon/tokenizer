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

    /** @var string */
    public const NAME = 'add';

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

        // For multi valued operations, the patch value must be an array
        if (!is_array($this->value)) {
            throw ScimException::forInvalidValue('PatchOp:value', 'Value must be an array');
        }

        // If the target location specifies a multi-valued attribute, a new value is added.
        foreach ($this->value as $value) {
            $target[] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function processObject(JsonNode $jsonNode)
    {
        // If the target location specifies a single-valued attribute, the existing value is replaced.
        // If the target location specifies a complex attribute, a set of sub-attributes SHALL be specified.
        if ($jsonNode->targetExists()) {
            $target = &$jsonNode->getTargetValue();
            $target = is_object($this->value) ? $this->mergeComplexType($target) : $this->value;
            return;
        }

        // If the target location does not exist, the attribute and value are added.
        if ($jsonNode->hasTargetName()) {
            $target = &$jsonNode->getData();
            $target->{$jsonNode->getTargetName()} = $this->value;
            return;
        }

        // If omitted, the target location is assumed to be the resource itself.
        // The "value" parameter contains a set of attributes to be added to the resource.
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
        // If the target location specifies a complex attribute, a set of sub-attributes SHALL be specified.
        if (!is_object($this->value)) {
            throw ScimException::forInvalidValue('PatchOp:value','Complex value required');
        }

        return (object) array_merge((array) $target, (array) $this->value);
    }
}