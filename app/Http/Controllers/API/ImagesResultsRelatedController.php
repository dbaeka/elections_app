<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Candidate;
use App\Models\ImageFile;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;

class ImagesResultsRelatedController extends APIController
{
    //
    public function show(ImageFile $image)
    {
        return $this->service->fetchRelated($image, 'results');
    }
}
