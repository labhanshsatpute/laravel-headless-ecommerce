<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

interface DashboardInterface
{
    public function viewDashboard();
}

class DashboardController extends Controller implements DashboardInterface
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
     * View Dashboard
     *
     * @return mixed
     */
    public function viewDashboard(): mixed
    {
        try {

            return view('admin.pages.dashboard.dashboard');
        } catch (Exception $exception) {
            return redirect()->back()->with('message', [
                'status' => 'error',
                'title' => 'An error occcured',
                'description' => $exception->getMessage()
            ]);
        }
    }
}