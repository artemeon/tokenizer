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
        if ($jsonNode->targetExists()) {
            $target = &$jsonNode->getTargetValue();
            $target = $this->value;
            return;
        }

        $target = &$jsonNode->getData();
        $target[] = $this->value;
    }

    /**
     * @inheritDoc
     */
    public function processObject(JsonNode $jsonNode)
    {
        if ($jsonNode->targetExists()) {
            $target = &$jsonNode->getTargetValue();
            $target = $this->value;
            return;
        }

        $target = &$jsonNode->getData();
        $target->{$jsonNode->getTargetName()} = (object) array_merge((array) $target, (array) $this->value);
    }
}