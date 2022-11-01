<?php

namespace Src\Core\Models;

use Src\Core\Exceptions\ValidationException;
use Src\Core\Models\Model;
use Src\Core\Models\TModel;

class Menu extends Model 
{
    protected static $tableName = 'menu';
    protected static $primaryKey = 'id';
    protected static $columns = [
        'name',
        'utip_id',
        'meta',
        'content'
    ];
    protected static $required = [
        'name',
        'meta'
    ];
    protected static $fields = [
        'desc',
        'url',
        'icon',
        'type',
        'level'
    ];
    protected static $jsonValues = ['content'];

    use TModel;

    public function save(): bool 
    {
        $this->validate();
        $this->encodeJSON();
        return parent::save();
    }

    public function addItem($data = []): Menu 
    {
        if($data) {
            if(!$data['level']) $data['level'] = 1;
            if(!$data['url']) $data['url'] = '#';
            if(!$data['type']) $data['type'] = 'item';

            $content = $this->content;
            $content['items'][] = [
                'desc' => $data['desc'],
                'url' => $data['url'],
                'icon' => $data['icon'],
                'level' => $data['level'],
                'type' => $data['type']
            ];

            $this->content = $content;
        }

        return $this;
    }

    public static function getTemplateMenus(int $userTypeId = 0, array $keys = [], array $params = []): array 
    {
        $array = [];

        $in = '';
        if($keys) {
            $in .= '(';
            foreach($keys as $key) {
                $in .= "'{$key}',";
            }
        }
        $in[strlen($in) - 1] = ')';

        $menus = self::get([
            'utip_id' => $userTypeId,
            'raw' => "meta IN {$in}"
        ]);

        if($menus) {
            foreach($menus as $menu) {
                $menu->transformParams($params);
                $array[$menu->meta] = $menu;
            }
        }

        return $array;
    }

    public function transformData(array $data): void 
    {
        $fieldnames = [];
        $content = [];

        foreach(self::$fields as $field) {
            $fieldname = $field;
            $fieldnames[] = $fieldname;
            $data[$fieldname] = explode(',', $data[$field]);
        }

        if($data['order'] !== '') {
            $data['order'] = explode(',', $data['order']);
            $items = [];
            for($i = 0; $i < count($data['order']); $i++) {
                $item = [];
                foreach($fieldnames as $fname) {
                    $arr = $data[$fname];
                    $item[$fname] = $arr[$i];
                }

                $items[$i] = $item;
            }

            $content['items'] = $items;
        }

        if($content) {
            $this->content = json_encode($content, true);
        } else {
            $this->content = null;
        }
    }

    public function transformParams(array $params = []): Menu 
    {
        $this->decodeJSON();
        $items = $this->content['items'];
        
        if($params) {
            foreach($params as $key => $value) {
                $this->name = str_replace('{' . $key . '}', ($value ? $value : ''), $this->name);
            }

            foreach($items as $index => $item) {
                foreach($params as $key => $value) {
                    $items[$index]['url'] = str_replace('{' . $key . '}', ($value ? $value : ''), $items[$index]['url']);
                    $items[$index]['desc'] = str_replace('{' . $key . '}', ($value ? $value : ''), $items[$index]['desc']);
                }
            }

            $this->content = ['items' => $items];
        }

        return $this;
    }

    private function validate(): void 
    {
        $errors = [];

        if(!$this->name) {
            $errors['name'] = 'O Menu precisa ter um nome!';
        }

        if($this->utip_id == '') {
            $errors['utip_id'] = 'O Menu precisa ter um tipo de usuário!';
        }

        if(!$this->meta) {
            $errors['meta'] = 'O Menu precisa ter uma chave!';
        }

        if(count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}