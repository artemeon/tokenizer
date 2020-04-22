<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Expression;

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
            if (property_exists($data, $this->name)) {
                $context->setOperationData($data->{$this->name});
                return;
            }

            $context->setOperationData($data, null, $this->name);
            return;
        }

        if (!property_exists($data, $this->name)) {
            throw new ScimException("Missing property:" . $this->name);
        }

        $propertyValue = &$data->{$this->name};
        $context->setExpressionResult($this, $propertyValue);
        $context->setCurrentData($propertyValue);
    }
}