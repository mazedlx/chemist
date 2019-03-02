<?php
Auth::routes();

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/invoices');
    Route::get('/invoices', 'InvoicesController@index');
    Route::get('/invoices/create', 'InvoicesController@create');
    Route::get('/invoices/{invoice}', 'InvoicesController@show');
    Route::post('/invoices', 'InvoicesController@store');
    Route::get('/invoices/{invoice}/edit', 'InvoicesController@edit');
    Route::patch('/invoices/{invoice}', 'InvoicesController@update');
});
