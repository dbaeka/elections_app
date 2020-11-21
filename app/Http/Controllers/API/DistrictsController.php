<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\District;


class DistrictsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return JSONAPICollection
     */
    public function index()
    {
        //
        return $this->service->fetchResources(District::class, 'districts');
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\District $district
     * @return JSONAPIResource
     */
    public function show($district)
    {
        //
        return $this->service->fetchResource(District::class, $district, 'districts');
    }
}
