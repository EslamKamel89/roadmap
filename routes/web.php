<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/features')->middleware(['auth'])->group(function () {
    Route::livewire('{feature}/create-comment', 'pages::features.create-comment');
});
