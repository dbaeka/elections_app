<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Base\APIController;
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

}
