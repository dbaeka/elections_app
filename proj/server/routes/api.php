<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\RegionsController;

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
Route::prefix('v1')->group(function () {
    Route::post('/authenticate', function (Request $request) {
        $request->validate([
            'phone' => 'required|regex:/^[0-9\-\(\)\/\+\s]*$/',
            'password' => 'required',
            'firebase_token' => 'required',
            'role' => 'required|in:polling,engine,display'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ["The provided number is incorrect"]
            ]);
        }
        $role = ($request->role == "polling" ? 'role:polling' : $request->type == "engine") ? 'role:engine' : 'role:display';
        $token = $user->createToken($request->phone, [$role, 'role:basic'])->plainTextToken;
        return response()->json([
            'token' => $token,
            'name' => $user->name,
        ]);
    });

    Route::middleware(['auth:sanctum', 'json.api.headers', 'sanctum.abilities:role:basic'])->group(function () {
        // Regions
        Route::apiResource('regions', RegionsController::class);
//        Route::get('/regions', [RegionsController::class, 'index']);
//        Route::get('/regions/{region}', [RegionsController::class, 'show']);
    });

});


Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists.'], 404);
});
