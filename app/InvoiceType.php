<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InvoiceType
 * @package App
 */
class InvoiceType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
    ];

    /**
     * Get invoices of type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
}
