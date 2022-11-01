<?php

namespace GTG\Pay\Endpoints;

use GTG\Pay\Endpoints\Endpoint;
use GTG\Pay\Pay;
use GTG\Pay\Routes;

class Customers extends Endpoint 
{
    public function get(array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::customers("get"), 
            $data
        );
    }

    public function getOne(int $customerId, array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::customers("get-one", [
                "customer_id" => $customerId
            ]), 
            $data
        );
    }

    public function getByUser(array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::customers("get-by-user"), 
            $data
        );
    }

    public function create(array $data = []) 
    {
        return $this->pay->request(
            self::POST, 
            Routes::customers("create"), 
            $data
        );
    }

    public function update(int $customerId, array $data = []) 
    {
        return $this->pay->request(
            self::PUT, 
            Routes::customers("update", [
                "customer_id" => $customerId
            ]), 
            $data
        );
    }
}