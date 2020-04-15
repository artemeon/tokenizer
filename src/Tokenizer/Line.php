<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Tokenizer;

class Line
{
    /** @var string */
    private $line;

    /** @var int */
    private $length;

    /** @var string */
    private $match;

    /** @var int */
    private $number;

    /** @var int */
    private $position = 0;

    /** @var int */
    private $offset = 0;

    private function __construct(string $line, $number)
    {
        $this->line = $line;
        $this->number = $number;
        $this->length = strlen($line);
    }

    public static function fromString(string $line, int $number): Line
    {
        return new self($line, $number);
    }

    public function matchPattern(string $pattern): bool
    {
        $this->match = '';

        if (preg_match($pattern, $this->line, $matches) !== 1) {
            return false;
        }

        $this->match = $matches[0];
        $this->line = substr($this->line, strlen($matches[0]));
        $this->length = strlen($this->line);
        $this->position += $this->offset;
        $this->offset = strlen($matches[0]);

        return true;
    }

    public function getMatch(): string
    {
        return $this->match;
    }

    public function isResolved(): bool
    {
        return $this->length === 0;
    }

    public function resolve(): void
    {
        $this->length = 0;
    }

    public function getUnmatched(): string
    {
        return $this->line;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
}
