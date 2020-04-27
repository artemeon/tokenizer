<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\Operation\RemoveOperation;
use Artemeon\Tokenizer\Interpreter\ScimContext;
use Artemeon\Tokenizer\Tokenizer\Lexer;
use Artemeon\Tokenizer\Tokenizer\ScimGrammar;
use SplFileObject;

require '../vendor/autoload.php';
require './Parser.php';

$stream = Lexer::fromGrammar(new ScimGrammar())->getTokenStreamFromFile(new SplFileObject('./scim.txt'));
$parser = ScimParser::fromTokenStream($stream);

$context = new ScimContext(file_get_contents('./test.json'));
$syntaxTree = $parser->parse(new RemoveOperation());
$syntaxTree->interpret($context);

var_dump($context->getJsonData()->children);


