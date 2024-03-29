<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionsDistrictsRelationshipsController extends APIController
{
    //
    public function index(Region $region)
    {
        return $this->service->fetchRelationship($region, 'districts');
    }

}
