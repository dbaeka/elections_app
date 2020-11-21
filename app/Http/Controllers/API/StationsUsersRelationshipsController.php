<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Party;
use App\Models\Station;
use Illuminate\Http\Request;

class StationsUsersRelationshipsController extends APIController
{
    //
    public function index(Station $station)
    {
        return $this->service->fetchRelationship($station, 'users');
    }
}
