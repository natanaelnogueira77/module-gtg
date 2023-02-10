<?php 

namespace Src\Components;

class Lang 
{
    private $filepath = '';
    private $content = [];
    private $base = null;

    public function __construct() 
    {
    }

    public static function getLanguage(): string 
    {
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        return isset($_SESSION[SESS_LANG]) ? $_SESSION[SESS_LANG] : 'pt';
    }

    public static function setLanguage(string $lang): void 
    {
        session_status() === PHP_SESSION_ACTIVE ?: session_start();
        $_SESSION[SESS_LANG] = $lang;
    }

    public function setFilepath(string $filepath): self 
    {
        $this->filepath = $filepath;
        return $this;
    }

    public function getContent(): self 
    {
        $lang = self::getLanguage();
        if(is_file(PATH . '/lang/' . $lang . "/{$this->filepath}.php")) {
            $this->content = include(PATH . '/lang/' . $lang . "/{$this->filepath}.php");
        }

        $this->base = null;
        return $this;
    }

    public function setBase(string $key): self 
    {
        if($this->content) {
            $elements = explode('.', $key);
            $base = $this->content;
            for($i = 0; $i < count($elements); $i++) {
                $base = $base[$elements[$i]];
            }

            if(is_array($base)) {
                $this->base = $base;
            }
        }

        return $this;
    }

    public function get(string $key, ?array $params = null): array|string
    {
        if($this->content) {
            $elements = explode('.', $key);
            $translation = $this->base ? $this->base : $this->content;
            for($i = 0; $i < count($elements); $i++) {
                $translation = $translation[$elements[$i]];
                if(!$translation) {
                    return '';
                }
            }

            if(is_string($translation)) {
                if($params) {
                    foreach($params as $key => $value) {
                        $translation = str_replace('{' . $key . '}', $value, $translation);
                    }
                }
            }
            
            return $translation;
        }

        return '';
    }
}