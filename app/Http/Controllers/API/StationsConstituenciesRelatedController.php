<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Candidate;
use App\Models\Station;
use Illuminate\Http\Request;

class StationsConstituenciesRelatedController extends APIController
{
    //
    public function show(Station $station)
    {
        return $this->service->fetchRelated($station, 'constituencies');
    }
}
