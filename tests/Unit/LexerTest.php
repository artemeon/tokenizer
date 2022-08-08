<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Tests\Unit;

use Artemeon\Tokenizer\Exception\UnexpectedTokenException;
use Artemeon\Tokenizer\Lexer;
use Artemeon\Tokenizer\Token;
use PHPUnit\Framework\TestCase;
use Artemeon\Tokenizer\Tests\TestGrammar;

class LexerTest extends TestCase
{
    private Lexer $lexer;

    public function setUp(): void
    {
        $this->lexer = Lexer::fromGrammar(new TestGrammar());
    }

    public function testCheckCompleteStream(): void
    {
        $tokenStream = $this->lexer->getTokenStreamFromString('members eq "users" and status eq 10');
        self::assertTrue($tokenStream->checkTypeAndValue(TestGrammar::TYPE_ATTRIBUTE, "members"));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkType(TestGrammar::TYPE_WHITESPACE));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkType(TestGrammar::TYPE_OPERATOR_EQUALS));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkType(TestGrammar::TYPE_WHITESPACE));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkTypeAndValue(TestGrammar::TYPE_STRING, '"users"'));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkType(TestGrammar::TYPE_WHITESPACE));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkType(TestGrammar::TYPE_OPERATOR_AND));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkType(TestGrammar::TYPE_WHITESPACE));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkTypeAndValue(TestGrammar::TYPE_ATTRIBUTE, "status"));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkType(TestGrammar::TYPE_WHITESPACE));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkType(TestGrammar::TYPE_OPERATOR_EQUALS));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkType(TestGrammar::TYPE_WHITESPACE));

        $tokenStream->next();
        self::assertTrue($tokenStream->checkTypeAndValue(TestGrammar::TYPE_NUMERIC, "10"));
    }

    public function testExpectCompleteStream(): void
    {
        $tokenStream = $this->lexer->getTokenStreamFromString('members eq "users" and status eq 10');
        $tokenStream->expectTypeAndValue(TestGrammar::TYPE_ATTRIBUTE, "members");
        $tokenStream->expectType(TestGrammar::TYPE_WHITESPACE);
        $tokenStream->expectType(TestGrammar::TYPE_OPERATOR_EQUALS);
        $tokenStream->expectType(TestGrammar::TYPE_WHITESPACE);
        $tokenStream->expectTypeAndValue(TestGrammar::TYPE_STRING, '"users"');
        $tokenStream->expectType(TestGrammar::TYPE_WHITESPACE);
        $tokenStream->expectType(TestGrammar::TYPE_OPERATOR_AND);
        $tokenStream->expectType(TestGrammar::TYPE_WHITESPACE);
        $tokenStream->expectTypeAndValue(TestGrammar::TYPE_ATTRIBUTE, "status");
        $tokenStream->expectType(TestGrammar::TYPE_WHITESPACE);
        $tokenStream->expectType(TestGrammar::TYPE_OPERATOR_EQUALS);
        $tokenStream->expectType(TestGrammar::TYPE_WHITESPACE);
        $tokenStream->expectTypeAndValue(TestGrammar::TYPE_NUMERIC, "10");

        self::assertCount(13, $tokenStream);
    }

    public function testExpectWithUnmatchedValues(): void {
        $tokenStream = $this->lexer->getTokenStreamFromString('members eq10');

        $tokenStream->expectTypeAndValue(TestGrammar::TYPE_ATTRIBUTE, "members");
        $tokenStream->expectType(TestGrammar::TYPE_WHITESPACE);

        $this->expectException(UnexpectedTokenException::class);
        $tokenStream->expectType(TestGrammar::TYPE_OPERATOR_EQUALS);
    }

    public function testCheckTypeIsOneOfWillReturnTrue(): void
    {
        $tokenStream = $this->lexer->getTokenStreamFromString('members eq 10');
        self::assertTrue($tokenStream->checkTypeIsOneOf([TestGrammar::TYPE_WHITESPACE, TestGrammar::TYPE_ATTRIBUTE]));

        $tokenStream = $this->lexer->getTokenStreamFromString(' members eq 10');
        self::assertTrue($tokenStream->checkTypeIsOneOf([TestGrammar::TYPE_WHITESPACE, TestGrammar::TYPE_ATTRIBUTE]));
    }

    public function testCheckTypeIsOneOfWillReturnFalse(): void
    {
        $tokenStream = $this->lexer->getTokenStreamFromString('members eq 10');
        self::assertFalse($tokenStream->checkTypeIsOneOf([TestGrammar::TYPE_WHITESPACE, TestGrammar::TYPE_OPERATOR_AND]));
    }

    public function testExpectTypeIsOneOf(): void
    {
        $tokenStream = $this->lexer->getTokenStreamFromString('members eq 10');
        self::assertInstanceOf(Token::class, $tokenStream->expectTypeIsOneOf([TestGrammar::TYPE_WHITESPACE, TestGrammar::TYPE_ATTRIBUTE]));

        $tokenStream = $this->lexer->getTokenStreamFromString(' members eq 10');
        self::assertInstanceOf(Token::class, $tokenStream->expectTypeIsOneOf([TestGrammar::TYPE_WHITESPACE, TestGrammar::TYPE_ATTRIBUTE]));
    }

    public function testExpectTypeIsOneOfWillThrowException(): void
    {
        $this->expectException(UnexpectedTokenException::class);
        $tokenStream = $this->lexer->getTokenStreamFromString('members eq 10');
        $tokenStream->expectTypeIsOneOf([TestGrammar::TYPE_WHITESPACE, TestGrammar::TYPE_OPERATOR_EQUALS]);
    }

    public function testLookAhead(): void {
        $tokenStream = $this->lexer->getTokenStreamFromString('members eq');

        $token = $tokenStream->lookAhead(1);
        self::assertSame(TestGrammar::TYPE_WHITESPACE, $token->getType());

        $token = $tokenStream->lookAhead(2);
        self::assertSame(TestGrammar::TYPE_OPERATOR_EQUALS, $token->getType());
    }
}
