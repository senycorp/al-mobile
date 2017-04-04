<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Car
 * @package App
 */
class Car extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'chassis_number',
        'purchase_date',
        'purchase_price',
        'sale_date',
        'sale_price'
    ];

    /**
     * Get invoices of car
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
}
