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
            \"valuedfd\": \"7567-5677-675675-97898\",
            \"resdfsf\": \"/Units/7567-5677-675675-97898\",
            \"dispdfslay\": \"New Controlling 4\"
        }
"
);

$jsonObject = json_decode(file_get_contents('./test.json'));
$scimPatchRequest = ScimPatchRequest::forAdd('', $childJson);
$scimPatchService = new ScimPatchService();
$result = $scimPatchService->execute($scimPatchRequest, $jsonObject);

var_dump($result);
