<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Controllers\Controller;
use App\Models\PMResult;
use App\Models\Result;
use Illuminate\Http\Request;

class UploadHistoryController extends APIController
{
    //
    public function index(Request $request)
    {
        $user = $request->user();
        return $this->service->fetchMultipleResources(Result::class, $user->id, 'results');
    }

    public function pm_index(Request $request)
    {
        $user = $request->user();
        return $this->service->fetchMultipleResources(PMResult::class, $user->id, 'pm_results');
    }
}
