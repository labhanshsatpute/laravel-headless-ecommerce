<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\Admin\SendPasswordResetMail;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

interface AuthInterface
{
    public function viewLogin();
    public function handleLogin(Request $request);
    public function viewForgotPassword();
    public function handleForgotPassword(Request $request);
    public function viewResetPassword($token);
    public function handleResetPassword(Request $request, $token);
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
     * @return RedirectResponse
     */
    public function handleLogin(Request $request): RedirectResponse
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

    /**
     * View Forgot Password
     *
     * @return mixed
     */
    public function viewForgotPassword(): mixed
    {
        try {

            return view('admin.pages.auth.forgot-password');
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Forgot Password
     *
     * @return RedirectResponse
     */
    public function handleForgotPassword(Request $request): RedirectResponse
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'exists:admins', 'min:6', 'max:100'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $token = Str::random(64);

            DB::table('password_reset_tokens')->where('email',  $request->input('email'))->delete();

            DB::table('password_reset_tokens')->insert([
                'email' => $request->input('email'),
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            $url = route('admin.view.reset.password', ['token' => $token]);

            dispatch(new SendPasswordResetMail($request->input('email'), $url));

            return redirect()->back()->with('message', [
                'status' => 'success',
                'title' => 'Link sent',
                'description' => 'The password reset link sent to your email'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View Reset Password
     *
     * @return mixed
     */
    public function viewResetPassword($token): mixed
    {
        try {

            if (DB::table('password_reset_tokens')->where('token', $token)->exists()) {

                $email = DB::table('password_reset_tokens')->where('token', $token)->first()->email;

                return view('admin.pages.auth.reset-password', [
                    'token' => $token,
                    'email' => $email
                ]);
            }

            return abort(404);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Handle Reset Password
     *
     * @return mixed
     */
    public function handleResetPassword(Request $request, $token): mixed
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'exists:admins', 'exists:password_reset_tokens', 'min:6', 'max:100'],
                'password' => ['required', 'string', 'confirmed', 'min:6', 'max:20']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if (DB::table('password_reset_tokens')->where(['email' => $request->email, 'token' => $token])->exists()) {

                $admin = Admin::where('email', $request->input('email'))->first();
                $admin->password = Hash::make($request->input('password'));
                $admin->password_updated_at = Carbon::now();
                $admin->update();

                DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

                return redirect()->route('admin.view.login')->with('message', [
                    'status' => 'success',
                    'title' => 'Password set',
                    'description' => 'Your account password is successfully set.'
                ]);
            }

            return abort(404);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }
}
