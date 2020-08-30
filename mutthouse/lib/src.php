<?php
/*
this file contains source functions for Mo's Mutt House application
*/


/**
* Class MyDateTime
*
* Extends DateTime to include a sensible addMonth method.
*
* This class provides a method that will increment the month, and
* if the day is greater than the last day in the new month, it
* changes the day to the last day of that month. For example,
* If you add one month to 2014-10-31 using DateTime::add, the
* result is 2014-12-01. Using MyDateTime::addMonth the result is
* 2014-11-30.
*/
class MyDateTime extends DateTime
{

    public function addMonth($num = 1)
    {
        $date = $this->format('Y-n-j');
        list($y, $m, $d) = explode('-', $date);

        $m += $num;
        while ($m > 12)
        {
            $m -= 12;
            $y++;
        }

        $last_day = date('t', strtotime("$y-$m-1"));
        if ($d > $last_day)
        {
            $d = $last_day;
        }

        $this->setDate($y, $m, $d);
    }

}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

?>