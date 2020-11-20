<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictsConstituenciesRelationshipsController extends APIController
{
    //
    public function index(District $district)
    {
        return $this->service->fetchRelationship($district, 'constituencies');
    }

}
