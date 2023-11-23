<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

interface AccessInterface
{
    public function viewAccessList();
    public function viewAccessCreate();
    public function viewAccessUpdate($id);
    public function handleAccessCreate(Request $request);
    public function handleAccessUpdate(Request $request, $id);
    public function handleToggleAccessStatus(Request $request);
    public function handleAccessDelete($id);
}

class AccessController extends Controller implements AccessInterface
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin')->except('logout');
    }

    /**
     * View Access List
     *
     * @return mixed
     */
    public function viewAccessList(): mixed
    {
        try {

            $admins = Admin::all();

            return view('admin.pages.access.access-list', [
                'admins' => $admins,
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
     * View Access Create
     *
     * @return mixed
     */
    public function viewAccessCreate(): mixed
    {
        try {

            return view('admin.pages.access.access-create');
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }

    /**
     * View Access Update
     *
     * @return mixed
     */
    public function viewAccessUpdate($id): mixed
    {
        try {

            $admin = Admin::find($id);

            if (!$admin) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Access not found',
                    'description' => 'Access not found with specified ID'
                ]);
            }

            return view('admin.pages.access.access-update', [
                'admin' => $admin
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
     * Handle Access Create
     *
     * @return RedirectResponse
     */
    public function handleAccessCreate(Request $request): RedirectResponse
    {
        try {

            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:1', 'max:250'],
                'email' => ['required', 'string', 'email',  'min:1', 'max:250', 'unique:admins'],
                'phone' => ['required', 'numeric', 'digits_between:10,12', 'unique:admins'],
                'password' => ['required', 'string', 'min:6', 'max:20', 'confirmed'],
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $admin = new Admin();
            $admin->name = $request->input('name');
            $admin->email = $request->input('email');
            $admin->phone = $request->input('phone');
            $admin->password = Hash::make($request->input('password'));
            $admin->save();

            return redirect()->route('admin.view.access.list')->with('message', [
                'status' => 'success',
                'title' => 'Access created',
                'description' => 'The access is successfully created.'
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
     * Handle Access Update
     *
     * @return RedirectResponse
     */
    public function handleAccessUpdate(Request $request, $id): RedirectResponse
    {
        try {

            $admin = Admin::find($id);

            if (!$admin) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Admin not found',
                    'description' => 'Admin not found with specified ID'
                ]);
            }

            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:1', 'max:250'],
                'email' => ['required', 'string', 'email',  'min:1', 'max:250', Rule::unique('admins')->ignore($id)],
                'phone' => ['required', 'numeric', 'digits_between:10,12', Rule::unique('admins')->ignore($id)],
                'password' => ['nullable', 'string', 'min:6', 'max:20', 'confirmed'],
            ]);

            if ($validation->fails()) {
                return redirect()->back()->withErrors($validation)->withInput();
            }

            $admin->name = $request->input('name');
            $admin->email = $request->input('email');
            $admin->phone = $request->input('phone');
            if ($request->input('password')) {
                $admin->password = Hash::make($request->input('password'));
            }
            $admin->update();

            return redirect()->route('admin.view.access.list')->with('message', [
                'status' => 'success',
                'title' => 'Changes saved',
                'description' => 'The changes are successfully saved.'
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
     * Handle Toggle Access Status
     *
     * @return Response
     */
    public function handleToggleAccessStatus(Request $request): Response
    {
        try {

            $validation = Validator::make($request->all(), [
                'id' => ['required', 'numeric', 'exists:admins']
            ]);

            if ($validation->fails()) {
                return response([
                    'status' => false,
                    'message' => $validation->errors()->first(),
                    'error' => $validation->errors()->getMessages()
                ], 200);
            }

            $admin = Admin::find($request->input('id'));
            $admin->status = !$admin->status;
            $admin->update();

            return response([
                'status' => true,
                'message' => "Status successfully updated",
                'data' => $admin
            ], 200);
        } catch (Exception $exception) {
            return response([
                'status' => false,
                'message' => "An error occcured",
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Handl Access Delete
     *
     * @return RedirectResponse
     */
    public function handleAccessDelete($id): RedirectResponse
    {
        try {

            $admin = Admin::find($id);

            if (!$admin) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Access not found',
                    'description' => 'Access not found with specified ID'
                ]);
            }

            $admin->delete();

            return redirect()->route('admin.view.access.list')->with('message', [
                'status' => 'success',
                'title' => 'Access deleted',
                'description' => 'The access is successfully deleted.'
            ]);
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }
}
