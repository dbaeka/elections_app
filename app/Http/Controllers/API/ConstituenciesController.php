<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\Constituency;
use Illuminate\Http\Request;

class ConstituenciesController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return JSONAPICollection
     */
    public function index()
    {
        //
        return $this->service->fetchResources(Constituency::class, 'constituencies');
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Constituency $constituency
     * @return JSONAPIResource
     */
    public function show($constituency)
    {
        //
        return $this->service->fetchResource(Constituency::class, $constituency, 'constituencies');
    }
}
