<?php

namespace Src\Models;

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
            $this->createRule()->required('logoURI')->setMessage(_('O logo é obrigatório!')),
            $this->createRule()->required('logoIconURI')->setMessage(_('O ícone é obrigatório!')),
            $this->createRule()->required('loginImageURI')->setMessage(_('A imagem de fundo é obrigatória!')),
            $this->createRule()->required('style')->setMessage(_('O esquema de cores é obrigatório!')),
            $this->createRule()->in('style', ['light', 'dark'])->setMessage(_('O esquema de cores é inválido!')),
            $this->createRule()->raw(function($model) {
                if(!$model->hasError('logoURI') 
                    && !in_array(pathinfo($model->logoURI, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                    $model->addError('logoURI', _('Essa imagem é inválida!'));
                }

                if(!$model->hasError('logoIconURI') 
                    && !in_array(pathinfo($model->logoIconURI, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                    $model->addError('logoIconURI', _('Essa imagem é inválida!'));
                }

                if(!$model->hasError('loginImageURI') 
                    && !in_array(pathinfo($model->loginImageURI, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                    $model->addError('loginImageURI', _('Essa imagem é inválida!'));
                }
            })
        ];
    }
}