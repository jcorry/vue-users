<?php
/**
 * 5TG
 */
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use JWTAuth;
use Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Carbon\Carbon;

/**
 * Responsible for handling requests related to registering new users
 *
 * @author John Corry <jcorry@gmail.com>
 */
class LoginController extends BaseController
{
    /**
     * Function to log user in.
     *
     * @param Request $request The HTTP request
     *
     * @return void
     */
    public function loginUser(\App\Http\Requests\AuthLoginRequest $request)
    {
        try {
            $token = JWTAuth::attempt($request->only('email', 'password'), [
                'exp' => Carbon::now()->addWeek()->timestamp,
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

        if (!$token) {
            return response()->json([
                'error' => 'Could not authenticate.'
            ], 401);
        } else {
            $data = [];
            $meta = [];
            
            $data['user'] = Auth::user();
            $meta['token'] = $token;

            return response()->json([
                'data' => $data,
                'meta' => $meta,
            ]);
        }
    }

    public function verifyToken(\Illuminate\Http\Request $request)
    {
    }
}
