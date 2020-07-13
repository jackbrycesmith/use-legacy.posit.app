<?php

use App\Actions\Proposal\Pub\GetPubProposalIndex;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

$domain = parse_url(config('app.url'), PHP_URL_HOST);

Route::domain("pub.{$domain}")->group(function () {
    Route::get('/', '\\'.GetPubProposalIndex::class);
});

Route::get('/', function () {
    return Inertia::render('Use/Index');
});

Auth::routes();
