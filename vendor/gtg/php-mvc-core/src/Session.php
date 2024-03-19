<?php 

namespace GTG\MVC;

final class Session 
{
    protected ?string $flashKey = null;
    protected ?string $authKey = null;
    protected ?string $languageKey = null;

    public function __construct() 
    {
        session_start();
    }

    public function setAuthKey(string $authKey): self
    {
        $this->authKey = $authKey;
        return $this;
    }

    public function setFlashKey(string $flashKey): self
    {
        $this->flashKey = $flashKey;
        $flashMessages = $_SESSION[$this->flashKey] ?? [];
        foreach($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }

        $_SESSION[$this->flashKey] = $flashMessages;
        return $this;
    }

    public function setLanguageKey(string $languageKey): self
    {
        $this->languageKey = $languageKey;
        return $this;
    }

    public function setFlash(string $key, string $message): void
    {
        if($this->flashKey) {
            if(!$_SESSION[$this->flashKey]) {
                $_SESSION[$this->flashKey] = [];
            }
            
            $_SESSION[$this->flashKey][$key] = [
                'remove' => false,
                'value' => $message
            ];
        }
    }

    public function getFlash(string $key): string|false
    {
        return $this->flashKey ? ($_SESSION[$this->flashKey][$key]['value'] ?? false) : false;
    }

    public function setAuth(object $auth): void 
    {
        if($this->authKey) {
            $_SESSION[$this->authKey] = $auth;
        }
    }

    public function getAuth(): object|false
    {
        return $this->authKey ? ($_SESSION[$this->authKey] ?? false) : false;
    }

    public function removeAuth(): void
    {
        if($this->authKey) {
            unset($_SESSION[$this->authKey]);
        }
    }

    public function setLanguage(array $languageInfo): void 
    {
        if($this->languageKey) {
            $_SESSION[$this->languageKey] = $languageInfo;
        }
    }

    public function getLanguage(): ?array
    {
        if($this->languageKey && !$_SESSION[$this->languageKey]) {
            $this->setLanguage(['pt_BR.utf-8', 'pt_BR', 'Portuguese_Brazil']);
        }
        return $this->languageKey ? ($_SESSION[$this->languageKey] ?? null) : null;
    }

    public function set(string $key, mixed $value): void 
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key): mixed 
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove(string $key): void 
    {
        unset($_SESSION[$key]);
    }

    public function __destruct() 
    {
        if($this->flashKey) {
            $flashMessages = $_SESSION[$this->flashKey] ?? [];
            foreach($flashMessages as $key => &$flashMessage) {
                if($flashMessage['remove']) {
                    unset($flashMessages[$key]);
                }
            }
    
            $_SESSION[$this->flashKey] = $flashMessages;
        }
    }
}