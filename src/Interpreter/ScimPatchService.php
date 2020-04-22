<?php

/*
 * This file is part of the Artemeon Core - Web Application Framework.
 *
 * (c) Artemeon <www.artemeon.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use App\Parser;
use Artemeon\Tokenizer\Interpreter\Operation\AddOperation;
use Artemeon\Tokenizer\Interpreter\Operation\Operation;
use Artemeon\Tokenizer\Interpreter\Operation\RemoveOperation;
use Artemeon\Tokenizer\Interpreter\Operation\ReplaceOperation;
use Artemeon\Tokenizer\Tokenizer\Lexer;
use Artemeon\Tokenizer\Tokenizer\ScimGrammar;
use stdClass;

class ScimPatchService
{
    /** @var Lexer */
    private $lexer;

    public function __construct()
    {
        $this->lexer = Lexer::fromGrammar(new ScimGrammar());
    }

    /**
     * @throws Exception\UnexpectedTokenException
     * @throws Exception\UnexpectedTokenValueException
     */
    public function execute(ScimPatchRequest $scimPatch, stdClass $jsonObject): stdClass
    {
        $jsonNode = $this->getJsonNode($scimPatch, $jsonObject);
        $scimOperation = $this->getOperation($scimPatch);

        if ($jsonNode->isArray()) {
            $scimOperation->processArray($jsonNode);
            return $jsonObject;
        }

        $scimOperation->processObject($jsonNode);
        return $jsonObject;
    }

    /**
     * @param ScimPatchRequest $scimPatch
     * @return Operation
     */
    private function getOperation(ScimPatchRequest $scimPatch): Operation
    {
        switch ($scimPatch->getOp()) {
            case 'add':
                return new AddOperation($scimPatch->getValue());
            case 'replace':
                return new ReplaceOperation($scimPatch->getValue());
            case 'remove':
                return new RemoveOperation();
        }
    }

    /**
     * @param ScimPatchRequest $scimPatch
     * @param stdClass $jsonObject
     * @return array
     * @throws Exception\UnexpectedTokenException
     * @throws Exception\UnexpectedTokenValueException
     */
    private function getJsonNode(ScimPatchRequest $scimPatch, stdClass &$jsonObject): JsonNode
    {
        if ($scimPatch->hasPath()) {
            $context = new ScimContext($jsonObject);
            $tokenStream = $this->lexer->getTokenStreamFromString($scimPatch->getPath());
            $syntaxTree = Parser::fromTokenStream($tokenStream)->parse();
            $syntaxTree->interpret($context);

            return $context->getJsonNode();
        }

        return JsonNode::fromObject($jsonObject, '');
    }
}