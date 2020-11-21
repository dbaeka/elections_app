<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\Request;

class UsersStationsRelatedController extends APIController
{
    //
    public function show(User $user)
    {
        return $this->service->fetchRelated($user, 'stations');
    }
}
