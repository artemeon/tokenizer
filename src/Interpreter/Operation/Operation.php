<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Operation;

use Artemeon\Tokenizer\Interpreter\Node\Node;
use Artemeon\Tokenizer\Interpreter\ScimException;

/**
 * Base class for al scim operations
 *
 * Detects the data type of the node and calls matching process method
 */
abstract class Operation
{
    /** @var string  */
    public const TYPE_ARRAY = 'array';

    /** @var string  */
    public const TYPE_STD_CLASS = 'object;';

    /**
     * Process the given node based on their data type
     *
     * @throws ScimException
     */
    public final function process(Node $jsonNode):void
    {
        switch ($jsonNode->getDataType()) {
            case self::TYPE_ARRAY:
                $this->processArray($jsonNode);
                break;
            case self::TYPE_STD_CLASS:
                $this->processObject($jsonNode);
                break;
            default:
                throw ScimException::forInvalidValue('Node', 'Data type not supported');
        }
    }

    /**
     * Process node's witch contains array data
     *
     * @throws ScimException
     */
    abstract protected function processArray(Node $jsonNode): void;

    /**
     * Process node's which contains stdClass data
     *
     * @throws ScimException
     */
    abstract protected function processObject(Node $jsonNode): void;
}