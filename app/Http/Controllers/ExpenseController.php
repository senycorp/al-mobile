<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class ExpenseController extends Controller
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
        return view('expense.index');
    }

    /**
     * Create new expense
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request) {
        $data = $request->toArray();
        $data['user_id'] = Auth::user()->id;
        $invoice = Invoice::create($data);

        return redirect()->route('expense_detail', ['id' => $invoice->id]);
    }

    public function detail($id) {
        return view('expense.detail', ['expense' => Invoice::find($id)]);
    }

    public function getData() {
        $expenses = DB::table('invoices')->select(['id', 'title', 'price', 'date'])->whereNull('car_id');

        return Datatables::of($expenses)->make(true);
    }
}
