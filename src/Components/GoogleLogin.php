<?php

namespace Src\Components;

use Exception;
use League\OAuth2\Client\Provider\Google;
use Src\Exceptions\AppException;

class GoogleLogin 
{
    protected $google;
    protected $code;
    protected $token;
    private $error;

    public function __construct(array $config = []) 
    {
        try {
            $this->google = new Google([
                'clientId' => $config['id'],
                'clientSecret' => $config['secret'],
                'redirectUri' => $config['redirect']
            ]);
        } catch(Exception $e) {
            $this->error = new AppException($e->getMessage());
        }
    }

    public function getAuthUrl() 
    {
        $authUrl = $this->google->getAuthorizationUrl(['scope' => ['email']]);
        return $authUrl;
    }

    public function setToken(string $code) 
    {
        $this->token = $this->google->getAccessToken('authorization_code', ['code' => $code]);
    }

    public function getUser() 
    {
        return $this->google->getResourceOwner($this->token);
    }

    public function error(): ?AppException 
    {
        return $this->error;
    }
}