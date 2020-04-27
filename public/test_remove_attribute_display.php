<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\ScimPatchRequest;
use Artemeon\Tokenizer\Interpreter\ScimPatchService;

require '../vendor/autoload.php';

$jsonObject = json_decode(file_get_contents('./test.json'));
$scimPatchRequest = ScimPatchRequest::forRemove('children[value eq "75679c223-6f76-4756-919d-41386"].display');
$scimPatchService = new ScimPatchService();
$result = $scimPatchService->execute($scimPatchRequest, $jsonObject);

var_dump($result->children);