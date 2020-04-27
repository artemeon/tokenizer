<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\ScimPatch;
use Artemeon\Tokenizer\Interpreter\ScimPatchService;

require '../vendor/autoload.php';

$childJson = json_decode(
    "
    [
        {
            \"value\": \"7567-5677-675675-97898\",
            \"ref\": \"/Units/7567-5677-675675-97898\",
            \"display\": \"New Controlling 4\"
        }
    ]
    "
);

$jsonObject = json_decode(file_get_contents('./test.json'));
$scimPatchRequest = ScimPatch::forAdd('children', $childJson);
$scimPatchService = new ScimPatchService();
$result = $scimPatchService->execute($scimPatchRequest, $jsonObject);

var_dump($result->children);
