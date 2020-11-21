<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\RegionsController;
use App\Http\Controllers\API\DistrictsController;
use \App\Http\Controllers\API\RegionsDistrictsRelationshipsController;
use \App\Http\Controllers\API\RegionsDistrictsRelatedController;
use \App\Http\Controllers\API\ConstituenciesDistrictsRelationshipsController;
use \App\Http\Controllers\API\CandidatesPartiesRelatedController;
use \App\Http\Controllers\API\Auth\CurrentAuthenticatedUserController;
use \App\Http\Controllers\API\UsersController;

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
//            'firebase_token' => 'required',
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
            'phone' => $user->phone,
            'fcm_token' => $user->fcm_token,
            'role' => $user->role,
            'id' => $user->id,
            'station_id' => $user->station_id,
        ]);
    });


    Route::middleware(['auth:sanctum', 'json.api.headers', 'sanctum.abilities:role:basic'])->group(function () {
        // Users
        Route::get('/users/current', [CurrentAuthenticatedUserController::class, 'show']);
        Route::apiResource('users', UsersController::class);
        Route::get('/users/{user}/relationships/stations', [\App\Http\Controllers\API\UsersStationsRelationshipsController::class, 'index'])->name('users.relationships.stations');
        Route::get('/users/{user}/stations', [\App\Http\Controllers\API\UsersStationsRelatedController::class, 'show'])->name('users.stations');


        // Regions
        Route::apiResource('regions', RegionsController::class);
        Route::get('/regions/{region}/relationships/districts', [RegionsDistrictsRelationshipsController::class, 'index'])->name('regions.relationships.districts');
        Route::get('/regions/{region}/districts', [RegionsDistrictsRelatedController::class, 'show'])->name('regions.districts');


        // Districts
        Route::apiResource('districts', DistrictsController::class);
        Route::get('/districts/{district}/relationships/regions', [\App\Http\Controllers\API\DistrictsRegionsRelationshipsController::class, 'index'])->name('districts.relationships.regions');
        Route::get('/districts/{district}/regions', [\App\Http\Controllers\API\DistrictsRegionsRelatedController::class, 'show'])->name('districts.regions');
        Route::get('/districts/{district}/relationships/constituencies', [\App\Http\Controllers\API\DistrictsConstituenciesRelationshipsController::class, 'index'])->name('districts.relationships.constituencies');
        Route::get('/districts/{district}/constituencies', [\App\Http\Controllers\API\DistrictsConstituenciesRelatedController::class, 'show'])->name('districts.constituencies');


        // Constituencies
        Route::apiResource('constituencies', \App\Http\Controllers\API\ConstituenciesController::class);
        Route::get('/constituencies/{constituency}/relationships/districts', [ConstituenciesDistrictsRelationshipsController::class, 'index'])->name('constituencies.relationships.districts');
        Route::get('/constituencies/{constituency}/districts', [\App\Http\Controllers\API\ConstituenciesDistrictsRelatedController::class, 'show'])->name('constituencies.districts');
        Route::get('/constituencies/{constituency}/relationships/stations', [\App\Http\Controllers\API\ConstituenciesStationsRelationshipsController::class, 'index'])->name('constituencies.relationships.stations');
        Route::get('/constituencies/{constituency}/stations', [\App\Http\Controllers\API\ConstituenciesStationsRelatedController::class, 'show'])->name('constituencies.stations');


        // Candidates
        Route::apiResource('candidates', \App\Http\Controllers\API\CandidatesController::class);
        Route::get('/candidates/{candidate}/relationships/parties', [\App\Http\Controllers\API\CandidatesPartiesRelationshipsController::class, 'index'])->name('candidates.relationships.parties');
        Route::get('/candidates/{candidate}/parties', [\App\Http\Controllers\API\CandidatesPartiesRelatedController::class, 'show'])->name('candidates.parties');


        // Parties
        Route::apiResource('parties', \App\Http\Controllers\API\PartiesController::class);
        Route::get('/parties/{party}/relationships/candidates', [\App\Http\Controllers\API\PartiesCandidatesRelationshipsController::class, 'index'])->name('parties.relationships.candidates');
        Route::get('/parties/{party}/candidates', [\App\Http\Controllers\API\PartiesCandidatesRelatedController::class, 'show'])->name('parties.candidates');

        //  Stations
        Route::apiResource('stations', \App\Http\Controllers\API\StationsController::class);
        Route::get('/stations/{station}/relationships/users', [\App\Http\Controllers\API\StationsUsersRelationshipsController::class, 'index'])->name('stations.relationships.users');
        Route::get('/stations/{station}/users', [\App\Http\Controllers\API\StationsUsersRelatedController::class, 'show'])->name('stations.users');
        Route::get('/stations/{station}/relationships/constituencies', [\App\Http\Controllers\API\StationsConstituenciesRelationshipsController::class, 'index'])->name('stations.relationships.constituencies');
        Route::get('/stations/{station}/constituencies', [\App\Http\Controllers\API\StationsConstituenciesRelatedController::class, 'show'])->name('stations.constituencies');

    });

});


Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists.'], 404);
});
