<?php

namespace Src\Models\AR;

use Src\Config\FileSystem;

class Config extends ActiveRecord 
{
    public const KEY_LOGIN_IMAGE = 'login_image';
    public const KEY_LOGO = 'logo';
    public const KEY_LOGO_ICON = 'logo_icon';
    public const KEY_STYLE = 'style';

    public static function tableName(): string 
    {
        return 'config';
    }

    public static function primaryKey(): string 
    {
        return 'id';
    }

    public static function attributes(): array 
    {
        return [
            'meta', 
            'value'
        ];
    }

    public static function hasTimestamps(): bool 
    {
        return false;
    }

    public function rules(): array 
    {
        return [
            $this->createRule()->required('meta')->setMessage(_('The metadata is required!')),
            $this->createRule()->maxLength('meta', 50)->setMessage(sprintf(_('The metadata must have %s characters or less!'), 50))
        ];
    }

    public static function getValuesByMetaKeys(array $metaKeys): ?array 
    {
        return self::transformMetaValues(self::get()->filters(function($where) use ($metaKeys) {
            $inCondition = $where->in('meta');
            array_walk($metaKeys, fn($key) => $inCondition->add($key));
        })->fetch(true) ?? []);
    }

    private static function transformMetaValues(array $models): array 
    {
        $values = [];
        foreach($models as $model) {
            if(in_array($model->meta, [self::KEY_LOGIN_IMAGE, self::KEY_LOGO, self::KEY_LOGO_ICON])) {
                $values[$model->meta]['uri'] = $model->value;
                $values[$model->meta]['url'] = url('public/storage/' . $model->value);
            } else {
                $values[$model->meta] = $model->value;
            }
        }

        return $values;
    }

    public static function saveSystemOptions(array $configValues): ?array
    {
        $configs = self::get()->filters(function($where) use ($configValues) {
            $inCondition = $where->in('meta');
            array_walk(array_keys($configValues), fn($key) => $inCondition->add($key));
        })->fetch(true);

        if($configs) {
            foreach($configs as $config) {
                $config->value = $configValues[$config->meta];
            }
            
            self::saveMany($configs);
        }
        
        return $configs;
    }

    public static function getLogoURL(): string 
    {
        return self::getValuesByMetaKeys([self::KEY_LOGO])[self::KEY_LOGO]['url'];
    }

    public static function getLoginImageURL(): string 
    {
        return self::getValuesByMetaKeys([self::KEY_LOGIN_IMAGE])[self::KEY_LOGIN_IMAGE]['url'];
    }
}