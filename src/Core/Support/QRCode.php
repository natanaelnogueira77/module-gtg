<?php

namespace Src\Core\Support;

use Src\Core\Exceptions\AppException;
use Src\Core\Exceptions\ValidationException;

class QRCode 
{
    public $url;
    public $e;
    public $s;
    public $t;

    public function getQRCode(string $url = ''): string
    {
        $this->url = url($url);
        $this->e = $this->e ? $this->e : 'H';
        $this->s = $this->s ? $this->s : 4;
        $this->t = $this->t ? $this->t : 'J';

        $qrCode = url() . '/plugins/qrcode/qr_img0.50j/php/qr_img.php?';
        $qrCode .= "d={$this->url}&e={$this->e}&s={$this->s}&t={$this->t}";

        return $qrCode;
    }

    public function type(string $type): QRCode
    {
        $this->t = $type;
        return $this;
    }

    public function size(int $size): QRCode 
    {
        $this->s = $size;
        return $this;
    }

    public function level(string $level): QRCode 
    {
        $this->e = $level;
        return $this;
    }
}