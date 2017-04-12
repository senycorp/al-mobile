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
        'purchase_invoice',
        'sale_invoice',
        'tax',
        'account'
    ];

    public function isAssigned() {
        return $this->car_id;
    }

    public function isAccount() {
        return ($this->account) ? 'Ja' : 'Nein';
    }

    /**
     * Get assigned car
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function car()
    {
        return $this->belongsTo('App\Car');
    }

    public function getIndicatedPrice() {
        return Formatter::indicatedCurrency($this->price);
    }

    /**
     * Get invoice type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo('App\InvoiceType', 'invoice_type_id', 'id');
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
            if ((new Date($this->car->purchase_date))->greaterThan(new Date($this->date))) return true;

            if ($this->car->sale_date) {
                return !(new Date($this->car->sale_date))->greaterThanOrEqualTo(new Date($this->date));
            }
        }

        return false;
    }

    public function getTitle() {
        if ($this->invoice_type_id) return $this->type->title;

        return $this->title;
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

    public function getTaxIdentifier() {
        if ($this->tax)
            return '§25a';

        return '19%';
    }

    public function is25(){
        return ($this->tax) ? 'Ja' : 'Nein';
    }
}
