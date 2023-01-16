<?php

// Algumas funções de transformar data e hora.
function getDateAsDateTime($date) 
{
    return is_string($date) ? new DateTime($date) : $date;
}

function isWeekend(string $date, ?array $days = []): bool 
{
    $inputDate = getDateAsDateTime($date);
    return in_array($inputDate->format('N'), $days);
}

function isBefore(string $date1, string $date2): bool  
{
    $inputDate1 = getDateAsDateTime($date1);
    $inputDate2 = getDateAsDateTime($date2);
    return $inputDate1 <= $inputDate2;
}

function getNextDay(string $date): DateTime 
{
    $inputDate = getDateAsDateTime($date);
    $inputDate->modify('+1 day');
    return $inputDate;
}

function sumIntervals(array $intervals)
{
    $date = new DateTime('00:00:00');

    foreach($intervals as $interval) {
        $date->add($interval);
    }
    
    return (new DateTime('00:00:00'))->diff($date);
}

function subtractIntervals(array $intervals, $hours) 
{
    $date = new DateTime('00:00:00');
    $workday = DateInterval::createFromDateString("{$hours} hours");
    $exitTime = (new DateTimeImmutable());
    
    if(!$interval) {
        return $exitTime->add($workday);
    } else {
        foreach($intervals as $interval) {
            $date->add($interval);
        }
        $workday->sub($date);

        return $exitTime->add($workday);
    }
}

function getDateFromInterval($interval) 
{
    return new DateTimeImmutable($interval->format('%H:%i:%s'));
}

function getDateFromString($str) 
{
    return DateTimeImmutable::createFromFormat('H:i:s', $str);
}

function getFirstDayOfMonth($date) 
{
    $time = getDateAsDateTime($date)->getTimestamp();
    return new DateTime(date('Y-m-1', $time));
}

function getLastDayOfMonth($date): DateTime
{
    $time = getDateAsDateTime($date)->getTimestamp();
    return new DateTime(date('Y-m-t', $time));
}

function getNextWeekday(string $date, ?array $weekdays = []): string
{
    $weekday = (new DateTime($date))->format("N");
    $key = array_search($weekday, $weekdays);
    if(is_array($weekdays)) {
        $count = count($weekdays);
        if($count > 0) {
            $index = ($key + 1) % count($weekdays);
            $days = $weekdays[$index] - $weekday;
        }
    }
    
    if($days <= 0) $days = 7 + $days;
    $newDate = date('Y-m-d', strtotime($date . " + {$days} days"));

    return $newDate;
}

function getSecondsFromDateInterval(DateInterval $interval): int 
{
    $d1 = new DateTimeImmutable();
    $d2 = $d1->add($interval);
    return $d2->getTimestamp() - $d1->getTimestamp();
}

function getTimeStringFromSeconds(int $seconds): string
{
    $h = intdiv($seconds, 3600);
    $m = intdiv($seconds % 3600, 60);
    $s = $seconds - ($h * 3600) - ($m * 60);
    return sprintf('%02d:%02d:%02d', $h, $m, $s);
}

function isDateAfter($date1, $date2): bool
{
    $ts1 = (new DateTime($date1))->getTimestamp();
    $ts2 = (new DateTime($date2))->getTimestamp();

    if($ts1 > $ts2) {
        return true;
    } else {
        return false;
    }
}