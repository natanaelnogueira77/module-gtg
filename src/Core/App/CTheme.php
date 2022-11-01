<?php

namespace Src\Core\App;

use League\Plates\Engine;
use Src\Core\App\CModel;

class CTheme extends CModel 
{
    public function __construct($router)
    {
        parent::__construct($router);
        $this->setTheme('architect-ui', [
            'router' => $this->router,
            'expiredSession' => [
                'check' => $this->getRoute('CEAWLogin.check'),
                'expired' => $this->getRoute('CEAWLogin.expiredSession')
            ],
            'ml' => [
                'load' => $this->getRoute('mediaLibrary.load'),
                'add' => $this->getRoute('mediaLibrary.add'),
                'delete' => $this->getRoute('mediaLibrary.delete')
            ]
        ]);
    }
}