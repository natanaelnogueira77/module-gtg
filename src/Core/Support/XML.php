<?php

namespace Src\Core\Support;

use Exception;

class XML 
{
    public $xml;

    public function __construct(array $data = [])
    {
        if($data) {
            $this->xml = $data['xml'];
        }
    }

    public function setData(array $data = []): void
    {
        $this->xml = $data['xml'];
    }

    public function send(array $data = []) 
    {
        $ch = curl_init();

        if(!$data['url']) {
            throw new Exception('O método de envio de XML precisa de um URL!');
        }

        if(!$data['method']) $data['method'] = 'GET';
        if(!in_array($data['method'], ['GET', 'POST', 'PUT', 'DELETE'])) {
            throw new Exception('O método de envio de XML é inválido!');
        }

        curl_setopt($ch, CURLOPT_URL, $data['url']);
        if($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->xml);
        }
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}