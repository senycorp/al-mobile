<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'date'
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
}
