<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/features')->name('features.')->middleware(['auth'])->group(function () {
    Route::livewire('/', 'pages::features.index');
    Route::livewire('{feature}/create-comment', 'pages::features.create-comment')->name('create-comment');
});
