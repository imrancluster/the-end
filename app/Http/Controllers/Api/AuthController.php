<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthTokenResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return AuthTokenResource|\Illuminate\Http\JsonResponse
     */
    public function getAccessToken(Request $request)
    {

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return new AuthTokenResource(Auth::user());
        }

        return response()->json([
            'data' => [
                'message' => 'Wrong Credential',
            ],
        ], 401);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordResetRequest(Request $request)
    {

        $request->validate(['email' => 'required|email|exists:users,email',]);

        $user = User::where('email', $request->email)->first();
        $user->reset_key = rand(10000, 99999);
        $user->api_token = str_random(50);
        $user->save();

        Mail::raw("Your Password Reset Key is: {$user->reset_key} \n\n--\nThe End Team", function ($message) use ($user) {
            $message->from(config('mail.from.address'), config('mail.from.name'));
            $message->subject('Password Reset Key of The End');
            $message->to($user->email);
        });

        return response()->json([
            'data' => [
                'message' => 'Password reset key sent to your email',
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'email'     => 'required|email|exists:users',
            'reset_key' => 'required',
            'password'  => 'required',
        ]);

        $user = User::where([
            ['reset_key', $request->reset_key],
            ['email', $request->email],
        ])->first();

        if (!$user) {
            return response()->json([
                'data' => [
                    'message' => 'Email and Reset Key does not match.'
                ]
            ], 422);
        }

        $user->reset_key = null;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'data' => [
                'message' => 'Password changed successfully.'
            ]
        ]);
    }
}
