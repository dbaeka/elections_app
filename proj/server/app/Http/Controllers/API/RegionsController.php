<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegionsCollection;
use App\Http\Resources\RegionsResource;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\RegionsCollection
     */
    public function index()
    {
        //
        $regions = Region::all();
        return new RegionsCollection($regions);
    }

    /**
     * Display the specified resource.
     *
     * @param Region $region
     * @return RegionsResource
     */
    public function show(Region $region)
    {
        //
        return new RegionsResource($region);
    }
}
