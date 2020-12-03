<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\Candidate;
use App\Models\PMCandidate;
use Illuminate\Http\Request;

class PMCandidatesController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return JSONAPICollection
     */
    public function index()
    {
        //
        return $this->service->fetchUserBasedResources(PMCandidate::class, 'pm_candidates');
    }


    /**
     * Display the specified resource.
     *
     * @return JSONAPIResource
     */
    public function show($candidate)
    {
        //
        return $this->service->fetchResource(Candidate::class, $candidate, 'pm_candidates');
    }
}
