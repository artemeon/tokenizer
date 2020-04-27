<?php

declare(strict_types=1);

namespace Artemeon\Tokenizer\Interpreter\Expression;

use Artemeon\Tokenizer\Interpreter\Node\ArrayNode;
use Artemeon\Tokenizer\Interpreter\ScimContext;
use Artemeon\Tokenizer\Interpreter\ScimException;

class EqualsFilterExpression implements Expression
{
    /** @var string */
    private $attribute;

    /** @var Expression */
    private $valueExpression;

    public function __construct(string $attribute, Expression $valueExpression)
    {
        $this->attribute = $attribute;
        $this->valueExpression = $valueExpression;
    }

    /**
     * @inheritDoc
     */
    public function interpret(ScimContext $context)
    {
        $data = &$context->getCurrentData();

        if (!is_array($data)) {
            throw ScimException::forInvalidValue('ScimPatch:path', "Filter target must be array" . $this->attribute);
        }

        foreach ($data as $index => &$row) {
            $this->valueExpression->interpret($context);
            $needle = $context->getExpressionResult($this->valueExpression);

            if (!property_exists($row, $this->attribute)) {
                throw ScimException::forNoTarget($this->attribute);
            }

            $propertyValue = $row->{$this->attribute};

            if ($propertyValue == $needle) {
                if ($context->isLastExpression($this)) {
                    $context->setNode(ArrayNode::fromArray($data, $index));
                    return;
                }

                $context->setCurrentData($row);
                $context->setExpressionResult($this, $row);
                return;
            }
        }

        throw ScimException::forNoTarget($this->attribute);
    }
}