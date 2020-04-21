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
    public function processMultiValuedAttribute(array &$targets, $index = null)
    {
        $targets = $this->value;
    }

    /**
     * @inheritDoc
     */
    public function processSingleValuedAttribute(&$target)
    {
        $target = $this->value;
    }

    /**
     * @inheritDoc
     */
    public function processComplexAttribute(string $attribute, stdClass $target)
    {
        $target = (object) array_merge((array) $target, (array) $this->value);
    }
}