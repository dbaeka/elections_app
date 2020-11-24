<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Controllers\Controller;
use App\Http\Requests\JSONAPIRequest;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\Result;
use App\Services\JSONAPIService;
use Illuminate\Http\Request;

class ResultsController extends APIController
{

    protected function resourceMethodsWithoutModels()
    {
        return ['index', 'store', 'show'];
    }


    /**
     * Display a listing of the resource.
     *
     * @return JSONAPICollection
     */
    public function index()
    {
        //
        return $this->service->fetchResources(Result::class, 'results');
    }

    /**
     * @param JSONAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(JSONAPIRequest $request)
    {
        //
        $user = $request->user();
        $relationship = [
            $user->type() => [
                "data" => [
                    "type" => $user->type(),
                    "id" => $user->id,
                ]
            ]
        ];
        $attributes =  $request->input('data.attributes');

        $attributes['is_approved'] = false;
        return $this->service->createResource(Result::class, $attributes, $relationship);
    }

    /**
     * Display the specified resource.
     * @param $result
     * @return JSONAPIResource
     */
    public function show($result)
    {
        //
        return $this->service->fetchResource(Result::class, $result, 'results');
    }


    /**
     * Update resource
     * @param JSONAPIRequest $request
     * @param Result $result
     * @return JSONAPIResource
     */
    public function update(JSONAPIRequest $request, Result $result)
    {
        //
        return $this->service->updateResource($result, $request->input('data.attributes'), $request->input('data.relationships'), $request->input('data.id'));
    }
}
