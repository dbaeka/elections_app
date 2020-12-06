<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Controllers\Controller;
use App\Http\Requests\JSONAPIRequest;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\Result;
use App\Models\Station;
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

    public function some_index()
    {
        $type = basename(\request()->getPathInfo());
        return $this->service->fetchEngineResources(Result::class, 'results', $type);
    }

    public function display_index()
    {
        //
        return $this->service->fetchDisplayResources(Station::class, 'results');
    }

    /**
     * @param JSONAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(JSONAPIRequest $request)
    {
        //
        $user = $request->user();
        $attributes = $request->input('data.attributes');
        $attributes['is_approved'] = false;
        $attributes['is_latest'] = false;
        $attributes['media_checked'] = false;
        $constituency_id = $user->station()->value('constituency_id');
        $attributes['constituency_id'] = $constituency_id;
        $attributes['user_id'] = $user->id;

        return $this->service->createResource(Result::class, $attributes);
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
        $id = $request->input('data.id');
        $attributes = $request->input('data.attributes');
        return $this->service->updateResource($result, $attributes, $request->input('data.relationships'), $id, "results");
    }
}
