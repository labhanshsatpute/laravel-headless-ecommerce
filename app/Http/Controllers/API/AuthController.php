<?php

namespace App\Http\Controllers\API;

use App\Events\User\Registred;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| API Auth Controller
|--------------------------------------------------------------------------
|
| Authentication operations for user are handled from this controller.
|
*/

interface AuthInterface
{
    public function handleLogin(Request $request);
    public function handleRegister(Request $request);
    // public function handleForgotPassword(Request $request);
    // public function handleResetPassword(Request $request, $token);
}

class AuthController extends Controller implements AuthInterface
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /*
    |--------------------------------------------------------------------------
    | Handle Login
    |--------------------------------------------------------------------------
    */
    public function handleLogin(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'exists:users', 'min:7', 'max:100'],
                'password' => ['required', 'string', 'min:6', 'max:20'],
            ]);

            if ($validation->fails()) {
                return $this->sendValidationError($validation->errors()->first(), $validation->errors()->getMessages());
            }

            if (Auth::attempt([
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ])) {
                
                $user = User::where('email', $request->input('email'))->first();

                if (!$user->status) {
                    return $this->sendResponse(false, "Access is blocked", null, 200);
                }

                Auth::login($user);

                $token = $user->createToken(Str::random(30))->plainTextToken;

                return $this->sendResponseOk("Successfully logged in", [
                    'token' => $token,
                    'user' => Auth::user()
                ]);
            }

            return  $this->sendValidationError("Invalid credentials", [
                'password' => ['Incorrect Password']
            ]);

        } catch (Exception $e) {
            $this->sendExceptionError($e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Handle Register
    |--------------------------------------------------------------------------
    */
    public function handleRegister(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:5', 'max:100'],
                'phone' => ['nullable', 'numeric', 'digits_between:10,12', 'unique:users'],
                'email' => ['required', 'string', 'email', 'unique:users', 'min:7', 'max:100'],
                'password' => ['required', 'string', 'min:6', 'max:20', 'confirmed'],
            ]);

            if ($validation->fails()) {
                return $this->sendValidationError($validation->errors()->first(), $validation->errors()->getMessages());
            }

            $user = new User();
            $user->name = $request->input('name');
            $user->phone = $request->input('phone');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            Auth::login($user);

            $token = $user->createToken($request->input('password'))->plainTextToken;

            Event::dispatch(new Registred($user));

            return $this->sendResponseCreated("Successfully registred", [
                'token' => $token,
                'user' => Auth::user()
            ]);

        } catch (Exception $e) {
            $this->sendExceptionError($e);
        }
    }
}
