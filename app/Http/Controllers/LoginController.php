<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetForgotRequest;
use App\Http\Requests\UserPasswordResetRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\services\LoginService;

class LoginController extends BaseController
{
    private $login;
    public function __construct(LoginService $login)
    {
        $this->login = $login;
    }

    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            $accesstoken = $request->user()->createToken($request->user()->email)->accessToken;
            $data = [
                'user' => $request->user(),
                'access token' => $accesstoken
            ];
            return $this->respond('Login successfull', Response::HTTP_OK, $data);
        } else {
            return $this->respond('Login unsuccessfull', Response::HTTP_BAD_REQUEST);
        }
    }


    public function createUser(CreateUserRequest $request)
    {
        //(create method)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        return $this->respond('User Successfully Created', Response::HTTP_CREATED, ['user' => new UserResource($user)]);
    }

    public function userList()
    {
        $users = User::all();
        return response()->json([
            'message' => 'List fetched sucessfully',
            'user' => $users
        ]);
    }


    public function resetPassword(UserPasswordResetRequest $request)
    {
        if (Hash::check($request->input('password'), $request->user()->password)) {
            if (Hash::check($request->input('new_password'), $request->user()->password)) {
                return response([
                    'Message' => 'New Password must be different'
                ]);
            }
            $request->user()->fill([
                'password' => Hash::make($request->password)
            ])->save();
            return $this->respond('Old Password Incorrect', Response::HTTP_OK);
        }
    }



    public function forgot(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));
        if ($status == Password::RESET_LINK_SENT) {
            User::where('email', $request->email)->update([
                'mail_timestamp' => time(),
            ]);
            return [
                "status" => __($status)
            ];
        } else {
            return 'An Error Occured !!!';
        }
    }

    public function resetForgot(ResetForgotRequest $request)
    {
        $openTime = time();
        $dataTime =  User::where('email', $request->email)->value('mail_timestamp');
        if (($openTime - $dataTime) > 900) {
            return [
                'message' => 'Link expired'
            ];
        }
        if ($request->input('email') == $request->user()->email) {
            if (Hash::check($request->input('new_password'), $request->user()->password)) {
                return response([
                    'Message' => 'New Password must be different'
                ]);
            }
            $request->user()->fill([
                'password' => Hash::make($request->new_password),
            ])->save();

            return $this->respond('reset done', Response::HTTP_OK);
        }
        return response()->json([
            'message' => 'Email incorrect'
        ]);
    }


    public function userLogout(Request $request)
    {
        $request->user()->token()->revoke();
        $data = [
            'logout' => 'LOGOUT DONE'
        ];
        return $this->respond('User successfully logged out', Response::HTTP_OK, $data);
    }
}
