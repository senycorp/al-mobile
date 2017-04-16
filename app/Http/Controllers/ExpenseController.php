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

        if (is_numeric($data['title'])) {
            $data['invoice_type_id'] = $data['title'];
            $data['title'] = null;
        }

        if ($data['in_out'] == 'out') {
            $data['price'] = -$data['price'];
        }

        $invoice = Invoice::create($data);

        return redirect()->route('expense_detail', ['id' => $invoice->id]);
    }

    public function detail($id) {
        return view('expense.detail', ['expense' => Invoice::find($id)]);
    }

    public function getData() {
        $expenses = DB::table('invoices')->select(['invoices.id', 'invoices.title as title', 'invoices.price', 'invoices.date', 'invoice_types.title as ititle'])->whereNull('car_id')
            ->leftJoin('invoice_types', 'invoices.invoice_type_id', '=', 'invoice_types.id');

        return Datatables::of($expenses)->make(true);
    }
}
