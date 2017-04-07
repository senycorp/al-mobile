<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;

/**
 * Class Invoice
 * @package App
 */
class Invoice extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'price',
        'description',
        'car_id',
        'invoice_type_id',
        'date',
        'user_id',
    ];

    /**
     * Get assigned car
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function car()
    {
        return $this->belongsTo('App\Car');
    }

    /**
     * Get invoice type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo('App\InvoiceType');
    }

    /**
     * Get creator of invoice
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function hasConflict() {
        if ($this->car_id) {
            if ($this->car->sale_date) {
                return !(new Date($this->car->sale_date))->greaterThanOrEqualTo(new Date($this->date));
            }
        }

        return false;
    }

    public function getDate() {
        return Formatter::date($this->date);
    }

    public function getPrice() {
        return Formatter::currency($this->price);
    }

    public function setDateAttribute($value) {
        if ($value && is_string($value))
            $this->attributes['date'] = (new Date($value))->format('Y-m-d');
        else
            $this->attributes['date'] = $value;
    }
}
