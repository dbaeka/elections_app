<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidatesPartiesRelatedController extends APIController
{
    //
    public function show(Candidate $candidate)
    {
        return $this->service->fetchRelated($candidate, 'parties');
    }
}
