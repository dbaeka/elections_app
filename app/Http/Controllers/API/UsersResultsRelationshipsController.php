<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Http\Request;

class UsersResultsRelationshipsController extends APIController
{
    //
    public function index(User $user)
    {
        return $this->service->fetchRelationship($user, 'results');
    }

    public function update(JSONAPIRelationshipRequest $request, User $user)
    {
        return $this->service->updateToManyRelationships($user, 'results', $request->input('data.*.id'));
    }
}
