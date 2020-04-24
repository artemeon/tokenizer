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

class RemoveOperation implements Operation
{
    /** @var string */
    public const NAME = 'remove';

    /**
     * @inheritDoc
     */
    public function processArray(JsonNode $jsonNode)
    {
        if (!$jsonNode->targetExists()) {
            return;
        }

        // If the target location is a multi-valued attribute and no filter is specified,
        // the attribute and all values are removed
        if ($jsonNode->getIndex() === null) {
            $target = &$jsonNode->getTargetValue();
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
    public function processObject(JsonNode $jsonNode)
    {
        if (!$jsonNode->targetExists()) {
            return;
        }

        // If the target location is a single-value attribute, the attribute and its
        // associated value is removed
        $target = &$jsonNode->getData();

        unset($target->{$jsonNode->getTargetName()});
    }
}