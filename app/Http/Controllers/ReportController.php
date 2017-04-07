<?php

namespace App\Http\Controllers;

use App\Car;
use App\Invoice;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('report.index');
    }

    public function create() {
        $fromDate = (new Date(request('from_date')))->format('Y-m-d');
        $toDate = (new Date(request('to_date')))->format('Y-m-d');

        $purchasedCars = Car::query()->where(function(\Illuminate\Database\Eloquent\Builder $query) use($fromDate, $toDate) {
            $query->whereBetween('purchase_date', [$fromDate, $toDate]);
        })->orderBy('purchase_date', 'ASC')->get();

        $selledCars = Car::query()->where(function(\Illuminate\Database\Eloquent\Builder $query) use($fromDate, $toDate) {
            $query->whereBetween('sale_date', [$fromDate, $toDate]);
        })->get();

        $expenses = Invoice::query()->where(function(\Illuminate\Database\Eloquent\Builder $query) use($fromDate, $toDate) {
            $query->whereBetween('date', [$fromDate, $toDate]);
            $query->whereNull('car_id');
        })->get();


        return view('report.index', ['data' => [
            'purchased' => $purchasedCars,
            'selled' => $selledCars,
            'expenses' => $expenses
        ]]);
    }
}
