<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictsRegionsRelationshipsController extends APIController
{
    //
    public function index(District $district)
    {
        return $this->service->fetchRelationship($district, 'regions');
    }
}
