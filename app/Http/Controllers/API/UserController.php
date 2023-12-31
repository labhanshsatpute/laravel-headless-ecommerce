<?php

namespace App\Http\Controllers\API;

use App\Enums\Gender;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

interface UserInterface
{
    public function handleGetUser();
    public function handleUpdateUserDetails(Request $request);
    public function handleUpdateUserPassword(Request $request);
}

class UserController extends Controller implements UserInterface
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('logout');
    }

    /**
     * Get Auth User
     *
     * @return Response
     */
    public function handleGetUser(): Response
    {
        try {

            return $this->sendResponseOk("User successfully fetch", [
                'id' => auth()->user()->id,
                'uuid' => auth()->user()->uuid,
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone,
                'gender' => auth()->user()->gender,
                'date_of_birth' => auth()->user()->date_of_birth,
                'profile_image' => is_null(auth()->user()->profile_image) ? null : asset('storage/'.auth()->user()->profile_image)
            ]);
        } catch (Exception $exception) {
            return $this->sendExceptionError($exception);
        }
    }

    /**
     * Handle Update User Details
     *
     * @return Response
     */
    public function handleUpdateUserDetails(Request $request): Response
    {
        try {

            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:1', 'max:250'],
                'email' => [
                    'required', 'string', 'min:1', 'max:250',
                    Rule::unique('users')->ignore(auth()->user()->id, 'id')
                ],
                'phone' => [
                    'required', 'numeric', 'digits_between:10,20',
                    Rule::unique('users')->ignore(auth()->user()->id, 'id')
                ],
                'profile_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
                'date_of_birth' => ['nullable', 'date_format:Y-m-d'],
                'gender' => ['nullable', 'string', new Enum(Gender::class)],
                'account_password' => ['required', 'string', 'min:1', 'max:100'],
            ]);

            if ($validation->fails()) {
                return $this->sendValidationError($validation->errors()->first(), $validation->errors()->getMessages());
            }

            if (Hash::check($request->input('account_password'), auth()->user()->password)) {

                $user = User::find(auth()->user()->id);
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->phone = $request->input('phone');
                $user->gender = $request->input('gender');
                $user->date_of_birth = $request->input('date_of_birth');
                if ($request->hasFile('profile_image')) {
                    if (!is_null(auth()->user()->profile_image)) Storage::delete(auth()->user()->profile_image);
                    $user->profile_image = $request->file('profile_image')->store('users');
                }
                $user->update();

                return $this->sendResponseOk("Successfully updated", [
                    'id' => auth()->user()->id,
                    'uuid' => auth()->user()->uuid,
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'phone' => auth()->user()->phone,
                    'gender' => auth()->user()->gender,
                    'date_of_birth' => auth()->user()->date_of_birth,
                    'profile_image' => is_null(auth()->user()->profile_image) ? null : asset('storage/'.auth()->user()->profile_image)
                ]);
            }

            return  $this->sendValidationError("Incorrect password", [
                'account_password' => ['Incorrect Password']
            ]);
        } catch (Exception $exception) {
            return $this->sendExceptionError($exception);
        }
    }

    /**
     * Handle Update User Password
     *
     * @return Response
     */
    public function handleUpdateUserPassword(Request $request): Response
    {
        try {

            $validation = Validator::make($request->all(), [
                'current_password' => ['required', 'string', 'min:1', 'max:100'],
                'password' => ['required', 'string', 'min:6', 'max:20', 'confirmed'],
            ]);

            if ($validation->fails()) {
                return $this->sendValidationError($validation->errors()->first(), $validation->errors()->getMessages());
            }

            if (Hash::check($request->input('current_password'), auth()->user()->password)) {

                $user = User::find(auth()->user()->id);
                $user->password = Hash::make($request->input('password'));
                $user->update();

                return $this->sendResponseOk("Password successfully updated", null);
            }

            return  $this->sendValidationError("Incorrect password", [
                'current_password' => ['Incorrect Password']
            ]);
        } catch (Exception $exception) {
            return $this->sendExceptionError($exception);
        }
    }
}
