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
use Artemeon\Tokenizer\Interpreter\Exception\UnexpectedTokenException;
use Artemeon\Tokenizer\Interpreter\Exception\UnexpectedTokenValueException;
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
     * Execute the given scim patch on the given stdClass
     *
     * @throws ScimException
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
     * Factory method for the requested operation
     *
     * @throws ScimException
     */
    private function getOperation(ScimPatchRequest $scimPatch): Operation
    {
        switch ($scimPatch->getOp()) {
            case AddOperation::NAME:
                $operation = new AddOperation($scimPatch->getValue());
                break;
            case ReplaceOperation::NAME:
                $operation = new ReplaceOperation($scimPatch->getValue());
                break;
            case RemoveOperation::NAME:
                $operation = new RemoveOperation();
                break;
            default:
                throw ScimException::forInvalidValue('op', $scimPatch->getOp());
        }

        return $operation;
    }

    /**
     *
     * @throws ScimException
     */
    private function getJsonNode(ScimPatchRequest $scimPatch, stdClass &$jsonObject): JsonNode
    {
        // If path is omitted use complete jason object
        if (!$scimPatch->hasPath()) {
            return JsonNode::fromObject($jsonObject, '');
        }

        // Interpret the given path an return found node
        try {
            $context = new ScimContext($jsonObject);
            $tokenStream = $this->lexer->getTokenStreamFromString($scimPatch->getPath());
            $syntaxTree = Parser::fromTokenStream($tokenStream)->parse();
            $syntaxTree->interpret($context);

            return $context->getJsonNode();
        } catch (UnexpectedTokenException | UnexpectedTokenValueException $e) {
            throw ScimException::forInvalidPath($scimPatch->getPath(), $e->getToken()->getCharacterPosition());
        }
    }
}