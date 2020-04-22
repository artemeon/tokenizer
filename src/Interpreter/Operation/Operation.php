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

interface Operation
{
    /**
     * @throws ScimException
     */
    public function processArray(JsonNode $jsonNode);

    /**
     * @throws ScimException
     */
    public function processObject(JsonNode $jsonNode);
}