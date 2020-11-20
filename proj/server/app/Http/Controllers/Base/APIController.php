<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Services\JSONAPIService;

class APIController extends Controller
{
    protected $service;

    public function __construct(JSONAPIService $service)
    {
        $this->service = $service;
    }
}
