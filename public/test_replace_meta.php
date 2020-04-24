<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\ScimPatchRequest;
use Artemeon\Tokenizer\Interpreter\ScimPatchService;

require '../vendor/autoload.php';
require './Parser.php';

$patchObject = json_decode(
    "
        {
            \"resourceType\": \"Patched\",
            \"NewPatched\": \"2010-01-23T04:56:22Z\",
            \"location\": \"patched\"
        }
    "
);

$jsonObject = json_decode(file_get_contents('./test.json'));
$scimPatchRequest = ScimPatchRequest::forReplace('meta', $patchObject);
$scimPatchService = new ScimPatchService();
$result = $scimPatchService->execute($scimPatchRequest, $jsonObject);

var_dump($result->meta);