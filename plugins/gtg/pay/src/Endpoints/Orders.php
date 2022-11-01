<?php

namespace GTG\Pay\Endpoints;

use GTG\Pay\Endpoints\Endpoint;
use GTG\Pay\Pay;
use GTG\Pay\Routes;

class Orders extends Endpoint 
{
    public function get(array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::orders("get"), 
            $data
        );
    }

    public function getOne(int $orderId, array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::orders("get-one", [
                "order_id" => $orderId
            ]), 
            $data
        );
    }

    public function create(array $data = []) 
    {
        return $this->pay->request(
            self::POST, 
            Routes::orders("create"), 
            $data
        );
    }

    public function close(int $orderId, array $data = []) 
    {
        return $this->pay->request(
            self::PATCH, 
            Routes::orders("close", [
                "order_id" => $orderId
            ]), 
            $data
        );
    }
}