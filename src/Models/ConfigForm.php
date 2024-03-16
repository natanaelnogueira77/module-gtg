<?php

namespace Src\Models;

use Src\Models\Model;

class ConfigForm extends Model 
{
    public function __construct(
        public ?string $logoURI = null,
        public ?string $logoIconURI = null,
        public ?string $loginImageURI = null,
        public ?string $style = null
    ) 
    {}

    public function rules(): array 
    {
        return [
            $this->createRule()->required('logoURI')->setMessage(_('The logo is required!')),
            $this->createRule()->required('logoIconURI')->setMessage(_('The icon is required!')),
            $this->createRule()->required('loginImageURI')->setMessage(_('The login background image is required!')),
            $this->createRule()->required('style')->setMessage(_('The color theme is required!')),
            $this->createRule()->in('style', ['light', 'dark'])->setMessage(_('The color theme is invalid!')),
            $this->createRule()->raw(function($model) {
                if(!$model->hasError('logoURI') 
                    && !in_array(pathinfo($model->logoURI, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                    $model->addError('logoURI', _('This image is invalid!'));
                }

                if(!$model->hasError('logoIconURI') 
                    && !in_array(pathinfo($model->logoIconURI, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                    $model->addError('logoIconURI', _('This image is invalid!'));
                }

                if(!$model->hasError('loginImageURI') 
                    && !in_array(pathinfo($model->loginImageURI, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                    $model->addError('loginImageURI', _('This image is invalid!'));
                }
            })
        ];
    }
}