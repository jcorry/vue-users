<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Models\User;

/**
 * AdminUserController handles requests related to user management
 * @author John Corry <jcorry@gmail.com>
 */
class AdminUserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::with('roles')->paginate();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(\App\Http\Requests\CreateUserRequest $request)
    {
        $user = User::create($request->all());

        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request The HTTP request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id The resource ID
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('roles')->find($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id The resource ID
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     * @param int $id The resource ID
     *
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\UpdateUserRequest $request, $id)
    {
        $user = User::find($id);
        $user->fill($request->only([
            'email',
            'first_name',
            'last_name',
            'phone',
            'dob',
        ]));
        $user->save();

        return $user;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id The resource ID.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(\App\Http\Requests\DeleteUserRequest $request, $id)
    {
        // Delete the User, activations, role_user records
        $user = \Sentinel::findById($id);

        return response()->json($user->delete(), 204);
    }
}
