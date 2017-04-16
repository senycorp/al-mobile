<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Car;
use App\Invoice;
use Goutte\Client;
use Illuminate\Support\Facades\Auth;

Route::get('/', 'HomeController@index')->name('dashboard')->middleware('auth');;

Auth::routes();

Route::get('/mass', function () {
    return view('mass.index');
})->name('mass')->middleware('auth');

Route::post('/mass/invoice', function () {
    $data = request()->toArray();

    foreach ($data as $key => $value) {
        $data[str_replace('expense_', '', $key)] = $value;

        unset($data[$key]);
    }

    if ($data['car']) $data['car_id'] = $data['car'];
    if ($data['in_out'] == 'out') $data['price'] = -$data['price'];


    $data['user_id'] = Auth::user()->id;

    if (is_numeric($data['title'])) {
        $data['invoice_type_id'] = $data['title'];
        $data['title'] = null;
    }

    $invoice = Invoice::create($data);

    return redirect()->back();
})->name('mass_invoice')->middleware('auth');

Route::post('/mass/car', function () {
    $data = request()->toArray();
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

    return redirect()->back();
})->name('mass_car')->middleware('auth');

Route::get('/expense/mass', function() {
    return view('expense.mass');
})->name('expense_mass')->middleware('auth');;
Route::post('/expense/mass', function() {
    $data = json_decode(request()->get('data'),true);
    $invoices = [];
    if (count($data)) {
        foreach ($data as $eData) {
            if ($eData['car']) $eData['car_id'] = $eData['car'];
            $eData['user_id'] = Auth::user()->id;

            if (is_numeric($eData['title'])) {
                $eData['invoice_type_id'] = $eData['title'];
                $eData['title'] = null;
            }

            if ($eData['in_out'] == 'out')
                $eData['price'] = -$eData['price'];

            $invoices[] = Invoice::create($eData);
        }
    }

    return view('expense.mass', ['data' => $invoices]);
})->name('expense_mass_create')->middleware('auth');;

Route::get('/home', 'HomeController@index');
Route::get('/report', 'ReportController@index')->name('report')->middleware('auth');;
Route::post('/report', 'ReportController@create')->name('report_create')->middleware('auth');;
Route::get('/expense/data', 'ExpenseController@getData')->name('expense_data')->middleware('auth');;
Route::get('/expense/{id}/delete', function($id) {
    $invoice = \App\Invoice::findOrFail($id);

    if ($invoice->sale_invoice) {
        $invoice->car->unsell();
    } else if (!$invoice->purchase_invoice)
        \App\Invoice::destroy([$id]);

    return redirect()->route('expense_index');
})->name('expense_delete')->middleware('auth');;
Route::post('/expense/{id}/update', function($id) {
    $data = request()->toArray();

    if ($data['in_out'] == 'out')
        $data['price'] = -$data['price'];

    if (is_numeric($data['title'])) {
        $data['invoice_type_id'] = $data['title'];
        $data['title'] = null;
    } else {
        $data['invoice_type_id'] = null;
    }

    \App\Invoice::find($id)->fill($data)->save();

    return redirect()->back();
})->name('expense_update')->middleware('auth');;
Route::get('/expense/{id}', 'ExpenseController@detail')->name('expense_detail')->middleware('auth');;
Route::get('/expense', 'ExpenseController@index')->name('expense_index')->middleware('auth');;
Route::post('/expense', 'ExpenseController@create')->name('expense_create')->middleware('auth');;
Route::get('/car/data', 'CarController@getData')->name('car_data')->middleware('auth');;
Route::get('/car/datastock', 'CarController@getDataStock')->name('car_data_stock')->middleware('auth');;
Route::get('/mobile/{id}', function($id) {
    try {
        $client = new Client();
        $crawler = $client->request('GET', 'http://suchen.mobile.de/fahrzeuge/details.html?id=' . $id);

        $data = [];
        $data['title'] =  $crawler->filter('h1#rbt-ad-title')->first()->text();
        $data['brutto_price'] =  $crawler->filter('span.h3.rbt-prime-price')->first()->text();
        $data['netto_price'] =  ($crawler->filter('span.rbt-sec-price')->count()) ? $crawler->filter('span.rbt-sec-price')->first()->text() : null;
        $data['image'] = 'http://1.1.1.1/bmi/' . substr($crawler->filter('div#rbt-gallery-img-0 > img')->first()->attr('src'), 2);

        return response()->json($data);
    } catch (Exception $e) {
        return response()->setStatusCode(500)->json(['error' => 'No Data available']);
    }
})->name('mobile_data')->middleware('auth');;
Route::get('/car/{id}/auctionData', 'CarController@getAuctionData')->name('car_auction_data')->middleware('auth');;
Route::get('/car/{id}/invoiceData', 'CarController@getInvoiceData')->name('car_invoice_data')->middleware('auth');;
Route::get('/car/{id}/delete', function($id) {
    \App\Car::destroy([$id]);

    return redirect()->route('car_index');
})->name('car_delete')->middleware('auth');;
Route::get('/carStock', 'CarController@indexStock')->name('car_index_stock')->middleware('auth');;
Route::get('/car/{id}', 'CarController@detail')->name('car_detail')->middleware('auth');;
Route::get('/car', 'CarController@index')->name('car_index')->middleware('auth');;

