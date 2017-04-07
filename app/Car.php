<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;

/**
 * Class Car
 * @package App
 */
class Car extends Model
{
    protected $totalExpense = false;
    protected $conflicts = false;

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
        'sale_price',
        'user_id',
        'mobile_id'
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

    public function creator() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function totalExpense() {
        if ($this->totalExpense) return $this->totalExpense;

        $totalExpense = 0;

        foreach ($this->invoices()->get() as $invoice) {
            $totalExpense += $invoice->price;
        }

        $this->totalExpense = $totalExpense;

        return $this->totalExpense;
    }

    /**
     * Get total expense
     *
     * @return string
     */
    public function getTotalExpense() {
        return Formatter::currency($this->totalExpense());
    }

    /**
     * Get total costs
     *
     * @return string
     */
    public function getTotalCosts() {
        return Formatter::currency($this->purchase_price + $this->totalExpense());
    }

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getSaleDate()
    {
        return Formatter::date($this->sale_date);
    }

    /**
     * Get purchase date
     *
     * @param $value
     * @return mixed|string
     */
    public function getPurchaseDate() {
        return Formatter::date($this->purchase_date);
    }

    /**
     * Get purchase price
     *
     * @param $value
     * @return string
     */
    public function getPurchasePrice() {
        return Formatter::currency($this->purchase_price);
    }

    /**
     * Get purchase price
     *
     * @param $value
     * @return string
     */
    public function getSalePrice() {
        return Formatter::currency($this->sale_price);
    }

    public function getCostsWithExpenses() {
        return Formatter::currency($this->sale_price - $this->purchase_price);
    }

    public function getCostsWithoutExpenses() {
        return Formatter::currency($this->sale_price - ($this->purchase_price + $this->totalExpense()));
    }

    public function getConflicts() {
        if ($this->conflicts) return $this->conflicts;

        $conflicts = [];
        foreach ($this->invoices()->get() as $invoice) {
            if ((new Date($this->purchase_date))->greaterThan(new Date($invoice->date))) {
                $conflicts[] = $invoice;
            } else if($this->sale_date && !(new Date($this->sale_date))->greaterThanOrEqualTo(new Date($invoice->date))) {
                $conflicts[] = $invoice;
            }
        }

        $this->conflicts = $conflicts;

        return $this->conflicts;
    }

    public function setSaleDateAttribute($value) {
        if ($value && is_string($value))
            $this->attributes['sale_date'] = (new Date($value))->format('Y-m-d');
        else
            $this->attributes['sale_date'] = $value;
    }

    public function setPurchaseDateAttribute($value) {
        if ($value && is_string($value))
            $this->attributes['purchase_date'] = (new Date($value))->format('Y-m-d');
        else
            $this->attributes['purchase_date'] = $value;
    }
}
