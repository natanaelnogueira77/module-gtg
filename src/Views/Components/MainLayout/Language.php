<?php 

namespace Src\Views\Components\MainLayout;

class Language 
{
    public function __construct(
        private string $id,
        private string $url,
        private string $imageURL,
        private string $name
    ) 
    {}

    public function getId(): string 
    {
        return $this->id;
    }

    public function getURL(): string 
    {
        return $this->url;
    }

    public function getImageURL(): string 
    {
        return $this->imageURL;
    }

    public function getName(): string 
    {
        return $this->name;
    }
}