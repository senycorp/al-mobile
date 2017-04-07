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

Route::get('/', 'HomeController@index')->name('dashboard');

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/report', 'ReportController@index')->name('report');
Route::post('/report', 'ReportController@create')->name('report_create');
Route::get('/expense/data', 'ExpenseController@getData')->name('expense_data');
Route::get('/expense/{id}/delete', function($id) {
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
Route::get('/car/{id}/invoiceData', 'CarController@getInvoiceData')->name('car_invoice_data');
Route::get('/car/{id}/delete', function($id) {
    \App\Car::destroy([$id]);

    return redirect()->route('car_index');
})->name('car_delete');
Route::get('/car/stock', 'CarController@indexStock')->name('car_index_stock');
Route::get('/car/{id}', 'CarController@detail')->name('car_detail');
Route::get('/car', 'CarController@index')->name('car_index');

Route::post('/car/{id}/sell', 'CarController@sellCar')->name('car_sell');
Route::post('/car/{id}/invoice', 'CarController@createInvoice')->name('car_create_invoice');
Route::post('/car', 'CarController@create')->name('car_create');
Route::get('/car/{id}/unsell', function($id) {
    \App\Car::find($id)->fill([
        'sale_date' => null,
        'sale_price' => null
    ])->save();

    return redirect()->back();
})->name('car_unsell');

Route::get('/invoice/{id}/delete', function($id) {
    \App\Invoice::destroy([$id]);

    return redirect()->back();
})->name('delete_invoice');