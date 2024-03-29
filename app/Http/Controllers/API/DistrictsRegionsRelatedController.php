<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictsRegionsRelatedController extends APIController
{
    //
    public function show(District $district)
    {
        return $this->service->fetchRelated($district, 'regions');
    }
}
