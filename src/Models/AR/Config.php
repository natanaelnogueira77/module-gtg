<?php

namespace Models\AR;

use Config\FileSystem;

class Config extends ActiveRecord 
{
    public const KEY_LOGIN_IMAGE = 'login_image';
    public const KEY_LOGO = 'logo';
    public const KEY_LOGO_ICON = 'logo_icon';
    public const KEY_STYLE = 'style';

    private static ?array $logo = null;
    private static ?array $logoIcon = null;
    private static ?array $backgroundImage = null;
    private static ?string $style = null;

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
            $this->createRule()->required('meta')->setMessage(_('O mtetadado é obrigatório!')),
            $this->createRule()->maxLength('meta', 50)->setMessage(sprintf(_('O metadado deve ter %s caractéres ou menos!'), 50))
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
                $values[$model->meta]['url'] = FileSystem::getLink($model->value);
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
            array_walk($configs, fn($config) => $config->value = $configValues[$config->meta]);
            self::saveMany($configs);
        }
        
        return $configs;
    }

    public static function getLogoURL(): string 
    {
        self::$logo = self::$logo ?? self::getValuesByMetaKeys([self::KEY_LOGO])[self::KEY_LOGO];
        return self::$logo['url'];
    }

    public static function getLogoIconURL(): string 
    {
        self::$logoIcon = self::$logoIcon ?? self::getValuesByMetaKeys([self::KEY_LOGO_ICON])[self::KEY_LOGO_ICON];
        return self::$logoIcon['url'];
    }

    public static function getLoginImageURL(): string 
    {
        self::$backgroundImage = self::$backgroundImage ?? self::getValuesByMetaKeys([self::KEY_LOGIN_IMAGE])[self::KEY_LOGIN_IMAGE];
        return self::$backgroundImage['url'];
    }

    public static function getStyle(): string 
    {
        self::$style = self::$style ?? self::getValuesByMetaKeys([self::KEY_STYLE])[self::KEY_STYLE];
        return self::$style;
    }
}