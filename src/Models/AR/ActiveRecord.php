<?php

namespace Src\Models\AR;

use GTG\MVC\Database\ActiveRecord as GTGActiveRecord;
use Src\Models\Lists\ActiveRecordList;
use Src\Exceptions\{ ApplicationException, ValidationException };

abstract class ActiveRecord extends GTGActiveRecord
{
    public function validate(): bool 
    {
        if(!parent::validate()) {
            throw new ValidationException(
                $this->getFirstErrors(), 
                _('Validation errors! Check the fields.'),
                422
            );
        }

        return true;
    }

    public function destroy(): void 
    {
        try {
            parent::destroy();
        } catch(Exception $e) {
            throw new ApplicationException(
                _('We are sorry, but it was not possible to delete!'),
                403
            );
        }
    }

    public static function getById(int $id, string|array $columns = '*'): ?static
    {
        return static::findById($id, $columns);
    }
}