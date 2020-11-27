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


// Image get
Route::get('/uploads/{filename}', [\App\Http\Controllers\API\ImageFileController::class, 'download'])
    ->middleware(['auth:sanctum', 'sanctum.abilities:basic']);


Route::prefix('api/v1')->group(function () {
    Route::post('/authenticate', function (Request $request) {
        $request->validate([
            'phone' => 'required|regex:/^[0-9\-\(\)\/\+\s]*$/',
            'password' => 'required',
//            'firebase_token' => 'required',
//            'role' => 'required|in:polling,engine,display,admin'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ["Incorrect password"]
            ]);
        }

        $token = $user->createToken($request->phone, [$user->role, 'basic'])->plainTextToken;
        return response()->json([
            'token' => $token,
            'name' => $user->name,
            'phone' => $user->phone,
            'fcm_token' => $user->fcm_token,
            'role' => $user->role,
            'id' => $user->id,
            'is_active' => $user->is_active,
            'station_id' => $user->station_id,
        ]);
    });

    Route::middleware(['auth:sanctum', 'json.api.headers'])->group(function () {
        // Everyone can
        Route::middleware(['sanctum.abilities:basic'])->group(function () {
            // Users
            Route::get('/users/current', [CurrentAuthenticatedUserController::class, 'show']);
            Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show');
            Route::get('/users', [UsersController::class, 'index'])->name('users.index');
            Route::get('/users/{user}/relationships/stations', [\App\Http\Controllers\API\UsersStationsRelationshipsController::class, 'index'])->name('users.relationships.stations');
            Route::get('/users/{user}/stations', [\App\Http\Controllers\API\UsersStationsRelatedController::class, 'show'])->name('users.stations');

            Route::get('/users/{user}/relationships/results', [\App\Http\Controllers\API\UsersResultsRelationshipsController::class, 'index'])->name('users.relationships.results');
            Route::get('/users/{user}/results', [\App\Http\Controllers\API\UsersResultsRelatedController::class, 'show'])->name('users.results');


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


            //  Results
            Route::get('results', [\App\Http\Controllers\API\ResultsController::class, 'index'])->name('results.index');;
            Route::get('results/{result}', [\App\Http\Controllers\API\ResultsController::class, 'show'])->name('results.show');
            Route::get('/results/{result}/relationships/users', [\App\Http\Controllers\API\ResultsUsersRelationshipsController::class, 'index'])->name('results.relationships.users');
            Route::get('/results/{result}/users', [\App\Http\Controllers\API\ResultsUsersRelatedController::class, 'show'])->name('results.users');

            Route::get('/results/{result}/relationships/images', [\App\Http\Controllers\API\ResultsImagesRelationshipsController::class, 'index'])->name('results.relationships.images');
            Route::get('/results/{result}/images', [\App\Http\Controllers\API\ResultsImagesRelatedController::class, 'show'])->name('results.images');


            //  Images
            Route::get('/images', [\App\Http\Controllers\API\ImageFileController::class, 'index'])->name('images.index');
            Route::get('/images/{image}', [\App\Http\Controllers\API\ImageFileController::class, 'show'])->name('images.show');
            Route::get('/images/{image}/relationships/results', [\App\Http\Controllers\API\ImagesResultsRelationshipsController::class, 'index'])->name('images.relationships.results');
            Route::get('/images/{image}/results', [\App\Http\Controllers\API\ImagesResultsRelatedController::class, 'show'])->name('images.results');


            // Upload History
            Route::get('/upload_history', [\App\Http\Controllers\API\UploadHistoryController::class, 'index']);


        });

        // For polling and admin
        Route::middleware(['sanctum.abilities:polling,admin'])->group(function () {
            //  Results
            Route::patch('results', [\App\Http\Controllers\API\ResultsController::class, 'update'])->name('results.update');
            Route::post('results', [\App\Http\Controllers\API\ResultsController::class, 'store'])->name('results.store');

            //  Images
            Route::post('/images', [\App\Http\Controllers\API\ImageFileController::class, 'fileUpload'])->withoutMiddleware(['json.api.headers']);
            Route::post('/images/base64', [\App\Http\Controllers\API\ImageFileController::class, 'fileBase64Upload']);
        });

        // For admin
        Route::middleware(['sanctum.abilities:admin'])->group(function () {
            //  Users
            Route::patch('/users', [UsersController::class, 'update'])->name('users.update');
            Route::post('/users', [UsersController::class, 'store'])->name('users.store');
            Route::patch('/users/{user}/relationships/results', [\App\Http\Controllers\API\UsersResultsRelationshipsController::class, 'update'])->name('users.relationships.results');
            Route::patch('/users/{user}/relationships/stations', [\App\Http\Controllers\API\UsersStationsRelationshipsController::class, 'update'])->name('users.relationships.stations');

            //  Results
            Route::patch('/results/{result}/relationships/users', [\App\Http\Controllers\API\ResultsUsersRelationshipsController::class, 'update'])->name('results.relationships.users');

        });

        // For engine
        Route::middleware(['sanctum.abilities:polling,admin'])->group(function () {
            //  Results
            Route::patch('results', [\App\Http\Controllers\API\ResultsController::class, 'update'])->name('results.update');
        });
    });

});


Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists.'], 404);
});
