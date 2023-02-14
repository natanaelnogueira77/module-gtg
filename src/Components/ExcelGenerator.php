<?php

namespace Src\Components;

use Exception;

class ExcelGenerator 
{
    private $data;
    private $filename;
    private $content = '';

    public function __construct(?array $data = null, ?string $filename = null)
    {
        $this->data = $data;
        $this->filename = $filename . '.xls';
    }

    public function setData(array $data = []): self
    {
        $this->data = $data;
        return $this;
    }

    public function setFilename(string $filename = ''): self 
    {
        $this->filename = $filename . '.xls';
        return $this;
    }

    public function render(): bool
    {
        try {
            if(!$this->data) {
                throw new Exception(_('Nenhum dado foi adicionado para a geração do Excel!'));
            }

            function cleanData(&$str) {
                $str = preg_replace("/\t/", "\\t", $str);
                $str = preg_replace("/\r?\n/", "\\n", $str);
                if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
            }

            if(!$this->filename) {
                $this->filename = 'website_data_' . date('Ymd') . '.xls';
            }

            $flag = false;
            $content = '';

            foreach($this->data as $row) {
                if(!$flag) {
                    $content .= implode("\t", array_keys($row)) . "\r\n";
                    $flag = true;
                }
                
                array_walk($row, __NAMESPACE__ . '\cleanData');
                $content .= implode("\t", array_values($row)) . "\r\n";
            }

            $this->content = $content;
        } catch(Exception $exception) {
            $this->error = $exception;
            return false;
        }

        return true;
    }

    public function stream(): void 
    {
        header("Content-Disposition: attachment; filename=\"{$this->filename}\"");
        header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
        header("Content-type:   application/x-msexcel; charset=utf-8");
        header("Pragma: no-cache");

        echo utf8_decode($this->content);
    }

    public function error(): ?Exception 
    {
        return $this->error;
    }
}