<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\ScimContext;
use Artemeon\Tokenizer\Tokenizer\Lexer;
use Artemeon\Tokenizer\Tokenizer\ScimGrammar;
use SplFileObject;

require '../vendor/autoload.php';
require './Parser.php';

$stream = Lexer::fromGrammar(new ScimGrammar())->getTokenStreamFromFile(new SplFileObject('./scim.txt'));
$parser = Parser::fromTokenStream($stream);

$context = new ScimContext(file_get_contents('./test.json'));
$syntaxTree = $parser->parse();
$syntaxTree->interpret($context);

$test2 = &$context->currentData;
$path = "children[1]";
$t = $context->jsonData->children[1];

$stream->rewind();
while ($stream->valid()) {
    print_r($stream->current());
    $stream->next();
}


