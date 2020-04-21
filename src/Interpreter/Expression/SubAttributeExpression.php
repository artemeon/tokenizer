<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Expression;

use Artemeon\Tokenizer\Interpreter\Operation\Operation;
use Artemeon\Tokenizer\Interpreter\ScimContext;
use Artemeon\Tokenizer\Interpreter\ScimException;

class SubAttributeExpression implements Expression
{
    /** @var string */
    private $name;

    /** @var Operation|null */
    private $operation;

    public function __construct(string $name, ?Operation $operation)
    {
        $this->name = str_replace('.', '', $name);
        $this->operation = $operation;
    }

    /**
     * @inheritDoc
     */
    public function interpret(ScimContext $context)
    {
        $data = &$context->getCurrentData();

        if ($this->operation instanceof Operation) {
            $this->processOperation($data);
            return;
        }

        if (!property_exists($data, $this->name)) {
            throw new ScimException("Missing sub property:" . $this->name);
        }

        $attribute = &$data->{$this->name};
        $context->setExpressionResult($this, $attribute);
        $context->setCurrentData($attribute);
    }

    /**
     * Process the scim operation based on the detected data type
     */
    private function processOperation(&$data): void
    {
        $propertyValue = &$data->{$this->name};

        if (is_array($propertyValue)) {
            $this->operation->processMultiValuedAttribute($propertyValue);
        }

        if (is_object($propertyValue)) {
            $this->operation->processComplexAttribute($this->name, $propertyValue);
        }

        $this->operation->processSingleValuedAttribute($propertyValue);
    }
}