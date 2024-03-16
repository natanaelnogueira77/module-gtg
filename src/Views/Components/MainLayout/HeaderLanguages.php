<?php 

namespace Src\Views\Components\MainLayout;

class HeaderLanguages 
{
    public function __construct(
        private array $languages,
        private string $currentLanguage
    ) 
    {}

    public function getLanguages(): array 
    {
        return $this->languages;
    }

    public function getCurrentLanguageImageURL(): string 
    {
        foreach($this->languages as $language) {
            if($this->currentLanguage == $language->getId()) {
                return $language->getImageURL();
            }
        }

        return '';
    }
}