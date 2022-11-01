<?php

namespace GTG\Pay\Endpoints;

use GTG\Pay\Endpoints\Endpoint;
use GTG\Pay\Pay;
use GTG\Pay\Routes;

class Cards extends Endpoint 
{
    public function get(int $customerId, array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::cards("get", [
                "customer_id" => $customerId
            ]), 
            $data
        );
    }

    public function getOne(int $customerId, int $cardId, array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::cards("get-one", [
                "customer_id" => $customerId,
                "card_id" => $cardId
            ]), 
            $data
        );
    }

    public function create(int $customerId, array $data = []) 
    {
        return $this->pay->request(
            self::POST, 
            Routes::cards("create", [
                "customer_id" => $customerId
            ]), 
            $data
        );
    }

    public function update(int $customerId, int $cardId, array $data = []) 
    {
        return $this->pay->request(
            self::PUT, 
            Routes::cards("update", [
                "customer_id" => $customerId,
                "card_id" => $cardId
            ]), 
            $data
        );
    }

    public function delete(int $customerId, int $cardId, array $data = []) 
    {
        return $this->pay->request(
            self::PUT, 
            Routes::cards("delete", [
                "customer_id" => $customerId,
                "card_id" => $cardId
            ]), 
            $data
        );
    }

    public function renew(int $customerId, int $cardId, array $data = []) 
    {
        return $this->pay->request(
            self::PUT, 
            Routes::cards("renew", [
                "customer_id" => $customerId,
                "card_id" => $cardId
            ]), 
            $data
        );
    }
}