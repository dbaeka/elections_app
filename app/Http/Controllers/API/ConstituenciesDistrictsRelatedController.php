<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Constituency;
use Illuminate\Http\Request;

class ConstituenciesDistrictsRelatedController extends APIController
{
    //
    public function show(Constituency $constituency)
    {
        return $this->service->fetchRelated($constituency, 'districts');
    }
}
