<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Tokenizer\Lexer;
use Artemeon\Tokenizer\Tokenizer\ScimGrammar;
use http\Encoding\Stream;
use SplFileObject;

require '../vendor/autoload.php';

$file   = new SplFileObject('./scim.txt');
$lexer  = Lexer::fromGrammar(new ScimGrammar());
$stream = $lexer->getTokenStream($file);

while ($stream->valid()) {
    switch ($stream->current()->getType()) {
        case ScimGrammar::TYPE_ATTRIBUTE:
            $t = "";
        default:
            $stream->next();
    }
    $stream->next();
}
