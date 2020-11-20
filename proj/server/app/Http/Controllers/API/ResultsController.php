<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class ResultsController extends APIController
{
    public function __construct(JSONAPIService $service)
    {
        parent::__construct($service);
        $this->authorizeResource(Result::class, 'result');
    }

    protected function resourceMethodsWithoutModels()
    {
        return ['index', 'store', 'show'];
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Result $results
     * @return \Illuminate\Http\Response
     */
    public function show(Result $results)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Result $results
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Result $results)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Result $results
     * @return \Illuminate\Http\Response
     */
    public function destroy(Result $results)
    {
        //
    }
}
