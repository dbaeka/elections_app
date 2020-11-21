<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return JSONAPICollection
     */
    public function index()
    {
        //
        return $this->service->fetchResources(Region::class, 'regions');
    }

    /**
     * Display the specified resource.
     *
     * @return JSONAPIResource
     */
    public function show($region)
    {
        //
        return $this->service->fetchResource(Region::class, $region, 'regions');
    }
}
