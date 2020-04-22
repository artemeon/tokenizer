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

class RemoveOperation implements Operation
{
    /**
     * @inheritDoc
     */
    public function processArray(JsonNode $jsonNode)
    {
        if (!$jsonNode->targetExists()) {
            return;
        }

        if ($jsonNode->getIndex() === null) {
            $target = &$jsonNode->getTargetValue();
            $target = [];

            return;
        }

        $target = &$jsonNode->getData();

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

        $target = &$jsonNode->getData();
        unset($target->{$jsonNode->getTargetName()});
    }
}