<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\Operation\RemoveOperation;
use Artemeon\Tokenizer\Interpreter\ScimContext;
use Artemeon\Tokenizer\Tokenizer\Lexer;
use Artemeon\Tokenizer\Tokenizer\ScimGrammar;

require '../vendor/autoload.php';
require './Parser.php';

$parser = Parser::fromTokenStream(
    Lexer::fromGrammar(new ScimGrammar())->getTokenStreamFromString(
        'children[value eq "3459c223-6f76-453a-919d-413861904646"]'
    )
);

$context = new ScimContext(file_get_contents('./test.json'));
$syntaxTree = $parser->parse(new RemoveOperation());
$syntaxTree->interpret($context);

var_dump($context->getJsonData()->children);


