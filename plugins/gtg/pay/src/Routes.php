<?php

namespace GTG\Pay;

use Exception;

class Routes 
{
    const PATH_BASE = "gtgpay";

    private static function replaceParams(string $path, array $params = []): string 
    {
        if($params) {
            foreach($params as $param => $value) {
                $path = str_replace('{' . $param . '}', $value, $path);
            }
        }

        return $path;
    }

    public static function platforms(string $op, array $params = []): string 
    {
        if($op == "get-one") {
            return self::PATH_BASE . self::replaceParams("/platforms/{platform_id}", [
                "platform_id" => $params["platform_id"]
            ]);
        }

        return "";
    }

    public static function users(string $op, array $params = []): string 
    {
        if($op == "get-one") {
            return self::PATH_BASE . self::replaceParams("/users/get-one");
        } elseif($op == "create") {
            return self::PATH_BASE . self::replaceParams("/users/create");
        }

        return "";
    }

    public static function addresses(string $op, array $params = []): string 
    {
        if($op == "get" || $op == "create") {
            return self::PATH_BASE . self::replaceParams("/customers/{customer_id}/addresses", [
                "customer_id" => $params["customer_id"]
            ]);
        } elseif($op == "get-one" || $op == "update" || $op == "delete") {
            return self::PATH_BASE . self::replaceParams("/customers/{customer_id}/addresses/{address_id}", [
                "customer_id" => $params["customer_id"],
                "address_id" => $params["address_id"]
            ]);
        }

        return "";
    }

    public static function orders(string $op, array $params = []): string 
    {
        if($op == "get" || $op == "create") {
            return self::PATH_BASE . self::replaceParams("/orders");
        } elseif($op == "get-one") {
            return self::PATH_BASE . self::replaceParams("/orders/{order_id}", [
                "order_id" => $params["order_id"]
            ]);
        } elseif($op == "close") {
            return self::PATH_BASE . self::replaceParams("/orders/{order_id}/closed", [
                "order_id" => $params["order_id"]
            ]);
        }

        return "";
    }

    public static function subscriptions(string $op, array $params = []): string 
    {
        if($op == "get" || $op == "create") {
            return self::PATH_BASE . self::replaceParams("/subscriptions");
        } elseif($op == "get-one" || $op == "update" || $op == "delete") {
            return self::PATH_BASE . self::replaceParams("/subscriptions/{subscription_id}", [
                "subscription_id" => $params["subscription_id"]
            ]);
        } elseif($op == "update-card") {
            return self::PATH_BASE . self::replaceParams("/subscriptions/{subscription_id}/card", [
                "subscription_id" => $params["subscription_id"]
            ]);
        } elseif($op == "update-metadata") {
            return self::PATH_BASE . self::replaceParams("/subscriptions/{subscription_id}/metadata", [
                "subscription_id" => $params["subscription_id"]
            ]);
        } elseif($op == "update-gateway") {
            return self::PATH_BASE . self::replaceParams("/subscriptions/{subscription_id}/gateway", [
                "subscription_id" => $params["subscription_id"]
            ]);
        } elseif($op == "update-payment-method") {
            return self::PATH_BASE . self::replaceParams("/subscriptions/{subscription_id}/payment-method", [
                "subscription_id" => $params["subscription_id"]
            ]);
        } elseif($op == "update-start-date") {
            return self::PATH_BASE . self::replaceParams("/subscriptions/{subscription_id}/start-at", [
                "subscription_id" => $params["subscription_id"]
            ]);
        } elseif($op == "update-minimum-price") {
            return self::PATH_BASE . self::replaceParams("/subscriptions/{subscription_id}/minimum-price", [
                "subscription_id" => $params["subscription_id"]
            ]);
        } elseif($op == "activate-manual-billing" || $op == "deactivate-manual-billing") {
            return self::PATH_BASE . self::replaceParams("/subscriptions/{subscription_id}/manual-billing", [
                "subscription_id" => $params["subscription_id"]
            ]);
        }

        return "";
    }

    public static function customers(string $op, array $params = []): string 
    {
        if($op == "get" || $op == "create") {
            return self::PATH_BASE . self::replaceParams("/customers");
        } elseif($op == "update" || $op == "get-one") {
            return self::PATH_BASE . self::replaceParams("/customers/{customer_id}", [
                "customer_id" => $params["customer_id"]
            ]);
        } elseif($op == "get-by-user") {
            return self::PATH_BASE . self::replaceParams("/customers/get-by-user");
        }

        return "";
    }

    public static function cards(string $op, array $params = []): string 
    {
        if($op == "get" || $op == "create") {
            return self::PATH_BASE . self::replaceParams("/customers/{customer_id}/cards", [
                "customer_id" => $params["customer_id"]
            ]);
        } elseif($op == "get-one" || $op == "update" || $op == "delete") {
            return self::PATH_BASE . self::replaceParams("/customers/{customer_id}/cards/{card_id}", [
                "customer_id" => $params["customer_id"],
                "card_id" => $params["card_id"]
            ]);
        } elseif($op == "renew") {
            return self::PATH_BASE . self::replaceParams("/customers/{customer_id}/cards/{card_id}/renew", [
                "customer_id" => $params["customer_id"],
                "card_id" => $params["card_id"]
            ]);
        }

        return "";
    }

    public static function plans(string $op, array $params = []): string 
    {
        if($op == "get" || $op == "create") {
            return self::PATH_BASE . self::replaceParams("/plans");
        } elseif($op == "get-one" || $op == "update" || $op == "delete") {
            return self::PATH_BASE . self::replaceParams("/plans/{plan_id}", [
                "plan_id" => $params["plan_id"]
            ]);
        } elseif($op == "metadata") {
            return self::PATH_BASE . self::replaceParams("/plans/{plan_id}/metadata", [
                "plan_id" => $params["plan_id"]
            ]);
        }

        return "";
    }

    public static function checkout(string $op, array $params = []): string 
    {
        if($op == "send") {
            return self::PATH_BASE . self::replaceParams("/checkout");
        }

        return "";
    }
}