<?php 

namespace Src\Views\Widgets\Sections;

class SystemOptions 
{
    public function __construct(
        private string $formId,
        private string $formAction,
        private string $formMethod,
        private string $loginImageId,
        private string $logoId,
        private string $logoIconId,
        private ?array $configValues = null
    ) 
    {}

    public function getFormId(): string 
    {
        return $this->formId;
    }

    public function getFormAction(): string 
    {
        return $this->formAction;
    }

    public function getFormMethod(): string 
    {
        return $this->formMethod;
    }

    public function getThemeStyle(): string 
    {
        return $this->configValues ? $this->configValues['style'] : '';
    }

    public function getLoginImageURI(): string 
    {
        return $this->configValues ? $this->configValues['login_image']['uri'] : '';
    }

    public function getLoginImageURL(): string 
    {
        return $this->configValues ? $this->configValues['login_image']['url'] : '';
    }

    public function getLogoURI(): string 
    {
        return $this->configValues ? $this->configValues['logo']['uri'] : '';
    }

    public function getLogoURL(): string 
    {
        return $this->configValues ? $this->configValues['logo']['url'] : '';
    }

    public function getLogoIconURI(): string 
    {
        return $this->configValues ? $this->configValues['logo_icon']['uri'] : '';
    }

    public function getLogoIconURL(): string 
    {
        return $this->configValues ? $this->configValues['logo_icon']['url'] : '';
    }

    public function getLoginImageId(): string 
    {
        return $this->loginImageId;
    }

    public function getLogoId(): string 
    {
        return $this->logoId;
    }

    public function getLogoIconId(): string 
    {
        return $this->logoIconId;
    }
}