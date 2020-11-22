<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\Request;

class UsersStationsRelationshipsController extends APIController
{
    //
    public function index(User $user)
    {
        return $this->service->fetchRelationship($user, 'stations');
    }

    public function update(JSONAPIRelationshipRequest $request, User $user)
    {
        return $this->service->updateToOneRelationship($user, 'stations', $request->input('data.id'));
    }
}
