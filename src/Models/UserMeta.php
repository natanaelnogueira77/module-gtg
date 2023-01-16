<?php

namespace Src\Models;

use Exception;
use Src\Exceptions\AppException;
use Src\Exceptions\ValidationException;
use Src\Models\Model;
use Src\Models\TMeta;

class UserMeta extends Model 
{
    protected static $tableName = 'usuario_meta';
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
    protected static $metas = [
        'lang'
    ];
    protected static $handlers = [];
    protected static $jsonValues = [];
    public $user;

    use TMeta;

    public function save(): bool 
    {
        return parent::save();
    }

    public function user(string $columns = '*') 
    {
        if(!$this->user) {
            $this->user = $this->belongsTo('Src\Models\User', 'usu_id', 'id', $columns);
        }
        return $this->user;
    }
}