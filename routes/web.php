<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\ShoppingListController;
use App\Models\ShoppingList;
use App\Http\Controllers\ItemController;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::resource('lists',ShoppingListController::class);
    Route::resource('items', ItemController::class);
    Route::get('/lists', function () {
        return Inertia::render('Lists',
            [
                'lists' => ShoppingList
                                ::where('user_id', auth()->user()->id)
                                ->orderBy('updated_at', 'DESC')
                                ->paginate(15)
                                ->through(function ($list) {
                    return [
                        'id' => $list->id,
                        'name' => $list->name
                    ];
                })
            ]
        );
    })->name('lists');
});

Route::get('/auth/redirect/{provider}', [SocialLoginController::class, 'providerRedirect']);
Route::get('/auth/callback/{provider}', [SocialLoginController::class, 'providerCallback']);
