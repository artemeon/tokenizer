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

namespace Artemeon\Tokenizer\Interpreter;

use Exception;

class ScimException extends Exception
{
    /** @var string  */
    public $scimType = '';

    /** @var string  */
    public const NO_TARGET = 'noTarget';

    /** @var string  */
    public const INVALID_PATH = 'invalidPath';

    /** @var string  */
    public const INVALID_VALUE = 'invalidValue  ';

    public function __construct(string $message, string $scimType)
    {
        $this->scimType = $scimType;
        parent::__construct($message);
    }

    public static function forNoTarget(string $attribute): self
    {
        $message = "Missing target for attribute: $attribute";
        return new self($message, self::NO_TARGET);
    }

    public static function forInvalidPath(string $path, int $position): self
    {
        $message = "Path is invalid near character position $position: $path";
        return new self($message, self::INVALID_PATH);
    }

    public static function forInvalidValue(string $attribute, string $value): self
    {
        $message = "Invalid value: '$value' for attribute: '$attribute'";
        return new self($message, self::INVALID_VALUE);
    }

    public function getScimType(): string
    {
        return $this->scimType;
    }
}