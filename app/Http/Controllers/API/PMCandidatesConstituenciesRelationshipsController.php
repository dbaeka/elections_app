<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Candidate;
use App\Models\PMCandidate;
use Illuminate\Http\Request;

class PMCandidatesConstituenciesRelationshipsController extends APIController
{
    //
    public function index(PMCandidate $candidate)
    {
        return $this->service->fetchRelationship($candidate, 'constituencies');
    }
}
