<?php

namespace GTG\Pay\Endpoints;

use GTG\Pay\Endpoints\Endpoint;
use GTG\Pay\Pay;
use GTG\Pay\Routes;

class Plans extends Endpoint 
{
    public function get(array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::plans("get"), 
            $data
        );
    }

    public function getOne(int $planId, array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::plans("get-one", [
                "plan_id" => $planId
            ]), 
            $data
        );
    }

    public function create(array $data = []) 
    {
        return $this->pay->request(
            self::POST, 
            Routes::plans("create"), 
            $data
        );
    }

    public function update(int $planId, array $data = []) 
    {
        return $this->pay->request(
            self::PUT, 
            Routes::plans("update", [
                "plan_id" => $planId
            ]), 
            $data
        );
    }

    public function delete(int $planId, array $data = []) 
    {
        return $this->pay->request(
            self::DELETE, 
            Routes::plans("delete", [
                "plan_id" => $planId
            ]), 
            $data
        );
    }

    public function metadata(int $planId, array $data = []) 
    {
        return $this->pay->request(
            self::PATCH, 
            Routes::plans("metadata", [
                "plan_id" => $planId
            ]), 
            $data
        );
    }
}