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

class RemoveOperation implements Operation
{
    /**
     * @inheritDoc
     */
    public function processMultiValuedAttribute(array &$targets, $index = null)
    {
        if ($index === null) {
            $targets = [];
        } else {
            unset($targets[$index]);
        }
    }

    /**
     * @inheritDoc
     */
    public function processSingleValuedAttribute(&$target)
    {
        $target = null;
    }

    /**
     * @inheritDoc
     */
    public function processComplexAttribute(string $attribute, stdClass $target)
    {
        unset($target->{$attribute});
    }
}