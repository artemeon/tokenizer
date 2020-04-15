<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Tokenizer;

use SplFileObject;

class Lexer
{
    /** @var Grammar */
    private $grammar;

    /** @var Token[] */
    private $parsedTokens = [];

    private function __construct(Grammar $grammar)
    {
        $this->grammar = $grammar;
    }

    /**
     * Named constructor to create an instance for the given token grammar
     */
    public static function fromGrammar(Grammar $grammar): Lexer
    {
        return new self($grammar);
    }

    /**
     * Return the token stream from the given pdf file
     */
    public function getTokenStream(SplFileObject $fileObject): TokenStream
    {
        foreach ($fileObject as $lineNumber => $line) {
            $line = Line::fromString($line, $lineNumber);

            while (!$line->isResolved()) {
                $this->matchToken($line);
            }
        }

        return TokenStream::fromArray($this->parsedTokens);
    }

    /**
     * Apply all pattern and create a Token object on match
     */
    private function matchToken(Line $line): void
    {
        foreach ($this->grammar as $name => $pattern) {
            if (!$line->matchPattern($pattern)) {
                continue;
            }

            if ($this->grammar->isTokenIgnored($name)) {
                return;
            }

            $this->addToken($name, $line);
            return;
        }

        if (!$line->isResolved()) {
            $this->addToken(Grammar::UNMATCHED_KEY, $line);
            $line->resolve();
        }
    }

    /**
     * Creates and add a token to the internal stack
     */
    private function addToken($name, Line $line): void
    {
        $this->parsedTokens[] = new Token(
            $name,
            $name === Grammar::UNMATCHED_KEY ? $line->getUnmatched() : $line->getMatch(),
            $line->getNumber(),
            $line->getPosition()
        );
    }
}
