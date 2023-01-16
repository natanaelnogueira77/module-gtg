<?php

function url(string $uri = null): string 
{
    if($uri) {
        if(strpos($uri, 'http://') !== false || strpos($uri, 'https://') !== false) {
            return $uri;
        }

        return ROOT . "/{$uri}";
    }

    return ROOT;
}

function message(string $message, string $type): array 
{
    return [
        'message' => $message, 
        'type' => $type
    ];
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit();
}

function addSuccessMsg(string $msg): void 
{
    $_SESSION['message'] = [
        'type' => 'success',
        'message' => $msg
    ];
}

function addErrorMsg(string $msg): void  
{
    $_SESSION['message'] = [
        'type' => 'error',
        'message' => $msg
    ];
}

function addInfoMsg(string $msg): void  
{
    $_SESSION['message'] = [
        'type' => 'info',
        'message' => $msg
    ];
}

function generatePassword(
    int $length = 12, 
    bool $upperCase = true, 
    bool $lowerCase = true, 
    bool $numbers = true, 
    bool $specials = false
): string {
    $ma = 'ABCDEFGHIJKLMNOPQRSTUVYXWZ';
    $mi = 'abcdefghijklmnopqrstuvyxwz';
    $nu = '0123456789';
    $si = '!@#$%¨&*()_+=';
    
    $password = '';
    if($upperCase) { $password .= str_shuffle($ma); }
    if($lowerCase) { $password .= str_shuffle($mi); }
    if($numbers) { $password .= str_shuffle($nu); }
    if($specials) { $password .= str_shuffle($si); }

    return substr(str_shuffle($password), 0, $length);
}

function verifyCell(string $tel): bool 
{
    $regex = "/^(?:(?:\+|00)?(55)\s?)?(?:\(?([1-9][0-9])\)?\s?)?(?:((?:9\d|[2-9])\d{3})\-?(\d{4}))$/";

    if(preg_match($regex, $tel)) {
        return true;
    } else {
        return false;
    }
}

function validateCNPJ(string $cnpj): bool 
{
	if(empty($cnpj)) {
		return false;
	}

	$cnpj = preg_replace("/[^0-9]/", "", $cnpj);
	$cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);

	if(strlen($cnpj) != 14) {
		return false;
	} elseif ($cnpj == '00000000000000' || 
		$cnpj == '11111111111111' || 
		$cnpj == '22222222222222' || 
		$cnpj == '33333333333333' || 
		$cnpj == '44444444444444' || 
		$cnpj == '55555555555555' || 
		$cnpj == '66666666666666' || 
		$cnpj == '77777777777777' || 
		$cnpj == '88888888888888' || 
		$cnpj == '99999999999999') {
		return false;
	 } else {
		$j = 5;
		$k = 6;
		$soma1 = 0;
		$soma2 = 0;
		for($i = 0; $i < 13; $i++) {
			$j = $j == 1 ? 9 : $j;
			$k = $k == 1 ? 9 : $k;
			$soma2 += ($cnpj[$i] * $k);
			if($i < 12) {
				$soma1 += ($cnpj[$i] * $j);
			}
			$k--;
			$j--;
		}
		$digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
		$digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;

		return (($cnpj[12] == $digito1) && ($cnpj[13] == $digito2));
	}
}

function validateCPF(string $cpf): bool
{
    $cpf = "$cpf";
    if(strpos($cpf, '-') !== false) {
        $cpf = str_replace('-', '', $cpf);
    }
    if(strpos($cpf, '.') !== false) {
        $cpf = str_replace('.', '', $cpf);
    }
    $sum = 0;
    $cpf = str_split( $cpf );
    $cpftrueverifier = array();
    $cpfnumbers = array_splice( $cpf , 0, 9 );
    $cpfdefault = array(10, 9, 8, 7, 6, 5, 4, 3, 2);
    for($i = 0; $i <= 8; $i++) {
        $sum += $cpfnumbers[$i]*$cpfdefault[$i];
    }
    $sumresult = $sum % 11;  
    if($sumresult < 2) {
        $cpftrueverifier[0] = 0;
    } else {
        $cpftrueverifier[0] = 11-$sumresult;
    }
    $sum = 0;
    $cpfdefault = array(11, 10, 9, 8, 7, 6, 5, 4, 3, 2);
    $cpfnumbers[9] = $cpftrueverifier[0];
    for($i = 0; $i <= 9; $i++) {
        $sum += $cpfnumbers[$i]*$cpfdefault[$i];
    }
    $sumresult = $sum % 11;
    if($sumresult < 2){
        $cpftrueverifier[1] = 0;
    } else {
        $cpftrueverifier[1] = 11 - $sumresult;
    }
    $returner = false;
    if($cpf == $cpftrueverifier) {
        $returner = true;
    }
    $cpfver = array_merge($cpfnumbers, $cpf);
    if(count(array_unique($cpfver)) == 1 || $cpfver == array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 0)) {
        $returner = false;
    }
    return $returner;
}

