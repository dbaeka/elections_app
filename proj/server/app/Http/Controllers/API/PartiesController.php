<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Controllers\Controller;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\Party;
use Illuminate\Http\Request;

class PartiesController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return JSONAPICollection
     */
    public function index()
    {
        //
        return $this->service->fetchResources(Party::class, 'parties');
    }


    /**
     * Display the specified resource.
     *
     * @return JSONAPIResource
     */
    public function show($party)
    {
        //
        return $this->service->fetchResource(Party::class, $party, 'parties');
    }
}
