<?php

namespace Src\Core\Models;

use Exception;
use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;
use Src\Core\Models\Model;
use Src\Core\Models\TMeta;

class UserMeta extends Model 
{
    protected static $tableName = 'usuario_meta';
    protected static $primaryKey = 'id';
    protected static $columns = [
        'usu_id',
        'meta',
        'value'
    ];
    protected static $required = [
        'usu_id',
        'meta'
    ];
    protected static $elements = [
        'entity' => 'usu_id',
        'meta' => 'meta',
        'value' => 'value'
    ];
    protected static $jsonValues = [];
    protected static $metas = [];
    protected static $handlers = [];

    use TMeta;

    public function save(): bool 
    {
        return parent::save();
    }
}