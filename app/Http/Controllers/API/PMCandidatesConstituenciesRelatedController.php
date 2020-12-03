<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Candidate;
use App\Models\PMCandidate;
use Illuminate\Http\Request;

class PMCandidatesConstituenciesRelatedController extends APIController
{
    //
    public function show(PMCandidate $candidate)
    {
        return $this->service->fetchRelated($candidate, 'constituencies');
    }
}
