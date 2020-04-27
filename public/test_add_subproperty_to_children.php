<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\ScimPatchRequest;
use Artemeon\Tokenizer\Interpreter\ScimPatchService;

require '../vendor/autoload.php';

$scimPatchRequest = ScimPatchRequest::forAdd(
    'children[value eq "3459c223-6f76-453a-919d-413861904646"].displayNew',
    'new_property_value'
);

$jsonObject = json_decode(file_get_contents('./test.json'));
$scimPatchService = new ScimPatchService();
$result = $scimPatchService->execute($scimPatchRequest, $jsonObject);

var_dump($result->children);