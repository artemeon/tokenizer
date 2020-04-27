<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter;

use Artemeon\Tokenizer\Interpreter\Node\Node;
use Artemeon\Tokenizer\Interpreter\Node\ObjectNode;
use Artemeon\Tokenizer\Interpreter\Operation\AddOperation;
use Artemeon\Tokenizer\Interpreter\Operation\Operation;
use Artemeon\Tokenizer\Interpreter\Operation\RemoveOperation;
use Artemeon\Tokenizer\Interpreter\Operation\ReplaceOperation;
use Artemeon\Tokenizer\Tokenizer\Exception\UnexpectedTokenException;
use Artemeon\Tokenizer\Tokenizer\Exception\UnexpectedTokenValueException;
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
        $jsonNode = $this->getNode($scimPatch, $jsonObject);
        $scimOperation = $this->getOperation($scimPatch);

        if ($jsonNode->isArray()) {
            $scimOperation->processArray($jsonNode);
        } else {
            $scimOperation->processObject($jsonNode);
        }

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
     * Find the node (target location) from the given stdClass based on the scim patch path
     *
     * @throws ScimException
     */
    private function getNode(ScimPatchRequest $scimPatch, stdClass &$jsonObject): Node
    {
        // If path is omitted use complete object
        if (!$scimPatch->hasPath()) {
            return ObjectNode::fromObject($jsonObject, '');
        }

        // Interpret the given path an return found node
        try {
            $context = new ScimContext($jsonObject);
            $tokenStream = $this->lexer->getTokenStreamFromString($scimPatch->getPath());
            $syntaxTree = ScimParser::fromTokenStream($tokenStream)->parse();
            $syntaxTree->interpret($context);

            return $context->getJsonNode();
        } catch (UnexpectedTokenException | UnexpectedTokenValueException $e) {
            throw ScimException::forInvalidPath($scimPatch->getPath(), $e->getToken()->getCharacterPosition());
        }
    }
}