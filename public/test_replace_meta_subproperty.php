<?php

declare(strict_types=1);

namespace App;

use Artemeon\Tokenizer\Interpreter\ScimPatch;
use Artemeon\Tokenizer\Interpreter\ScimPatchService;

require '../vendor/autoload.php';

$jsonObject = json_decode(file_get_contents('./test.json'));
$scimPatchRequest = ScimPatch::forReplace('meta.resourceType', 'new_property_value');
$scimPatchService = new ScimPatchService();
$result = $scimPatchService->execute($scimPatchRequest, $jsonObject);

var_dump($result->meta);