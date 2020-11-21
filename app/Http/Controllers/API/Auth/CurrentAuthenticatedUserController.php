<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Base\APIController;
use App\Models\User;
use Illuminate\Http\Request;

class CurrentAuthenticatedUserController extends APIController
{
    //
    public function show(Request $request)
    {
        return $this->service->fetchResource(User::class, $request->user()->id, 'users');
    }
}
