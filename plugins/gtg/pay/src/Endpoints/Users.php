<?php

namespace GTG\Pay\Endpoints;

use GTG\Pay\Endpoints\Endpoint;
use GTG\Pay\Pay;
use GTG\Pay\Routes;

class Users extends Endpoint 
{
    public function getOne(array $data = []) 
    {
        return $this->pay->request(self::GET, Routes::users("get-one"), $data);
    }

    public function create(array $data = []) 
    {
        return $this->pay->request(self::POST, Routes::users("create"), $data);
    }
}