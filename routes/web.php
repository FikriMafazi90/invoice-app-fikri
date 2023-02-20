<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect('/invoice');
});

Route::get('/invoice', [InvoiceController::class, 'index']);
Route::get('/invoice/cari', [InvoiceController::class, 'cari']);
Route::get('/invoice/tambah', [InvoiceController::class, 'tambah']);
Route::post('/invoice/store', [InvoiceController::class, 'store']);
Route::get('/invoice/edit/{id}', [InvoiceController::class, 'edit']);
Route::post('/invoice/update', [InvoiceController::class, 'update']);
Route::get('/invoice/hapus/{id}', [InvoiceController::class, 'hapus']);

Route::get('/invoice_api', [InvoiceController::class, 'invoice_api']);
Route::get('/invoice_detail/{id}', [InvoiceController::class, 'invoice_detail']);