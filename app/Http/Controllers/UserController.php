<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Returns the user object for the user identified by the JWT in the request
     *
     * @return response()
     */
    public function readResource(Request $request)
    {
        return response()->json([
            'data' => \Sentinel::getUser()
        ]);
    }
}
