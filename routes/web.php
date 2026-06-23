<?php

use App\Models\Feature;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/policy', function () {
    dump(auth()->user());
    $feature = Feature::first();
    if (! auth()->user()->can('update', $feature)) {
        dump('You cant update this feature');
    } else {
        dump('You can update this feature');
    }
});
