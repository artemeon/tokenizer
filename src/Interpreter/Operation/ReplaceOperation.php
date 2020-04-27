<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Operation;

use Artemeon\Tokenizer\Interpreter\Node\Node;
use Artemeon\Tokenizer\Interpreter\ScimException;

class ReplaceOperation implements Operation
{
    /** @var mixed */
    private $value;

    /** @var string */
    public const NAME = 'replace';

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function processArray(Node $jsonNode)
    {
        // If the target location is a multi-valued attribute and no filter is specified, the attribute
        // and all values are replaced. If the target location is a multi-valued attribute and a value
        // selection ("valuePath") filter is specified that matches one or more values of the multi-valued
        // attribute, then all matching record values SHALL be replaced.
        // If the target location path specifies an attribute that does not exist,
        // the service provider SHALL treat the operation as an "add".
        $target = &$jsonNode->getTarget();
        $target = is_object($target) ? $this->mergeComplexType($target) : $this->value;
        return;
    }

    /**
     * @inheritDoc
     */
    public function processObject(Node $jsonNode)
    {
        // If the target location is a single-value attribute, the attributes value is replaced.
        // If the target location specifies a complex attribute, a set of sub-attributes SHALL be specified.
        if ($jsonNode->targetExists()) {
            $target = &$jsonNode->getTarget();
            $target = is_object($target) ? $this->mergeComplexType($target) : $this->value;
            return;
        }

        // If the target location path specifies an attribute that does not exist,
        // the service provider SHALL treat the operation as an "add".
        $target = &$jsonNode->getData();
        $value = is_object($target) ? $this->mergeComplexType($target) : $this->value;
        $target->{$jsonNode->getIndex()} = $value;
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
            throw ScimException::forInvalidValue('PatchOp:value', 'Complex value required');
        }

        return (object) array_merge((array) $target, (array) $this->value);
    }
}