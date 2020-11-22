<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
use App\Http\Requests\JSONAPIRequest;
use App\Http\Resources\JSONAPICollection;
use App\Http\Resources\JSONAPIResource;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends APIController
{
    /**
     * Display a listing of the resource.
     *
     * @return JSONAPICollection
     */
    public function index()
    {
        //
        return $this->service->fetchResources(User::class, 'users');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JSONAPIResource
     */
    public function show($user)
    {
        //
        return $this->service->fetchResource(User::class, $user, 'users');
    }

    /**
     * @param JSONAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(JSONAPIRequest $request)
    {
        //
        $attributes =  $request->input('data.attributes');
        if ($attributes['role'] === 'polling')
            $attributes['is_active'] = false;
        return $this->service->createResource(User::class, $attributes, $request->input('data.relationships'));
    }


    /**
     * Update resource
     * @param JSONAPIRequest $request
     * @param User $user
     * @return JSONAPIResource
     */
    public function update(JSONAPIRequest $request, User $user)
    {
        //
        return $this->service->updateResource($user, $request->input('data.attributes'), $request->input('data.relationships'), $request->input('data.id'));
    }
}
