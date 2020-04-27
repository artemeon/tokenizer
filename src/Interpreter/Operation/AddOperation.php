<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Operation;

use Artemeon\Tokenizer\Interpreter\Node\Node;
use Artemeon\Tokenizer\Interpreter\ScimException;
use stdClass;

class AddOperation extends Operation
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
    protected function processArray(Node $jsonNode): void
    {
        $target = &$jsonNode->getTarget();

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
    protected function processObject(Node $jsonNode): void
    {
        // If the target location specifies a single-valued attribute, the existing value is replaced.
        // If the target location specifies a complex attribute, a set of sub-attributes SHALL be specified.
        if ($jsonNode->targetExists()) {
            $target = &$jsonNode->getTarget();
            $target = is_object($this->value) ? $this->mergeComplexType($target) : $this->value;
            return;
        }

        // If the target location does not exist, the attribute and value are added.
        if ($jsonNode->hasIndex()) {
            $target = &$jsonNode->getData();
            $target->{$jsonNode->getIndex()} = $this->value;
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
    private function mergeComplexType(stdClass &$target): stdClass
    {
        // If the target location specifies a complex attribute, a set of sub-attributes SHALL be specified.
        if (!is_object($this->value)) {
            throw ScimException::forInvalidValue('PatchOp:value', 'Complex value required');
        }

        return (object) array_merge((array) $target, (array) $this->value);
    }
}