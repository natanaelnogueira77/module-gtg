<?php

namespace Src\Components;

use Exception;
use League\OAuth2\Client\Provider\Facebook;

class FacebookLogin 
{
    protected $facebook;
    protected $code;
    protected $token;

    public function __construct(array $config = []) 
    {
        try {
            $this->facebook = new Facebook([
                'clientId' => $config['id'],
                'clientSecret' => $config['secret'],
                'redirectUri' => $config['redirect'],
                'graphApiVersion' => $config['version']
            ]);
        } catch(Exception $e) {
            $this->error = $e;
        }
    }

    public function getAuthUrl() 
    {
        $authUrl = $this->facebook->getAuthorizationUrl([
            'scope' => ['email']
        ]);

        return $authUrl;
    }

    public function setToken(string $code) 
    {
        $this->token = $this->facebook->getAccessToken('authorization_code', [
            'code' => $code
        ]);
    }

    public function getUser() 
    {
        return $this->facebook->getResourceOwner($this->token);
    }

    public function error(): ?Exception 
    {
        return $this->error;
    }
}