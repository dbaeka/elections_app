<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Candidate;
use App\Models\Station;
use Illuminate\Http\Request;

class StationsConstituenciesRelationshipsController extends APIController
{
    //
    public function index(Station $station)
    {
        return $this->service->fetchRelationship($station, 'constituencies');
    }
}
