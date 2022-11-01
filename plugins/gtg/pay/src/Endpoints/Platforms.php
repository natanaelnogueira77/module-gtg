<?php

namespace GTG\Pay\Endpoints;

use GTG\Pay\Endpoints\Endpoint;
use GTG\Pay\Pay;
use GTG\Pay\Routes;

class Platforms extends Endpoint 
{
    public function getOne(int $platformId, array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::platforms("get-one", [
                "platform_id" => $platformId
            ]), 
            $data
        );
    }
}