<?php

namespace Src\App\Controllers\Admin;

use Src\App\Controllers\Admin\TemplateController;
use Src\Models\Config;
use Src\Models\User;
use Src\Models\UserType;

class AdminController extends TemplateController  
{
    public function index(array $data): void 
    {
        $this->addData();

        $dbUserCounts = (new User())->get([], 'utip_id, COUNT(*) as users_count')->group('utip_id')->fetch('count');
        if($dbUserCounts) {
            foreach($dbUserCounts as $dbUserCount) {
                $usersCount[$dbUserCount->utip_id] = $dbUserCount->users_count;
            }
        }

        $this->render('admin/index', [
            'configMetas' => (new Config())->getGroupedMetas(['login_img', 'logo', 'logo_icon', 'style']),
            'userTypes' => (new UserType())->get()->fetch(true),
            'usersCount' => $usersCount
        ]);
    }

    public function system(array $data): void 
    {
        $callback = [];
        $objects = [];

        foreach(['style', 'logo', 'logo_icon', 'login_img'] as $attr) {
            $objects[] = (new Config())->loadData([
                'meta' => $attr,
                'value' => $data[$attr]
            ]);
        }

        if(!(new Config())->saveMany($objects)) {
            $errors = [];
            foreach($objects as $object) {
                $errors[$object->meta] = $object->getFirstError($object->meta);
            }

            $this->setMessage('error', _('Erros de validação! Verifique os campos.'))
                ->setErrors($errors)->APIResponse($callback, 422);
            return;
        }
        
        $this->setMessage('success', _('Configurações atualizadas com sucesso!'))->APIResponse($callback, 200);
    }
}