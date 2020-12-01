<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\Station;
use Illuminate\Http\Request;

class StationsController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return JSONAPICollection
     */
    public function index()
    {
        return $this->service->fetchResources(Station::class, 'stations');
    }

    public function some_index()
    {
        $type = basename(\request()->getPathInfo());
        return $this->service->specialMultipleResources(Station::class, 'stations', $type, 'results');
    }

    /**
     * Display the specified resource.
     *
     * @return JSONAPIResource
     */
    public function show($station)
    {
        //
        return $this->service->fetchResource(Station::class, $station, 'stations');
    }
}
