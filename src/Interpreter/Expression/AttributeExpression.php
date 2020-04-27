<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Expression;

use Artemeon\Tokenizer\Interpreter\Node\StdClassNode;
use Artemeon\Tokenizer\Interpreter\ScimContext;
use Artemeon\Tokenizer\Interpreter\ScimException;

class AttributeExpression implements Expression
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function interpret(ScimContext $context)
    {
        $data = &$context->getCurrentData();

        if ($context->isLastExpression($this)) {
            $context->setFoundNode(StdClassNode::fromObject($data, $this->name));
            return;
        }

        if (!property_exists($data, $this->name)) {
            throw ScimException::forNoTarget($this->name);
        }

        $propertyValue = &$data->{$this->name};
        $context->setCurrentData($propertyValue);
        $context->setExpressionResult($this, $propertyValue);
    }
}