Route::post('/car/{id}/sell', 'CarController@sellCar')->name('car_sell')->middleware('auth');;
Route::post('/car/{id}/invoice', 'CarController@createInvoice')->name('car_create_invoice')->middleware('auth');;
Route::post('/car', 'CarController@create')->name('car_create')->middleware('auth');;
Route::get('/car/{id}/unsell', function($id) {
    \App\Car::find($id)->unsell();

    return redirect()->back();
})->name('car_unsell')->middleware('auth');;

Route::get('/invoice/{id}/delete', function($id) {
    $invoice = \App\Invoice::findOrFail($id);

    if ($invoice->sale_invoice) {
        $invoice->car->unsell();
    } else if (!$invoice->purchase_invoice)
        \App\Invoice::destroy([$id]);

    return redirect()->back();
})->name('delete_invoice')->middleware('auth');;

Route::get('/car/{id}/invoicep', function($id) {
    $car = \App\Car::find($id);

    $invoiceData = [];
    if ($car->hasInvoiceData()) {
        $invoiceData = json_decode($car->invoice_data, true);
        $invoiceData['car_id'] = $car->id;
        $invoiceData['tax'] = $car->tax;
        $invoiceData['date'] = \App\Formatter::date($car->sale_date);
        $invoiceData['price'] = $car->sale_price;
        $invoiceData['invoice_no'] = $car->getSaleInvoice()->id;
    } else {
        $invoiceData = [
            'car_id' => $car->id,
            'title' =>   $car->title,
            'pos_title' =>   $car->title,
            'description' => '',
            'date' => $car->getSaleDate(),
            'invoice_no' => $car->getSaleInvoice()->id,
            'chassis_number' => $car->chassis_number,
            'km' => null,
            'first_registration' => null,
            'color' => null,
            'price' => $car->sale_price,
            'tax' => $car->tax,
            'service_provision_date' => null,
            'buyer' => [
                'name' => null,
                'street' => null,
                'location' => null,
                'country' => 'Deutschland'
            ],
        ];

    }

    return view('car.invoice', ['data' => $invoiceData]);
})->name('car_invoice')->middleware('auth');;

Route::post('/car/{id}/invoicep', function($id) {
    $car = \App\Car::find($id);
    $car->fill([
        'invoice_data' => json_encode(request()->toArray())])->save();

    return response('', 200);
})->name('car_save_invoice')->middleware('auth');;

Route::get('/expense/{id}/invoicep', function($id) {
    $invoice = \App\Invoice::find($id);

    $data = [
        'title' => $invoice->getTitle(),
        'invoice_no' => $invoice->id,
        'id' => $invoice->id,
        'price' => $invoice->price,
        'date' => $invoice->date,
        'tax' => $invoice->tax,
        'description' => $invoice->description,
        'buyer' => [
            'name' => null,
            'street' => null,
            'location' => null,
            'country' => 'Deutschland'
        ],
    ];

    if ($invoice->invoice_data) {
        $data2 = json_decode($invoice->invoice_data, true);
        $data['buyer'] = $data2['buyer'];
        $data['title'] = $data2['title'];
        $data['description'] = $data2['description'];
    }

    return view('expense.invoice', [
        'data' => $data
    ]);
})->name('expense_invoice')->middleware('auth');;

Route::post('/expense/{id}/invoicep', function($id) {
    $invoice = \App\Invoice::find($id);

    $invoice->fill([
        'invoice_data' => json_encode(request()->toArray())
    ])->save();

    return response('', 200);
})->name('expense_save_invoice')->middleware('auth');;