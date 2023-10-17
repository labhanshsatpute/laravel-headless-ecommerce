<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

interface AuthInterface 
{
    public function viewLogin();
    public function handleLogin(Request $request);
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
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * View Login
     *
     * @return mixed
     */
    public function viewLogin(): mixed
    {
        try {

            return view('admin.pages.auth.login');

        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Login
     *
     * @return mixed
     */
    public function handleLogin(Request $request): mixed
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'min:10', 'max:100', 'exists:admins'],
                'password' => ['required', 'string', 'min:1', 'max:20']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (Auth::guard('admin')->attempt([
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ], $request->get('remember'))) {
                return redirect(RouteServiceProvider::ADMIN_DASHBOARD);
            }

            return redirect()->back()->withErrors([
                'password' => [
                    'Wrong password'
                ]
            ])->withInput($request->only('email', 'remember'));

        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }
}
