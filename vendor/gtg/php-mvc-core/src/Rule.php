<?php 

namespace GTG\MVC;

use DateTime;
use GTG\MVC\Model;

final class Rule 
{
    private const RULE_REQUIRED = 1;
    private const RULE_RAW = 2;
    private const RULE_DATETIME = 3;
    private const RULE_EMAIL = 4;
    private const RULE_IN = 5;
    private const RULE_INT = 6;
    private const RULE_MATCH = 7;
    private const RULE_MAX = 8;
    private const RULE_MIN = 9;
    private const RULE_EQUAL_TO = 10;
    private const RULE_SMALLER_THAN = 11;
    private const RULE_EQUAL_OR_SMALLER_THAN = 12;
    private const RULE_LARGER_THAN = 13;
    private const RULE_EQUAL_OR_LARGER_THAN = 14;

    private int $type = 1;
    private ?string $attribute = null;
    private ?string $message = null;
    private ?array $params = null;

    public function getAttribute(): string 
    {
        return $this->attribute ?? '';
    }

    public function getMessage(): string 
    {
        return $this->message ?? '';
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function required(string $attribute): self 
    {
        $this->type = self::RULE_REQUIRED;
        $this->attribute = $attribute;
        return $this;
    }

    public function raw(callable $function): self 
    {
        $this->type = self::RULE_RAW;
        $this->params = ['function' => $function];
        return $this;
    }

    public function dateTime(string $attribute, string $pattern): self 
    {
        $this->type = self::RULE_DATETIME;
        $this->attribute = $attribute;
        $this->params = ['pattern' => $pattern];
        return $this;
    }

    public function email(string $attribute): self 
    {
        $this->type = self::RULE_EMAIL;
        $this->attribute = $attribute;
        return $this;
    }

    public function in(string $attribute, array $values): self 
    {
        $this->type = self::RULE_IN;
        $this->attribute = $attribute;
        $this->params = ['values' => $values];
        return $this;
    }

    public function int(string $attribute): self 
    {
        $this->type = self::RULE_INT;
        $this->attribute = $attribute;
        return $this;
    }

    public function match(string $attribute, string $matchAttribute): self 
    {
        $this->type = self::RULE_MATCH;
        $this->attribute = $attribute;
        $this->params = ['match' => $matchAttribute];
        return $this;
    }

    public function maxLength(string $attribute, int $maxLength): self 
    {
        $this->type = self::RULE_MAX;
        $this->attribute = $attribute;
        $this->params = ['value' => $maxLength];
        return $this;
    }

    public function minLength(string $attribute, int $minLength): self 
    {
        $this->type = self::RULE_MIN;
        $this->attribute = $attribute;
        $this->params = ['value' => $minLength];
        return $this;
    }

    public function equalTo(string $attribute, int|float $value): self 
    {
        $this->type = self::RULE_EQUAL_TO;
        $this->attribute = $attribute;
        $this->params = ['value' => $value];
        return $this;
    }

    public function smallerThan(string $attribute, int|float $value): self 
    {
        $this->type = self::RULE_SMALLER_THAN;
        $this->attribute = $attribute;
        $this->params = ['value' => $value];
        return $this;
    }

    public function equalOrSmallerThan(string $attribute, int|float $value): self 
    {
        $this->type = self::RULE_EQUAL_OR_SMALLER_THAN;
        $this->attribute = $attribute;
        $this->params = ['value' => $value];
        return $this;
    }

    public function largerThan(string $attribute, int|float $value): self 
    {
        $this->type = self::RULE_LARGER_THAN;
        $this->attribute = $attribute;
        $this->params = ['value' => $value];
        return $this;
    }

    public function equalOrLargerThan(string $attribute, int|float $value): self 
    {
        $this->type = self::RULE_EQUAL_OR_LARGER_THAN;
        $this->attribute = $attribute;
        $this->params = ['value' => $value];
        return $this;
    }

    public function validate(Model $model): bool
    {
        $value = $this->attribute ? $model->{$this->attribute} : null;
        if(
            ($this->isRequired() && !$value) 
            || ($this->isRaw() && !$this->params['function']($model))
            || ($this->isDateTime() && $value && !DateTime::createFromFormat($this->params['pattern'], $value))
            || ($this->isEmail() && !filter_var($value, FILTER_VALIDATE_EMAIL))
            || ($this->isInt() && !filter_var($value, FILTER_VALIDATE_INT))
            || ($this->isMin() && $value && strlen($value) < $this->params['value'])
            || ($this->isMax() && $value && strlen($value) > $this->params['value'])
            || ($this->isMatch() && $value !== $model->{$this->params['match']})
            || ($this->isEqualTo() && $value != $this->params['value'])
            || ($this->isSmallerThan() && $value >= $this->params['value'])
            || ($this->isEqualOrSmallerThan() && $value > $this->params['value'])
            || ($this->isLargerThan() && $value <= $this->params['value'])
            || ($this->isEqualOrLargerThan() && $value < $this->params['value'])
            || ($this->isIn() && $value && !in_array($value, $this->params['values']))
        ) {
            return false;
        }

        return true;
    }

    private function isRequired(): bool 
    {
        return $this->type == self::RULE_REQUIRED;
    }

    private function isRaw(): bool 
    {
        return $this->type == self::RULE_RAW;
    }

    private function isDateTime(): bool 
    {
        return $this->type == self::RULE_DATETIME;
    }

    private function isEmail(): bool 
    {
        return $this->type == self::RULE_EMAIL;
    }

    private function isInt(): bool 
    {
        return $this->type == self::RULE_INT;
    }

    private function isMin(): bool 
    {
        return $this->type == self::RULE_MIN;
    }

    private function isMax(): bool 
    {
        return $this->type == self::RULE_MAX;
    }

    private function isMatch(): bool 
    {
        return $this->type == self::RULE_MATCH;
    }

    private function isEqualTo(): bool 
    {
        return $this->type == self::RULE_EQUAL_TO;
    }

    private function isSmallerThan(): bool 
    {
        return $this->type == self::RULE_SMALLER_THAN;
    }

    private function isEqualOrSmallerThan(): bool 
    {
        return $this->type == self::RULE_EQUAL_OR_SMALLER_THAN;
    }

    private function isLargerThan(): bool 
    {
        return $this->type == self::RULE_LARGER_THAN;
    }

    private function isEqualOrLargerThan(): bool 
    {
        return $this->type == self::RULE_EQUAL_OR_LARGER_THAN;
    }

    private function isIn(): bool 
    {
        return $this->type == self::RULE_IN;
    }

    public function hasMessage(): bool 
    {
        return $this->message ? true : false;
    }
}