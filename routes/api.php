<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiLinkController;
use App\Http\Controllers\Api\ApiAnalyticsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* API shorten endpoints */
Route::get('action/shorten', [ApiLinkController::class, 'shortenLink'])->name('api_shorten_url');
Route::post('action/shorten', [ApiLinkController::class, 'shortenLink'])->name('papi_shorten_url');
Route::post('action/shorten_bulk', [ApiLinkController::class, 'shortenLinksBulk'])->name('api_shorten_url_bulk');

/* API lookup endpoints */
Route::get('action/lookup', [ApiLinkController::class, 'lookupLink'])->name('api_lookup_url');
Route::post('action/lookup', [ApiLinkController::class, 'lookupLink'])->name('papi_lookup_url');

/* API data endpoints */
Route::get('data/link', [ApiAnalyticsController::class, 'lookupLinkStats'])->name('api_link_analytics');
Route::post('data/link', [ApiAnalyticsController::class, 'lookupLinkStats'])->name('papi_link_analytics');
