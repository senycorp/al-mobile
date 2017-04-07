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
        $data = $request->toArray();
        $data['user_id'] = Auth::user()->id;

        $car = Car::create($data);

        return redirect('/car/' . $car->id);
    }

    /**
     * Sell Car
     *
     * @param $id
     */
    public function sellCar(Request $request, $id) {
        Car::findOrFail($id)->fill($request->toArray())->save();

        return redirect()->back();
    }

    public function createInvoice(Request $request, $id) {
        Car::find($id)->invoices()->create([
            'title' => $request->get('invoice_title'),
            'price' => $request->get('invoice_price'),
            'date' => $request->get('invoice_date'),
            'user_id' => Auth::user()->id
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
        $data['image'] = 'http://1.1.1.1/bmi/' . substr($crawler->filter('div#rbt-gallery-img-0 > img')->first()->attr('src'), 2);

        return response()->json($data);
    }

    public function getData(Request $request) {
        $cars = DB::table('cars')->select(['id', 'title', 'chassis_number', 'purchase_date', 'purchase_price', 'sale_date', 'sale_price']);

        return Datatables::of($cars)->make(true);
    }

    public function getDataStock(Request $request) {
        $cars = DB::table('cars')->select(['id', 'title', 'chassis_number', 'purchase_date', 'purchase_price'])->whereNull('sale_date');

        return Datatables::of($cars)->make(true);
    }

    public function getInvoiceData(Request $request, $id) {
        $cars = DB::table('invoices')->select(['id', 'title', 'price', 'date'])->where('car_id', '=', $id);

        return Datatables::of($cars)->make(true);
    }
}
