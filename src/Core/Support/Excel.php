<?php

namespace Src\Core\Support;

use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;

class Excel 
{
    public $data;
    public $filename;

    public function __construct(array $data = [])
    {
        if($data) {
            $this->data = $data;
        }
    }

    public function setData(array $data = []): void
    {
        $this->data = $data;
    }

    public function get(string $filename = ''): void
    {
        if(!$this->data) {
            throw new AppException('Nenhum dado foi adicionado!');
        }

        function cleanData(&$str) {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        }

        if(!$filename) {
            $this->filename = "website_data_" . date('Ymd') . ".xls";
        } else {
            $this->filename = $filename . ".xls";
        }

        header("Content-Disposition: attachment; filename=\"{$this->filename}\"");
        header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
        header("Content-type:   application/x-msexcel; charset=utf-8");
        header("Pragma: no-cache");
        
        $flag = false;
        $data = $this->data;
        $content = "";

        if($data) {
            foreach($data as $row) {
                if(!$flag) {
                    $content .= implode("\t", array_keys($row)) . "\r\n";
                    $flag = true;
                }
                
                array_walk($row, __NAMESPACE__ . '\cleanData');
                $content .= implode("\t", array_values($row)) . "\r\n";
            }
        }

        $content = utf8_decode($content);

        echo $content;
    }
}