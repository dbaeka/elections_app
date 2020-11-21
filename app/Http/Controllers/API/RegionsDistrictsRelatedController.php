<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionsDistrictsRelatedController extends APIController
{
    //
    public function show(Region $region)
    {
        return $this->service->fetchRelated($region, 'districts');
    }
}
