<?php 

namespace GTG\MVC;

final class SMTP 
{
    private string $host = '';
    private string $port = '';
    private string $username = '';
    private string $password = '';
    private string $fromName = '';
    private string $fromEmail = '';

    public function setConnection(
        string $host,
        string $port,
        string $username,
        string $password,
        string $fromName,
        string $fromEmail
    ): void 
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->fromName = $fromName;
        $this->fromEmail = $fromEmail;
    }

    public function setHost(string $host): string 
    {
        $this->host = $host;
    }

    public function setPort(string $port): string 
    {
        $this->port = $port;
    }

    public function setUsername(string $username): string 
    {
        $this->username = $username;
    }

    public function setPassword(string $password): string 
    {
        $this->password = $password;
    }

    public function setFromName(string $fromName): string 
    {
        $this->fromName = $fromName;
    }

    public function setFromEmail(string $fromEmail): string 
    {
        $this->fromEmail = $fromEmail;
    }

    public function getHost(): string 
    {
        return $this->host;
    }

    public function getPort(): string 
    {
        return $this->port;
    }

    public function getUsername(): string 
    {
        return $this->username;
    }

    public function getPassword(): string 
    {
        return $this->password;
    }

    public function getFromName(): string 
    {
        return $this->fromName;
    }

    public function getFromEmail(): string 
    {
        return $this->fromEmail;
    }
}