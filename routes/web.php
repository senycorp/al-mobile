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

use Goutte\Client;

Route::get('/', 'HomeController@index')->name('dashboard');

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/report', 'ReportController@index')->name('report');
Route::post('/report', 'ReportController@create')->name('report_create');
Route::get('/expense/data', 'ExpenseController@getData')->name('expense_data');
Route::get('/expense/{id}/delete', function($id) {
    $invoice = \App\Invoice::findOrFail($id);

    if ($invoice->sale_invoice) {
        $invoice->car->unsell();
    } else if (!$invoice->purchase_invoice)
        \App\Invoice::destroy([$id]);

    return redirect()->route('expense_index');
})->name('expense_delete');
Route::post('/expense/{id}/update', function($id) {
    \App\Invoice::find($id)->fill(request()->toArray())->save();

    return redirect()->back();
})->name('expense_update');
Route::get('/expense/{id}', 'ExpenseController@detail')->name('expense_detail');
Route::get('/expense', 'ExpenseController@index')->name('expense_index');
Route::post('/expense', 'ExpenseController@create')->name('expense_create');
Route::get('/car/data', 'CarController@getData')->name('car_data');
Route::get('/car/datastock', 'CarController@getDataStock')->name('car_data_stock');
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
})->name('mobile_data');
Route::get('/car/{id}/auctionData', 'CarController@getAuctionData')->name('car_auction_data');
Route::get('/car/{id}/invoiceData', 'CarController@getInvoiceData')->name('car_invoice_data');
Route::get('/car/{id}/delete', function($id) {
    \App\Car::destroy([$id]);

    return redirect()->route('car_index');
})->name('car_delete');
Route::get('/carStock', 'CarController@indexStock')->name('car_index_stock');
Route::get('/car/{id}', 'CarController@detail')->name('car_detail');
Route::get('/car', 'CarController@index')->name('car_index');

Route::post('/car/{id}/sell', 'CarController@sellCar')->name('car_sell');
Route::post('/car/{id}/invoice', 'CarController@createInvoice')->name('car_create_invoice');
Route::post('/car', 'CarController@create')->name('car_create');
Route::get('/car/{id}/unsell', function($id) {
    \App\Car::find($id)->unsell();

    return redirect()->back();
})->name('car_unsell');

Route::get('/invoice/{id}/delete', function($id) {
    $invoice = \App\Invoice::findOrFail($id);

    if ($invoice->sale_invoice) {
        $invoice->car->unsell();
    } else if (!$invoice->purchase_invoice)
        \App\Invoice::destroy([$id]);

    return redirect()->back();
})->name('delete_invoice');

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
})->name('car_invoice');

Route::post('/car/{id}/invoicep', function($id) {
    $car = \App\Car::find($id);
    $car->fill([
        'invoice_data' => json_encode(request()->toArray())])->save();

    return response('', 200);
})->name('car_save_invoice');