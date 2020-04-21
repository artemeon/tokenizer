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

use stdClass;

interface Operation
{
    public function processMultiValuedAttribute(array &$targets, $index = null);

    public function processSingleValuedAttribute(&$target);

    public function processComplexAttribute(string $attribute, stdClass $target);
}