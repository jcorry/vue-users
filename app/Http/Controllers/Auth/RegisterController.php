<?php
/**
 * 5TG
 */
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Controllers\Controller as BaseController;

/**
 * Responsible for handling requests related to registering new users
 *
 * @author John Corry <jcorry@gmail.com>
 */
class RegisterController extends BaseController
{
    /**
     * Function to create a new registered user
     * POST /auth/register
     *
     * @param Request $request The HTTP request
     *
     * @return void
     */
    public function createUser(\App\Http\Requests\AuthRegisterRequest $request)
    {
        // Add user to User table
        $user = User::create($request->all());
        // Add user to the users role
        // Notify user of registration, send verification code
        return response()->json($user);
    }

    /**
     * Function to verify a new registered user
     * POST /auth/verify
     *
     * @param Request $request The HTTP request
     *
     * @return void
     */
    public function verifyUser(Request $request)
    {
    }
}
