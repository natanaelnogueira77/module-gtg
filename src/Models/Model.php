<?php

namespace Src\Models;

use GTG\MVC\Model as GTGModel;
use Src\Exceptions\ValidationException;

abstract class Model extends GTGModel 
{
    public function validate(): bool 
    {
        if(!parent::validate()) {
            throw new ValidationException(
                $this->getFirstErrors(), 
                _('Validation errors! Check the fields.')
            );
        }

        return true;
    }
}