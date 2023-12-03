<?php 

namespace Src\Utils;

use DateInterval;
use DateTime;
use DateTimeImmutable;

class DateUtils 
{
    public static function getMonths(): array 
    {
        return [
            1 => _('Janeiro'),
            2 => _('Fevereiro'),
            3 => _('Março'),
            4 => _('Abril'),
            5 => _('Maio'),
            6 => _('Junho'),
            7 => _('Julho'),
            8 => _('Agosto'),
            9 => _('Setembro'),
            10 => _('Outubro'),
            11 => _('Novembro'),
            12 => _('Dezembro')
        ];
    }
    
    public static function getWeekdays(): array 
    {
        return [
            0 => _('Domingo'),
            1 => _('Segunda-feira'),
            2 => _('Terça-feira'),
            3 => _('Quarta-feira'),
            4 => _('Quinta-feira'),
            5 => _('Sexta-feira'),
            6 => _('Sábado')
        ];
    }

    public static function getDateAsDateTime(string $date): DateTime 
    {
        return new DateTime($date);
    }

    public static function isDateOnWeekend(string $date): bool 
    {
        return in_array(self::getDateAsDateTime($date)->format('N'), [6, 7]);
    }

    public static function isDateAfter(string $date1, string $date2): bool
    {
        return self::getDateAsDateTime($date1)->getTimestamp() > self::getDateAsDateTime($date2)->getTimestamp();
    }

    public static function isDateBefore(string $date1, string $date2): bool  
    {
        return self::getDateAsDateTime($date1)->getTimestamp() < self::getDateAsDateTime($date2)->getTimestamp();
    }

    public static function getNextDay(string $date): DateTime 
    {
        $inputDate = self::getDateAsDateTime($date);
        $inputDate->modify('+1 day');
        return $inputDate;
    }

    public static function sumIntervals(array $intervals): DateInterval
    {
        $date = new DateTime('00:00:00');
        foreach($intervals as $interval) {
            $date->add($interval);
        }
        
        return (new DateTime('00:00:00'))->diff($date);
    }

    public static function getDateFromInterval(string $pattern, DateInterval $interval): DateTimeImmutable
    {
        return new DateTimeImmutable($interval->format($pattern));
    }

    public static function getDateFromString(string $pattern, string $str): DateTimeImmutable|false
    {
        return DateTimeImmutable::createFromFormat($pattern, $str);
    }

    public static function getFirstDayOfMonth(string $date): DateTime
    {
        return new DateTime(date('Y-m-1', self::getDateAsDateTime($date)->getTimestamp()));
    }

    public static function getLastDayOfMonth(string $date): DateTime
    {
        return new DateTime(date('Y-m-t', self::getDateAsDateTime($date)->getTimestamp()));
    }

    public static function getNextWeekday(string $date, array $weekdays = []): string
    {
        $weekdays = count($weekdays) > 0 ? $weekdays : [1, 2, 3, 4, 5, 6, 7];
        $dt = (new DateTime($date));
        $dt->modify('+1 day');

        while(!in_array($dt->format('N'), $weekdays)) {
            $dt->modify('+1 day');
        }

        return $dt->format('Y-m-d');
    }

    public static function getSecondsFromDateInterval(DateInterval $interval): int 
    {
        $d1 = new DateTimeImmutable();
        $d2 = $d1->add($interval);
        return $d2->getTimestamp() - $d1->getTimestamp();
    }

    public static function getTimeStringFromSeconds(int $seconds): string
    {
        return sprintf(
            '%02d:%02d:%02d', 
            intdiv($seconds, 3600), 
            intdiv($seconds % 3600, 60), 
            $seconds - ($h * 3600) - ($m * 60)
        );
    }
}