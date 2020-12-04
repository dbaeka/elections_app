<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/download', function () {
    return view('download');
});

Route::post('/con_download', function (Request $request) {
    $request->validate([
        'phone' => 'required|regex:/^[0-9\-\(\)\/\+\s]*$/|exists:users,phone',
        'password' => 'required',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'password' => ["Incorrect password"]
        ]);
    }

    if (!($user->role === "polling" || $user->role === "admin")) {
        throw ValidationException::withMessages([
            'user' => ["User not available to download"]
        ]);
    }
    $user->increment('has_downloaded', 1);
    $name = 'ghdecides.apk';
    $headers = ['Content-Type' => 'application/vnd.android.package-archive'];
    return Storage::download('/app_bundle/apk_build_12_2020.apk', $name, $headers);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists.'], 404);
});
