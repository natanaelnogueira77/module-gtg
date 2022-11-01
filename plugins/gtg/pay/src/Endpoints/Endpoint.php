<?php

namespace GTG\Pay\Endpoints;

use GTG\Pay\Pay;

abstract class Endpoint 
{
    /**
     * @var string
     */
    const POST = 'POST';
    /**
     * @var string
     */
    const GET = 'GET';
    /**
     * @var string
     */
    const PUT = 'PUT';
    /**
     * @var string
     */
    const DELETE = 'DELETE';

    /**
     * @var string
     */
    const PATCH = 'PATCH';
    /**
     * @var \GTG\Pay\Pay
     */
    protected $pay;

    public function __construct(Pay $pay) 
    {
        $this->pay = $pay;
    }
}