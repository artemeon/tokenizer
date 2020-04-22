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
    public function processArrayNode(JsonNode $jsonNode)
    {
        if (!$jsonNode->propertyExists()) {
            return;
        }

        if ($jsonNode->getIndex() === null) {
            $target = &$jsonNode->getPropertyValue();
            $target = [];

            return;
        }

        $target = &$jsonNode->getData();

        unset($target[$jsonNode->getIndex()]);
    }

    /**
     * @inheritDoc
     */
    public function processObjectNode(JsonNode $jsonNode)
    {
        if (!$jsonNode->propertyExists()) {
            return;
        }

        $target = &$jsonNode->getData();
        unset($target->{$jsonNode->getPropertyName()});
    }
}