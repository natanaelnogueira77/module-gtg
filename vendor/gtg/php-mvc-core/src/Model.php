<?php 

namespace GTG\MVC;

abstract class Model 
{
    protected array $errors = [];

    abstract public function rules(): array;

    public function validate(): bool
    {
        foreach($this->rules() as $rule) {
            if(!$rule->validate($this) && $rule->hasMessage()) {
                $this->addError(
                    $rule->getAttribute(),
                    $rule->getMessage()
                );
            }
        }

        return empty($this->errors);
    }

    protected function createRule(): Rule 
    {
        return new Rule();
    }

    public function addError(string $attribute, string $message): void
    {
        $this->errors[$attribute][] = $message;
    }

    public function hasError(string $attribute): array|false
    {
        return $this->errors[$attribute] ?? false;
    }

    public function hasErrors(): bool 
    {
        return $this->errors ? true : false;
    }

    public function getFirstError(string $attribute): string|false
    {
        return $this->errors[$attribute][0] ?? false;
    }

    public function getFirstErrors(): array
    {
        $errors = [];
        foreach($this->errors as $attr => $messages) {
            $errors[$attr] = $messages[0];
        }
        return $errors;
    }
}