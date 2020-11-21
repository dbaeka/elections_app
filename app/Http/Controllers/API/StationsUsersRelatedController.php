<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Party;
use App\Models\Station;
use Illuminate\Http\Request;

class StationsUsersRelatedController extends APIController
{

    //
    public function show(Station $station)
    {
        return $this->service->fetchRelated($station, 'users');
    }
}
