<?php

namespace Models;

use GTG\MVC\Model as GTGModel;
use Exceptions\ValidationException;

abstract class Model extends GTGModel 
{
    public function validate(): bool 
    {
        if(!parent::validate()) {
            throw new ValidationException($this->getFirstErrors(), _('Erros de validação! Verifique os campos.'), 422);
        }

        return true;
    }
}