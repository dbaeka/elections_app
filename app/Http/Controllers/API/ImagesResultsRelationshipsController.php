<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Requests\JSONAPIRelationshipRequest;
use App\Models\Candidate;
use App\Models\ImageFile;
use App\Models\User;
use Illuminate\Http\Request;

class ImagesResultsRelationshipsController extends APIController
{
    //
    public function index(ImageFile $image)
    {
        return $this->service->fetchRelationship($image, 'results');
    }

}
