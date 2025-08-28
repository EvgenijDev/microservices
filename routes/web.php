<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// SPA fallback: отдаём одно и то же представление для всех не-API путей
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
