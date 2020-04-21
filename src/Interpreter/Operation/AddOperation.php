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

class AddOperation implements Operation
{
    /** @var mixed */
    private $value;

    /**
     * AddOperation constructor.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function processMultiValuedAttribute(array &$targets, $index = null)
    {
        if ($index !== null) {
            foreach ($this->value as $value) {
                $targets[] = $value;
            }
        } else {
            $targets[$index] = $this->value;
        }
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
        $target->{$attribute} = $this->value;
    }
}