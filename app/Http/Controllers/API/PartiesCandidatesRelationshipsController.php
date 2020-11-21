<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Party;
use Illuminate\Http\Request;

class PartiesCandidatesRelationshipsController extends APIController
{
    //
    public function index(Party $party)
    {
        return $this->service->fetchRelationship($party, 'candidates');
    }
}
