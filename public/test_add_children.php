<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\Operation\AddOperation;
use Artemeon\Tokenizer\Interpreter\ScimContext;
use Artemeon\Tokenizer\Tokenizer\Lexer;
use Artemeon\Tokenizer\Tokenizer\ScimGrammar;

require '../vendor/autoload.php';
require './Parser.php';

$childJson = json_decode("
    [
        {
            \"value\": \"7567-5677-675675-97898\",
            \"ref\": \"/Units/7567-5677-675675-97898\",
            \"display\": \"New Controlling 4\"
        }
    ]
");

$parser = Parser::fromTokenStream(
    Lexer::fromGrammar(new ScimGrammar())->getTokenStreamFromString('children')
);

$context = new ScimContext(file_get_contents('./test.json'), new AddOperation($childJson));
$syntaxTree = $parser->parse();
$syntaxTree->interpret($context);

var_dump($context->getJsonData()->children);
