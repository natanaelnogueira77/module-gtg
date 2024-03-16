<?php

namespace GTG\MVC\Utils;

use GTG\MVC\Exceptions\ExcelGeneratorException;

class ExcelGenerator 
{
    private string $content = '';

    public function __construct(
        private array $data = [],
        private string $filename = ''
    )
    {}

    public function setData(array $data = []): self
    {
        $this->data = $data;
        return $this;
    }

    public function setFilename(string $filename = ''): self 
    {
        $this->filename = $filename;
        return $this;
    }

    public function render(): self
    {
        $this->setContentFromData();
        return $this;
    }

    private function setContentFromData(): void 
    {
        $this->content = '';
        $this->transformDataToContent();
    }

    private function transformDataToContent(): void
    {
        if(!$this->data) {
            throw new ExcelGeneratorException('Error at transforming data: no data was settled!');
        }

        foreach($this->data as $row) {
            $this->content .= implode("\t", array_values(self::assembleRow($row))) . "\r\n";
        }
    }

    private static function assembleRow(array $row): array 
    {
        array_walk($row, function (&$str) {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            if(strstr($str, '"')) {
                $str = '"' . str_replace('"', '""', $str) . '"';
            }
        });

        return $row;
    }

    public function stream(): void 
    {
        if(!$this->filename) {
            throw new ExcelGeneratorException('Error at stream: no filename was settled!');
        }

        header("Content-Disposition: attachment; filename=\"{$this->filename}.xls\"");
        header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
        header("Content-type:   application/x-msexcel; charset=utf-8");
        header("Pragma: no-cache");

        echo utf8_decode($this->content);
    }
}