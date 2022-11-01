<?php

namespace GTG\Pay\Endpoints;

use GTG\Pay\Endpoints\Endpoint;
use GTG\Pay\Pay;
use GTG\Pay\Routes;

class Checkout extends Endpoint 
{
    public function send(array $data = []) 
    {
        return $this->pay->request(
            self::POST, 
            Routes::checkout("send"), 
            $data
        );
    }
}