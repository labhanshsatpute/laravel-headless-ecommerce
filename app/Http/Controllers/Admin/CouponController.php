<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CouponDiscountType;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use App\Models\Coupon;
use Exception;

interface CouponInterface
{
    public function viewCouponList();
    public function viewCouponCreate();
    public function viewCouponUpdate($id);
    public function handleCouponCreate(Request $request);
    public function handleCouponUpdate(Request $request, $id);
    public function handleToggleCouponStatus(Request $request);
    public function handleCouponDelete($id);
}

class CouponController extends Controller implements CouponInterface
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
     * View Coupons List
     *
     * @return mixed
     */
    public function viewCouponList(): mixed
    {
        try {

            $coupons = Coupon::all();
            $discount_type = CouponDiscountType::class;

            return view('admin.pages.coupon.coupon-list', [
                'coupons' => $coupons,
                'discount_type' => $discount_type
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
     * View Cpupon Create
     *
     * @return mixed
     */
    public function viewCouponCreate(): mixed
    {
        try {

            $discount_type = CouponDiscountType::class;

            return view('admin.pages.coupon.coupon-create', [
                'discount_type' => $discount_type
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
     * View Coupon Update
     *
     * @return mixed
     */
    public function viewCouponUpdate($id): mixed
    {
        try {

            $coupon = Coupon::find($id);

            if (!$coupon) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Coupon not found',
                    'description' => 'Coupon not found with specified ID'
                ]);
            }

            $discount_type = CouponDiscountType::class;

            return view('admin.pages.coupon.coupon-update', [
                'coupon' => $coupon,
                'discount_type' => $discount_type
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
     * Handle Create Coupon
     *
     * @return RedirectResponse
     */
    public function handleCouponCreate(Request $request): RedirectResponse
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'min:1', 'max:250', 'unique:coupons'],
                'code' => ['required', 'string', 'min:1', 'max:100', 'unique:coupons'],
                'summary' => ['nullable', 'string', 'min:1', 'max:500'],
                'start_date' => ['required', 'string', 'date_format:Y-m-d'],
                'expire_date' => ['required', 'string', 'date_format:Y-m-d'],
                'discount_type' => ['required', 'string', new Enum(CouponDiscountType::class)],
                'discount_value' => ['required', 'numeric', 'min:0'],
                'minimum_purchase' => ['required', 'numeric', 'min:0'],
                'maximum_discount' => ['required', 'numeric', 'min:0']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $coupon = new Coupon();
            $coupon->name = $request->input('name');
            $coupon->code = $request->input('code');
            $coupon->summary = $request->input('summary');
            $coupon->start_date = $request->input('start_date');
            $coupon->expire_date = $request->input('expire_date');
            $coupon->discount_type = $request->input('discount_type');
            $coupon->discount_value = $request->input('discount_value');
            $coupon->minimum_purchase = $request->input('minimum_purchase');
            $coupon->maximum_discount = $request->input('maximum_discount');
            $coupon->save();

            return redirect()->route('admin.view.coupon.list')->with('message', [
                'status' => 'success',
                'title' => 'Coupon created',
                'description' => 'The coupon is successfully created.'
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
     * Handle Update Coupon
     *
     * @return RedirectResponse
     */
    public function handleCouponUpdate(Request $request, $id): RedirectResponse
    {
        try {

            $coupon = Coupon::find($id);

            if (!$coupon) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Coupon not found',
                    'description' => 'Coupon not found with specified ID'
                ]);
            }

            $validator = Validator::make($request->all(), [
                'name' => [
                    'required', 'string', 'min:1', 'max:250',
                    Rule::unique('coupons')->ignore($coupon->name, 'name')
                ],
                'code' => [
                    'required', 'string', 'min:1', 'max:250',
                    Rule::unique('coupons')->ignore($coupon->code, 'code')
                ],
                'summary' => ['nullable', 'string', 'min:1', 'max:500'],
                'start_date' => ['required', 'string', 'date_format:Y-m-d'],
                'expire_date' => ['required', 'string', 'date_format:Y-m-d'],
                'discount_type' => ['required', 'string', new Enum(CouponDiscountType::class)],
                'discount_value' => ['required', 'numeric', 'min:0'],
                'minimum_purchase' => ['required', 'numeric', 'min:0'],
                'maximum_discount' => ['required', 'numeric', 'min:0']
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $coupon->name = $request->input('name');
            $coupon->code = $request->input('code');
            $coupon->summary = $request->input('summary');
            $coupon->start_date = $request->input('start_date');
            $coupon->expire_date = $request->input('expire_date');
            $coupon->discount_type = $request->input('discount_type');
            $coupon->discount_value = $request->input('discount_value');
            $coupon->minimum_purchase = $request->input('minimum_purchase');
            $coupon->maximum_discount = $request->input('maximum_discount');
            $coupon->update();

            return redirect()->route('admin.view.coupon.list')->with('message', [
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
     * Handle Toggle Coupon Status
     *
     * @return Response
     */
    public function handleToggleCouponStatus(Request $request): Response
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => ['required', 'numeric', 'exists:coupons']
            ]);

            if ($validator->fails()) {
                return response([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'error' => $validator->errors()->getMessages()
                ], 200);
            }

            $coupon = Coupon::find($request->input('id'));
            $coupon->status = !$coupon->status;
            $coupon->update();

            return response([
                'status' => true,
                'message' => "Status successfully updated",
                'data' => $coupon
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
     * Handle Delete Coupon
     *
     * @return RedirectResponse
     */
    public function handleCouponDelete($id): RedirectResponse
    {
        try {

            $coupon = Coupon::find($id);

            if (!$coupon) {
                return redirect()->back()->with('message', [
                    'status' => 'warning',
                    'title' => 'Coupon not found',
                    'description' => 'Coupon not found with specified ID'
                ]);
            }

            $coupon->delete();

            return redirect()->route('admin.view.coupon.list')->with('message', [
                'status' => 'success',
                'title' => 'Coupon deleted',
                'description' => 'The coupon is successfully deleted.'
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
