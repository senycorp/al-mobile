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
    public static function currency($value) {
        setlocale(LC_MONETARY, 'de_DE');
        return money_format('%!n €', $value);
    }

    public static function date($value) {
        return (new Date($value))->format('d.m.Y');
    }
}
