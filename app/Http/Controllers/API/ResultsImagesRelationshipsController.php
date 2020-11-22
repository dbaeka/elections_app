<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Models\Candidate;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultsImagesRelationshipsController extends APIController
{
    //
    public function index(Result $result)
    {
        return $this->service->fetchRelationship($result, 'images');
    }
}
