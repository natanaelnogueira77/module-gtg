<?php

namespace GTG\Pay\Endpoints;

use GTG\Pay\Endpoints\Endpoint;
use GTG\Pay\Pay;
use GTG\Pay\Routes;

class Addresses extends Endpoint 
{
    public function get(int $customerId, array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::addresses("get", [
                "customer_id" => $customerId
            ]), 
            $data
        );
    }

    public function getOne(int $customerId, int $addressId, array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::addresses("get-one", [
                "customer_id" => $customerId,
                "address_id" => $addressId
            ]), 
            $data
        );
    }

    public function create(int $customerId, array $data = []) 
    {
        return $this->pay->request(
            self::POST, 
            Routes::addresses("create", [
                "customer_id" => $customerId
            ]), 
            $data
        );
    }

    public function update(int $customerId, int $addressId, array $data = []) 
    {
        return $this->pay->request(
            self::PUT, 
            Routes::addresses("update", [
                "customer_id" => $customerId,
                "address_id" => $addressId
            ]), 
            $data
        );
    }

    public function delete(int $customerId, int $addressId) 
    {
        return $this->pay->request(
            self::DELETE, 
            Routes::addresses("delete", [
                "customer_id" => $customerId,
                "address_id" => $addressId
            ]), 
            $data
        );
    }
}