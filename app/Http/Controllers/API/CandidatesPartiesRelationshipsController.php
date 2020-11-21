<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidatesPartiesRelationshipsController extends APIController
{
    //
    public function index(Candidate $candidate)
    {
        return $this->service->fetchRelationship($candidate, 'parties');
    }
}
