<?php

/*
 * This file is part of the Artemeon\Tokenizer package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer;

use SplFileObject;

/**
 * Lexer to create an parser token stream based on the given Grammar and input
 */
class Lexer
{
    private Grammar $grammar;

    /** @var Token[] */
    private array $parsedTokens = [];

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
     * Return the token stream from the given string
     */
    public function getTokenStreamFromString(string $input): TokenStream
    {
        /** @var string[] $lines */
        $lines = (array) preg_split("/\n|\r\n?/", $input);

        return $this->parseLines($lines);
    }

    /**
     * Return the token stream from the given file object
     */
    public function getTokenStreamFromFile(SplFileObject $inputFile): TokenStream
    {
        /** @var string[] $lines */
        $lines = [];

        /** @var string $line */
        foreach ($inputFile as $line) {
            $lines[] = $line;
        }

        return $this->parseLines($lines);
    }

    /**
     * Create the TokenStream based on the given line strings
     *
     * @param string[] $lines
     */
    private function parseLines(array $lines): TokenStream
    {
        $this->parsedTokens = [];

        foreach ($lines as $lineNumber => $lineParser) {
            $lineParser = LineParser::fromString($lineParser, $lineNumber);

            while (!$lineParser->isResolved()) {
                $this->applyToken($lineParser);
            }
        }

        return TokenStream::fromArray($this->parsedTokens);
    }

    /**
     * Apply all pattern and create a Token object on match
     */
    private function applyToken(LineParser $lineParser): void
    {
        /**
         * @var string $name
         * @var string $pattern
         */
        foreach ($this->grammar as $name => $pattern) {
            if (!$lineParser->applyPattern($pattern)) {
                continue;
            }

            if ($this->grammar->isTokenIgnored($name)) {
                return;
            }

            $this->addToken($name, $lineParser);
            return;
        }

        if (!$lineParser->isResolved()) {
            $this->addToken(Grammar::UNMATCHED_KEY, $lineParser);
            $lineParser->resolve();
        }
    }

    /**
     * Creates and add a token to the internal stack
     */
    private function addToken(string $name, LineParser $lineParser): void
    {
        $this->parsedTokens[] = new Token(
            $name,
            $name === Grammar::UNMATCHED_KEY ? $lineParser->getUnmatched() : $lineParser->getMatch(),
            $lineParser->getLineNumber(),
            $lineParser->getCharacterPosition()
        );
    }
}
