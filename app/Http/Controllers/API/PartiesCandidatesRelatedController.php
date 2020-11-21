<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Party;
use Illuminate\Http\Request;

class PartiesCandidatesRelatedController extends APIController
{
    //
    public function show(Party $party)
    {
        return $this->service->fetchRelated($party, 'candidates');
    }
}
