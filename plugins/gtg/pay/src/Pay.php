<?php

namespace GTG\Pay;

use Exception;
use GTG\Pay\Endpoints\Addresses;
use GTG\Pay\Endpoints\Cards;
use GTG\Pay\Endpoints\Checkout;
use GTG\Pay\Endpoints\Customers;
use GTG\Pay\Endpoints\Plans;
use GTG\Pay\Endpoints\Platforms;
use GTG\Pay\Endpoints\Subscriptions;
use GTG\Pay\Endpoints\Orders;
use GTG\Pay\Endpoints\Users;
use GTG\Pay\Exceptions\AppException;
use GTG\Pay\Exceptions\RequestException;
use GTG\Pay\Exceptions\ValidationException;
use GTG\Pay\Routes;
use ReflectionClass;

class Pay 
{
    const VERSION = "2.0.0";

    const TEST_BASE = "https://gtg.software/dev/gtg-pay";
    const LIVE_BASE = "https://gtgpay.link";
    const LOCAL_BASE = "http://localhost/gtg-pay/v2";

    private $platform_key;
    private $mode;
    private $base;
    private $user_id;
    private $users;
    private $addresses;
    private $platforms;
    private $orders;
    private $cards;
    private $customers;
    private $subscriptions;
    private $plans;
    private $checkout;
    private $error;

    public function __construct(string $key, string $mode = "test") 
    {
        $this->platform_key = $key;
        $this->getMode($mode);
        $this->getBase();

        $this->users = new Users($this);
        $this->addresses = new Addresses($this);
        $this->platforms = new Platforms($this);
        $this->orders = new Orders($this);
        $this->cards = new Cards($this);
        $this->customers = new Customers($this);
        $this->subscriptions = new Subscriptions($this);
        $this->plans = new Plans($this);
        $this->checkout = new Checkout($this);
    }

    public function getMode(string $mode): void
    {
        if($mode == "test") {
            $this->mode = "test";
        } elseif($mode == "live") {
            $this->mode = "live";
        } elseif($mode == "local") {
            $this->mode = "local";
        } else {
            $this->mode = "test";
        }
    }

    public function getBase(): void
    {
        if($this->mode == "test") {
            $this->base = self::TEST_BASE;
        } elseif($this->mode == "live") {
            $this->base = self::LIVE_BASE;
        } elseif($this->mode == "local") {
            $this->base = self::LOCAL_BASE;
        }
    }

    public function connectUser(int $id): void
    {
        $this->user_id = $id;
    }

    public function users(): Users
    {
        return $this->users;
    }

    public function addresses(): Addresses
    {
        return $this->addresses;
    }

    public function platforms(): Platforms
    {
        return $this->platforms;
    }

    public function orders(): Orders
    {
        return $this->orders;
    }

    public function cards(): Cards
    {
        return $this->cards;
    }

    public function customers(): Customers
    {
        return $this->customers;
    }

    public function subscriptions(): Subscriptions
    {
        return $this->subscriptions;
    }

    public function plans(): Plans
    {
        return $this->plans;
    }

    public function checkout(): Checkout
    {
        return $this->checkout;
    }

    public function request(string $method, string $uri, array $data = []) 
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->base . "/" . $uri);

            $data["token"] = $this->platform_key;
            $data["user_id"] = $this->user_id;

            switch($method) {
                case "GET": 
                    curl_setopt($ch, CURLOPT_URL, $this->base . "/" . $uri . (
                        $data ? "?" . http_build_query($data) : ""
                    ));

                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    break;
                case "POST":
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    break;
                case "PUT":
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    break;
                case "DELETE": 
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    break;
                case "PATCH": 
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    break;
            }

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if($httpCode != 200) {
                throw new RequestException($httpCode, curl_error($ch));
            }

            curl_close($ch);

            $response = json_decode($result, true);
            if($response["error"]) {
                if($response["errors"]) {
                    throw new ValidationException($response["errors"], $response["error"]);
                }
                throw new AppException($response["error"]);
            }

            if(!$response["success"]) {
                throw new AppException("Lamentamos, mas a requisição não teve sucesso!");
            }

            if(isset($response["content"])) {
                return $response["content"];
            }

            return null;
        } catch(AppException $e) {
            $this->error = $e;
        }
    }

    public function error()
    {
        return $this->error;
    }

    public function getErrors(): ?array
    {
        if($this->error) {
            if((new ReflectionClass($this->error))->getShortName() == "ValidationException") {
                return $this->error->getErrors();
            }
        }

        return null;
    }
}