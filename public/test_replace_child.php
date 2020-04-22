<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\ScimPatchRequest;
use Artemeon\Tokenizer\Interpreter\ScimPatchService;

require '../vendor/autoload.php';
require './Parser.php';

$childJson = json_decode(
    "
        {
            \"value\": \"7567-5677-675675-97898\",
            \"ref\": \"/Units/7567-5677-675675-97898\",
            \"display\": \"New Controlling 4 replacement\"
        }
"
);

$jsonObject = json_decode(file_get_contents('./test.json'));
$scimPatchRequest = ScimPatchRequest::forReplace('children[value eq "3459c223-6f76-453a-919d-413861904646"]', $childJson);
$scimPatchService = new ScimPatchService();
$result = $scimPatchService->execute($scimPatchRequest, $jsonObject);

var_dump($result->children);