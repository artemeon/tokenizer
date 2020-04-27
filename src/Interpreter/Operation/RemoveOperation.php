<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Operation;

use Artemeon\Tokenizer\Interpreter\Node\Node;
use Artemeon\Tokenizer\Interpreter\ScimException;

class RemoveOperation extends Operation
{
    /** @var string */
    public const NAME = 'remove';

    /**
     * @inheritDoc
     */
    protected function processArray(Node $jsonNode): void
    {
        if (!$jsonNode->targetExists()) {
            return;
        }

        // If the target location is a multi-valued attribute and no filter is specified,
        // the attribute and all values are removed
        if ($jsonNode->getIndex() === null) {
            $target = &$jsonNode->getTarget();
            $target = [];

            return;
        }

        // If the target location is a multi-valued attribute and a complex filter is specified
        // comparing a "value", the values matched by the filter are removed.
        $target = &$jsonNode->getData();

        if (!is_array($target)) {
            throw ScimException::forInvalidValue('PatchOp:value', 'Target must be an array');
        }

        unset($target[$jsonNode->getIndex()]);
    }

    /**
     * @inheritDoc
     */
    protected function processObject(Node $jsonNode): void
    {
        if (!$jsonNode->targetExists()) {
            return;
        }

        // If the target location is a single-value attribute, the attribute and its
        // associated value is removed
        $target = &$jsonNode->getData();

        unset($target->{$jsonNode->getIndex()});
    }
}