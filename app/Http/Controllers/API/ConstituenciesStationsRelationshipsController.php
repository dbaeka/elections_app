<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Candidate;
use App\Models\Constituency;
use Illuminate\Http\Request;

class ConstituenciesStationsRelationshipsController extends APIController
{
    //
    public function index(Constituency $constituency)
    {
        return $this->service->fetchRelationship($constituency, 'stations');
    }
}
