<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Expression;

use Artemeon\Tokenizer\Interpreter\Node\ObjectNode;
use Artemeon\Tokenizer\Interpreter\ScimContext;
use Artemeon\Tokenizer\Interpreter\ScimException;

class SubAttributeExpression implements Expression
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = str_replace('.', '', $name);
    }

    /**
     * @inheritDoc
     */
    public function interpret(ScimContext $context)
    {
        $data = &$context->getCurrentData();

        if ($context->isLastExpression($this)) {
            $context->setFoundNode(ObjectNode::fromObject($data, $this->name));
            return;
        }

        if (!property_exists($data, $this->name)) {
            throw ScimException::forNoTarget($this->name);
        }

        $propertyValue = &$data->{$this->name};
        $context->setExpressionResult($this, $propertyValue);
        $context->setCurrentData($propertyValue);
    }
}
