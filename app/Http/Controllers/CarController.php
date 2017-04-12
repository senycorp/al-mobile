<?php

namespace App\Http\Controllers;

use App\Car;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use Yajra\Datatables\Datatables;

class CarController extends Controller
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
        return view('car.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexStock()
    {
        return view('car.carstock');
    }

    /**
     * Create new car
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(Request $request) {
        $this->validate($request, [
            'title' => 'required',
            'purchase_date' => 'required',
            'purchase_price' => 'required',
            'chassis_number' => 'required|unique:cars|max:6|min:6',
            'mobile_id' => 'nullable|unique:cars',
            'tax' => 'required'
        ]);

        $data = $request->toArray();
        $data['user_id'] = Auth::user()->id;

        $car = Car::create($data);

        $car->invoices()->create([
            'title' => 'Einkaufsbeleg',
            'price' => -$car->purchase_price,
            'date' => $car->purchase_date,
            'account' => $data['account'],
            'description' => 'Einkaufsbeleg für ' . $car->title . ' mit FG ' . $car->chassis_number,
            'purchase_invoice' => 1,
            'user_id' => Auth::user()->id,
            'tax' => $car->tax
        ]);

        return redirect('/car/' . $car->id);
    }

    /**
     * Sell Car
     *
     * @param $id
     */
    public function sellCar(Request $request, $id) {
        $car = Car::findOrFail($id);
        $request['sale_date'] = (new Date($request['sale_date']))->format('Y-m-d');
        $request['purchase_date'] = $car->purchase_date;
        $this->validate($request, [
            'sale_date' => 'required|date|after_or_equal:purchase_date',
            'sale_price' => 'required'
        ]);

        Car::findOrFail($id)->sell($request['sale_date'], $request['sale_price'], $request['account']);

        return redirect()->back();
    }

    public function createInvoice(Request $request, $id) {
        $car = Car::findOrFail($id);
        $request['purchase_date'] = $car->purchase_date;
        $this->validate($request, [
            'invoice_title' => 'required',
            'invoice_price' => 'required|numeric',
            'invoice_date'  => 'required|date|after_or_equal:purchase_date',
            'invoice_description' => 'nullable',
            'invoice_tax' => 'required'
        ]);

        $data = $request->toArray();
        $data['invoice_type_id'] = null;
        if (is_numeric($data['invoice_title'])) {
            $data['invoice_type_id'] = $data['invoice_title'];
            $data['invoice_title'] = null;
        }

        Car::find($id)->invoices()->create([
            'title' => $data['invoice_title'],
            'invoice_type_id' => $data['invoice_type_id'],
            'price' => $data['invoice_price'],
            'date' => $data['invoice_date'],
            'user_id' => Auth::user()->id,
            'tax' => $data['invoice_tax'],
            'account' => $data['invoice_account']
        ]);

        return redirect('/car/' . $id);
    }

    /**
     * Show car detail page
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail($id) {
        return view('car.detail', ['car' => Car::find($id)]);
    }

    public function getAuctionData($id) {
        $client = new Client();
        $crawler = $client->request('GET', 'http://suchen.mobile.de/fahrzeuge/details.html?id=' . Car::find($id)->mobile_id);

        $data = [];
        $data['title'] =  $crawler->filter('h1#rbt-ad-title')->first()->text();
        $data['brutto_price'] =  $crawler->filter('span.h3.rbt-prime-price')->first()->text();
        $data['netto_price'] =  ($crawler->filter('span.rbt-sec-price')->count()) ? $crawler->filter('span.rbt-sec-price')->first()->text() : null;
        //$data['image'] = 'http://1.1.1.1/bmi/' . substr($crawler->filter('div#rbt-gallery-img-0 > img')->first()->attr('src'), 2);
        $data['image'] = 'http:' . $crawler->filter('div#rbt-gallery-img-0 > img')->first()->attr('src');

        return response()->json($data);
    }

    public function getData(Request $request) {
        $cars = DB::table('cars')->select(['id', 'title', 'chassis_number', 'purchase_date', 'purchase_price', 'sale_date', 'sale_price']);

        return Datatables::of($cars)->setRowClass(function ($car) {
            return ($car->sale_date) ? 'danger' : 'success';
        })->make(true);
    }

    public function getDataStock(Request $request) {
        $cars = DB::table('cars')->select(['id', 'title', 'chassis_number', 'purchase_date', 'purchase_price'])->whereNull('sale_date');

        return Datatables::of($cars)->make(true);
    }

    public function getInvoiceData(Request $request, $id) {
        $cars = DB::table('invoices')->select(['invoices.id as id', 'invoices.title as title', 'invoices.price', 'invoices.date', 'invoice_types.title as ititle'])->where('car_id', '=', $id)
            ->leftJoin('invoice_types', 'invoices.invoice_type_id', '=', 'invoice_types.id');
        return Datatables::of($cars)->make(true);
    }
}
