<?php

namespace Src\Models;

use Src\Exceptions\ValidationException;
use Src\Models\Model;

class Config extends Model 
{
    protected static $tableName = 'config';
    protected static $columns = [
        'meta',
        'value'
    ];
    protected static $required = [
        'meta'
    ];

    protected static $metas = [
        'login_img',
        'logo',
        'logo_icon',
        'style'
    ];

    public function save(): bool 
    {
        $this->validate();
        return parent::save();
    }

    public function getMeta(string $meta): mixed
    {
        $object = (new self())->get(['meta' => $meta])->fetch(false);
        return $object ? $object->value : null;
    }

    public function getGroupedMetas(array $metas): ?array
    {
        $objects = (new self())->get(['in' => ['meta' => $metas]])->fetch(true);
        if($objects) {
            $metas = [];
            foreach($objects as $object) {
                $metas[$object->meta] = $object->value;
            }
            return $metas;
        }
        return null;
    }

    public function saveMeta(string $meta, mixed $value): void 
    {
        $object = (new self())->get(['meta' => $meta])->fetch(false);
        if(!$object) {
            $object = (new self());
            $object->meta = $meta;
        }
        $object->value = $value;
        $object->save();
    }

    public function saveManyMetas(array $data): void 
    {
        $objects = (new self())->get(['in' => ['meta' => array_keys($data)]])->fetch(true);
        if($objects) {
            $objects = self::getGroupedBy($objects, 'meta');
        }

        $errors = [];

        foreach($data as $meta => $value) {
            if(isset($objects[$meta])) {
                $objects[$meta]->value = $value;
            } else {
                $objects[$meta] = (new self())->setValues([
                    'meta' => $meta,
                    'value' => $value
                ]);
            }

            try {
                $objects[$meta]->validate();
            } catch(ValidationException $e) {
                $errors[$meta] = $e->getErrors()['value'];
            }
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
        }

        for($i = 0; $i <= count($objects) - 1; $i += 1000) {
            if($objects) {
                parent::updateMany(array_slice($objects, $i, 1000));
            }
        }
    }

    private function validate(): void 
    {
        $errors = [];

        if($this->meta == 'login_img') {
            if(!$this->value) {
                $errors['value'] = _('A imagem de fundo do login é obrigatória!');
            } elseif(!in_array(pathinfo($this->value, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $errors['value'] = _('A imagem de fundo não é uma imagem válida!');
            }
        } elseif($this->meta == 'logo') {
            if(!$this->value) {
                $errors['value'] = _('O logo é obrigatório!');
            } elseif(!in_array(pathinfo($this->value, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $errors['value'] = _('O logo não é uma imagem válida!');
            }
        } elseif($this->meta == 'logo_icon') {
            if(!$this->value) {
                $errors['value'] = _('O ícone é obrigatório!');
            } elseif(!in_array(pathinfo($this->value, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                $errors['value'] = _('O ícone não é uma imagem válida!');
            }
        } elseif($this->meta == 'style') {
            if(!$this->value) {
                $errors['value'] = _('O tema é obrigatório!');
            } elseif(!in_array($this->value, ['light', 'dark'])) {
                $errors['value'] = _('O tema é inválido!');
            }
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors, _('Erros de validação! Verifique os campos.'));
        }
    }
}