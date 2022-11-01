<?php

namespace GTG\Pay\Endpoints;

use GTG\Pay\Endpoints\Endpoint;
use GTG\Pay\Pay;
use GTG\Pay\Routes;

class Subscriptions extends Endpoint 
{
    public function get(array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::subscriptions("get"), 
            $data
        );
    }

    public function getOne(int $subscriptionId, array $data = []) 
    {
        return $this->pay->request(
            self::GET, 
            Routes::subscriptions("get-one", [
                "subscription_id" => $subscriptionId
            ]), 
            $data
        );
    }

    public function create(array $data = []) 
    {
        return $this->pay->request(self::POST, Routes::subscriptions("create"), $data);
    }

    public function update(int $subscriptionId, array $data = []) 
    {
        return $this->pay->request(
            self::PUT, 
            Routes::subscriptions("update", [
                "subscription_id" => $subscriptionId
            ]), 
            $data
        );
    }

    public function delete(int $subscriptionId, array $data = []) 
    {
        return $this->pay->request(
            self::DELETE, 
            Routes::subscriptions("delete", [
                "subscription_id" => $subscriptionId
            ]), 
            $data
        );
    }

    public function card(int $subscriptionId, array $data = []) 
    {
        return $this->pay->request(
            self::PATCH, 
            Routes::subscriptions("update-card", [
                "subscription_id" => $subscriptionId
            ]), 
            $data
        );
    }

    public function metadata(int $subscriptionId, array $data = []) 
    {
        return $this->pay->request(
            self::PATCH, 
            Routes::subscriptions("update-metadata", [
                "subscription_id" => $subscriptionId
            ]), 
            $data
        );
    }

    public function gateway(int $subscriptionId, array $data = []) 
    {
        return $this->pay->request(
            self::PATCH, 
            Routes::subscriptions("update-gateway", [
                "subscription_id" => $subscriptionId
            ]), 
            $data
        );
    }

    public function paymentMethod(int $subscriptionId, array $data = []) 
    {
        return $this->pay->request(
            self::PATCH, 
            Routes::subscriptions("update-payment-method", [
                "subscription_id" => $subscriptionId
            ]), 
            $data
        );
    }

    public function startDate(int $subscriptionId, array $data = []) 
    {
        return $this->pay->request(
            self::PATCH, 
            Routes::subscriptions("update-start-date", [
                "subscription_id" => $subscriptionId
            ]), 
            $data
        );
    }

    public function minimumPrice(int $subscriptionId, array $data = []) 
    {
        return $this->pay->request(
            self::PATCH, 
            Routes::subscriptions("update-minimum-price", [
                "subscription_id" => $subscriptionId
            ]), 
            $data
        );
    }

    public function activateManualBilling(int $subscriptionId, array $data = []) 
    {
        return $this->pay->request(
            self::POST, 
            Routes::subscriptions("activate-manual-billing", [
                "subscription_id" => $subscriptionId
            ]), 
            $data
        );
    }

    public function deactivateManualBilling(int $subscriptionId, array $data = []) 
    {
        return $this->pay->request(
            self::DELETE, 
            Routes::subscriptions("deactivate-manual-billing", [
                "subscription_id" => $subscriptionId
            ]), 
            $data
        );
    }
}