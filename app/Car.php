<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
        'mobile_id',
        'tax',
        'invoice_data'
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
        if (!$this->sale_date) return null;
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

    public function sell($date, $price, $account=false) {
        $this->fill([
            'sale_date' => $date,
            'sale_price' => $price,
        ])->save();

        $this->invoices()->create([
            'title' => 'Verkaufsbeleg',
            'price' => $this->sale_price,
            'date' => $this->sale_date,
            'account' => $account,
            'description' => 'Verkaufsbeleg für ' . $this->title . ' mit FG ' . $this->chassis_number,
            'sale_invoice' => 1,
            'user_id' => Auth::user()->id
        ]);
    }

    public function getTaxIdentifier() {
        if ($this->tax)
            return '§25a';

        return '19%';
    }

    public function is25(){
        return ($this->tax) ? 'Ja' : 'Nein';
    }

    public function unsell() {
        $this->fill([
            'sale_date' => null,
            'sale_price' => null
        ]);

        $this->save();

        Invoice::where('sale_invoice', 1)->where('car_id', $this->id)->get()[0]->delete();
    }

    /**
     * Get purchase price
     *
     * @param $value
     * @return string
     */
    public function getSalePrice() {
        if (!$this->sale_price) return null;
        return Formatter::currency($this->sale_price);
    }

    public function getCostsWithExpenses() {
        return Formatter::currency($this->sale_price - ($this->purchase_price + $this->totalExpense()));
    }

    public function getCostsWithoutExpenses() {
        return Formatter::currency($this->sale_price - $this->purchase_price);
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

    public function hasInvoiceData() {
        return $this->invoice_data;
    }

    public function isSelled() {
        return $this->sale_date;
    }

    public function getSaleInvoice() {
        return Invoice::where('car_id', '=', $this->id)->where('sale_invoice', '=', '1')->get()[0];
    }

    public function getPurchaseInvoice() {
        return Invoice::where('car_id', '=', $this->id)->where('purchase_invoice', '=', '1')->get()[0];
    }
}
