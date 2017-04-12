<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;

/**
 * Class Car
 * @package App
 */
class Formatter
{
    public static function currency($value, $indicator = false) {
        setlocale(LC_MONETARY, 'de_DE');

        return ($indicator && $value > 0 ? '+' : '' ) . number_format($value,2, ",", ".") . ' &euro;';
    }

    public static function date($value) {
        Date::setLocale('de');
        return (new Date($value))->format('d.m.Y');
    }

    public static function indicatedCurrency($value, $indicator = false) {
        $class = $value > 0 ? 'success' : 'danger';

        return '<span class="label label-'.$class.'">' . Formatter::currency($value, $indicator) . '</span>';
    }

    public static function dateDifference($date1, $date2) {
        return (new Date($date1))->diffForHumans(new Date($date2));
    }
}
