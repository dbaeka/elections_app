<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidatesController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return JSONAPICollection
     */
    public function index()
    {
        //
        return $this->service->fetchResources(Candidate::class, 'candidates');
    }


    /**
     * Display the specified resource.
     *
     * @return JSONAPIResource
     */
    public function show($candidate)
    {
        //
        return $this->service->fetchResource(Candidate::class, $candidate, 'candidates');
    }
}
