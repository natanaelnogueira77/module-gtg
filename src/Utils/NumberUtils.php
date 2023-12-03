<?php 

namespace Src\Utils;

class NumberUtils 
{
    public static function numberInFull(string|int $number): string 
    {
        $inFull = '';
        $str = "{$number}";
        $pts = str_split($str);

        $groups = [
            'sing' => ['', _('mil'), _('milhão'), _('bilhão'), _('trilhão'), _('quatrilhão'), _('quintilhão'), _('sextilhão')],
            'plur' => ['', _('mil'), _('milhões'), _('bilhões'), _('trilhões'), _('quatrilhões'), _('quintilhões'), _('sextilhões')]
        ];

        $u = ['', _('um'), _('dois'), _('três'), _('quatro'), _('cinco'), _('seis'), _('sete'), _('oito'), _('nove')];
        $d10 = [_('dez'), _('onze'), _('doze'), _('treze'), _('quatorze'), _('quinze'), _('dezesseis'), _('dezesete'), _('dezoito'), _('dezenove')];
        $d = ['', _('dez'), _('vinte'), _('trinta'), _('quarenta'), _('cinquenta'), _('sessenta'), _('setenta'), _('oitenta'), _('noventa')];
        $c = ['', _('cento'), _('duzentos'), _('trezentos'), _('quatrocentos'), _('quinhentos'), _('seiscentos'), _('setecentos'), _('oitocentos'), _('novecentos')];

        $count = count($pts);

        for($i = 0; $i < count($pts); $i++) {
            $df = $count - $i;
            $grp = floor(($df - 1) / 3);

            if($df % 3 == 1) {
                $type = $pts[$i] != 1 ? 'plur' : 'sing';
                if($pts[$i - 2] == 1 && $pts[$i - 1] == 0 && $pts[$i] == 0) {
                    $inFull .= '';
                } elseif($pts[$i - 1] == 1) {
                    $inFull .= (
                        $count - 1 != $df && $pts[$i - 1] != 0 
                        ? ($grp >= 1 && $pts[$i - 2] == 0 ? ', ' : _(' e '))
                        : ''
                    ) . $d10[$pts[$i]];
                } else {
                    $inFull .= (
                        $count != $df && $pts[$i] != 0 
                        ? ($grp >= 1 && $pts[$i - 2] == 0 && $pts[$i - 1] == 0 ? ', ' : _(' e '))
                        : ''
                    ) . $u[$pts[$i]];
                }

                if(!($pts[$i - 2] == 0 && $pts[$i - 1] == 0 && $pts[$i] == 0)) {
                    $inFull .= ' ' . $groups[$type][$grp];
                }
            } elseif($df % 3 == 2) {
                if($pts[$i - 1] == 1 && $pts[$i] == 0 && $pts[$i + 1] == 0) {
                    $inFull .= '';
                } elseif($pts[$i] != 1) {
                    $inFull .= (
                        $count != $df && $pts[$i] != 0 
                        ? ($grp >= 1 && $pts[$i - 1] == 0 ? ', ' : _(' e '))
                        : ''
                    ) . $d[$pts[$i]];
                }
            } elseif($df % 3 == 0) {
                if($pts[$i] == 1 && $pts[$i + 1] == 0 && $pts[$i + 2] == 0) {
                    $inFull .= ($count != $df && $pts[$i] != 0 ? _(' e ') : '') . _('cem');
                } else {
                    $inFull .= ($count != $df && $pts[$i] != 0 ? ', ' : '') . $c[$pts[$i]];
                }
            }
        }

        return $inFull;
    }

    public static function priceInFull(string|int|float $number, string $currency = 'BRL'): string 
    {
        $cents = null;
        if($number > intval($number)) {
            $cents = intval(substr('' . number_format($number, 2) . '', -2, 2));
        }

        if($currency == 'BRL') {
            $firstCurrency = 'reais';
            $secondCurrency = 'centavos';
        }

        return self::numberInFull(intval($number)) . " {$firstCurrency}" 
            . ($cents ? _(' e ') . self::numberInFull($cents) . " {$secondCurrency}" : '');
    }
}