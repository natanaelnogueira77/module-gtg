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
            throw new ValidationException($this->getFirstErrors(), _('Erros de validação! Verifique os campos.'), 422);
        }

        return true;
    }

    public function destroy(): void 
    {
        try {
            parent::destroy();
        } catch(Exception $e) {
            throw new ApplicationException(_('Lamentamos, mas não foi possível excluir!'), 403);
        }
    }

    public function save(): void 
    {
        $this->transformBeforeSaving();
        parent::save();
        $this->transformAfterFetch();
    }

    protected function transformBeforeSaving(): static 
    {
        return $this;
    }

    protected function transformAfterFetch(): static
    {
        return $this;
    }

    public static function getById(int $id, string|array $columns = '*'): ?static
    {
        return static::findById($id, $columns)?->transformAfterFetch();
    }

    public static function groupManyById(array $models): array 
    {
        $output = [];
        foreach($models as $model) {
            $output[$model->{static::primaryKey()}] = $model;
        }

        return $output;
    }

    public static function getByIds(array $ids, string|array $columns = '*'): ?array
    {
        $ids = array_filter($ids, fn($id) => !is_null($id));
        $models = self::get($columns)->filters(function($where) use ($ids) {
            if($ids) {
                $inCondition = $where->in(static::primaryKey());
                foreach($ids as $id) {
                    $inCondition->add($id);
                }
            }
        })->fetch(true);

        if($models) {
            array_walk($models, fn($model) => $model->transformAfterFetch());
        }

        return $models;
    }

    public static function getAll(string $columns = '*'): ?array 
    {
        $models = static::get($columns)->fetch(true);
        return $models ? array_map(fn($model) => $model->transformAfterFetch(), $models) : null;
    }

    public static function saveMany(array $models): array 
    {
        array_walk($models, fn($model) => $model->transformBeforeSaving());
        $result = parent::saveMany($models);
        array_walk($models, fn($model) => $model->transformAfterFetch());

        return $result;
    }
}