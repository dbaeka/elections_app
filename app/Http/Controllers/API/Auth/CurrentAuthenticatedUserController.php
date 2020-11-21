<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\JSONAPIResource;
use Illuminate\Http\Request;

class CurrentAuthenticatedUserController extends Controller
{
    //
    public function show(Request $request)
    {
        return new JSONAPIResource($request->user());
    }
}
