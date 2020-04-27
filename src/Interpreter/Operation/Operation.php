<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Operation;

use Artemeon\Tokenizer\Interpreter\Node\Node;
use Artemeon\Tokenizer\Interpreter\ScimException;

interface Operation
{
    /**
     * @param Node $jsonNode
     * @throws ScimException
     */
    public function processArray(Node $jsonNode);

    /**
     * @param Node $jsonNode
     * @throws ScimException
     */
    public function processObject(Node $jsonNode);
}