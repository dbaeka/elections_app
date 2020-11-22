<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Models\Candidate;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultsUsersRelatedController extends APIController
{
    //
    public function show(Result $result)
    {
        return $this->service->fetchRelated($result, 'users');
    }
}