function cleanDocument(string $str): mixed 
{
    $str = str_replace(' ', '', $str);
    $str = str_replace('-', '', $str);
    $str = str_replace('.', '', $str);
    $str = str_replace('/', '', $str);
    return preg_replace('/[^A-Za-z0-9\-]/', '', $str);
}

function generateSlug(string $str): string
{
    $str = html_entity_decode($str);
    $str = mb_strtolower($str);
    $str = preg_replace('/(â|á|ã)/', 'a', $str);
    $str = preg_replace('/(ê|é)/', 'e', $str);
    $str = preg_replace('/(í|Í)/', 'i', $str);
    $str = preg_replace('/(ú)/', 'u', $str);
    $str = preg_replace('/(ó|ô|õ|Ô)/', 'o',$str);
    $str = preg_replace('/(_|\/|!|\?|#|&)/', '', $str);
    $str = preg_replace('/( )/', '-', $str);
    $str = preg_replace('/ç/', 'c', $str);
    $str = preg_replace('/(-[-]{1,})/', '-', $str);
    $str = preg_replace('/(,)/', '-', $str);
    $str = strtolower($str);
    return $str;
}

function writeIniFile(array $array, string $path, bool $hasSections = false)
{
    $content = ''; 
    if($hasSections) { 
        foreach($array as $key => $elem) { 
            $content .= '[' . $key . "]\n"; 
            foreach($elem as $key2 => $elem2) { 
                if(is_array($elem2)) { 
                    for($i = 0 ;$i < count($elem2); $i++) { 
                        $content .= $key2 . "[] = \"" . $elem2[$i] . "\"\n"; 
                    } 
                } elseif($elem2 == "") {
                    $content .= $key2 . " = \n"; 
                } else {
                    $content .= $key2 . " = \"" . $elem2 . "\"\n"; 
                }
            } 
        } 
    } else { 
        foreach($array as $key => $elem) { 
            if(is_array($elem)) { 
                for($i = 0; $i < count($elem); $i++) { 
                    $content .= $key . "[] = \"" . $elem[$i] . "\"\n"; 
                } 
            } elseif($elem == "") {
                $content .= $key . " = \n";
            } else {
                $content .= $key . " = \"" . $elem . "\"\n"; 
            }
        } 
    } 

    if(!$handle = fopen($path, 'w')) { 
        return false; 
    }

    $success = fwrite($handle, html_entity_decode($content));
    fclose($handle); 

    return $success; 
}

function numberInFull($number)
{
    $inFull = '';
    $str = "{$number}";
    $pts = str_split($str);

    $groups = [
        'sing' => ['', 'mil', 'milhão', 'bilhão', 'trilhão', 'quatrilhão', 'quintilhão', 'sextilhão'],
        'plur' => ['', 'mil', 'milhões', 'bilhões', 'trilhões', 'quatrilhões', 'quintilhões', 'sextilhões']
    ];

    $u = ['', 'um', 'dois', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove'];
    $d10 = ['dez', 'onze', 'doze', 'treze', 'quatorze', 'quinze', 'dezesseis', 'dezesete', 'dezoito', 'dezenove'];
    $d = ['', 'dez', 'vinte', 'trinta', 'quarenta', 'cinquenta', 'sessenta', 'setenta', 'oitenta', 'noventa'];
    $c = ['', 'cento', 'duzentos', 'trezentos', 'quatrocentos', 'quinhentos', 'seiscentos', 'setecentos', 'oitocentos', 'novecentos'];

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
                    ? ($grp >= 1 && $pts[$i - 2] == 0 ? ', ' : ' e ')
                    : ''
                ) . $d10[$pts[$i]];
            } else {
                $inFull .= (
                    $count != $df && $pts[$i] != 0 
                    ? ($grp >= 1 && $pts[$i - 2] == 0 && $pts[$i - 1] == 0 ? ', ' : ' e ')
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
                    ? ($grp >= 1 && $pts[$i - 1] == 0 ? ', ' : ' e ')
                    : ''
                ) . $d[$pts[$i]];
            }
        } elseif($df % 3 == 0) {
            if($pts[$i] == 1 && $pts[$i + 1] == 0 && $pts[$i + 2] == 0) {
                $inFull .= ($count != $df && $pts[$i] != 0 ? ' e ' : '') . 'cem';
            } else {
                $inFull .= ($count != $df && $pts[$i] != 0 ? ', ' : '') . $c[$pts[$i]];
            }
        }
    }

    return $inFull;
}