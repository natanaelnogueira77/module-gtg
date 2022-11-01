<?php

namespace Src\Core\App\Admin;

use Src\Core\App\Admin\CTemplate;
use Src\Core\Models\Config;
use Src\Core\Models\UserType;
use Src\Core\System\DatabaseConfig;
use Src\Core\System\EmailConfig;
use Src\Core\System\Main;

class CConfig extends CTemplate 
{
    public function system(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }

            $dbMain = new Main([
                'error_mail' => $data['error_mail'],
                'root' => $data['root'],
                'sitename' => $data['sitename'],
                'sessname' => $data['sessname'],
                'version' => $data['version']
            ]);

            Config::saveMetas([
                'title' => $data['title'],
                'style' => $data['style'],
                'logo' => $data['logo'],
                'logo_white' => $data['logo_white'],
                'logo_icon' => $data['logo_icon']
            ]);

            $content = $dbMain->checkData();

            $this->setMessage('Configurações atualizadas com sucesso!');
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }

    public function smtp(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }

            $emailConfig = new EmailConfig([
                'host' => $data['host'],
                'port' => $data['port'],
                'username' => $data['username'],
                'password' => $data['password'],
                'name' => $data['name'],
                'email' => $data['email']
            ]);

            $content = $emailConfig->checkData();

            $this->setMessage('Configurações de E-mail alteradas com sucesso!');
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }

    public function database(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }

            $dbConfig = new DatabaseConfig([
                'driver' => $data['driver'],
                'dbname' => $data['dbname'],
                'host' => $data['host'],
                'port' => $data['port'],
                'username' => $data['username'],
                'passwd' => $data['passwd']
            ]);

            $content = $dbConfig->checkData();

            $this->setMessage('Configurações de E-mail alteradas com sucesso!');
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }

    public function usertypes(array $data): void 
    {
        $user = $this->getSessionUser();

        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }

            $userTypes = [];
            if($data['utip_id']) {
                foreach($data['utip_id'] as $index => $value) {
                    $object = new UserType();
                    $object->setValues([
                        'id' => $value,
                        'name_sing' => $data['name_sing'][$value],
                        'name_plur' => $data['name_plur'][$value]
                    ]);
                    $userTypes[] = $object;
                }

                UserType::updateObjects($userTypes);
            }

            $this->setMessage('Nomenclaturas dos Usuários alteradas com sucesso!');
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }

    public function saveConfig(array $data): void 
    {
        $user = $this->getSessionUser();
        $callback = [];

        try {
            if(!$user->isAdmin()) {
                $this->throwException('Você não tem permissão para executar essa ação!');
            }

            Config::saveMetas($data);
            $this->setMessage('Configurações atualizadas com sucesso!');
        } catch(\Exception $e) {
            $this->error = $e;
        }
        
        $this->echoCallback($callback);
    }
}