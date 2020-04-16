<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\ScimContext;
use Artemeon\Tokenizer\Tokenizer\Lexer;
use Artemeon\Tokenizer\Tokenizer\ScimGrammar;
use SplFileObject;

require '../vendor/autoload.php';
require './Parser.php';

$file = new SplFileObject('./scim.txt');
$lexer = Lexer::fromGrammar(new ScimGrammar());
$stream = $lexer->getTokenStreamFromFile($file);

$parser = new Parser($stream);
$expressions = $parser->parse();
$context = new ScimContext(file_get_contents('./test.json'));

foreach ($expressions as $expression) {
    $expression->interpret($context);
}

$changed = $context->getCurrentData() . 'Changed';
$context->setCurrentData($changed);
$stream->rewind();

while ($stream->valid()) {
    print_r($stream->current());
    $stream->next();
}
