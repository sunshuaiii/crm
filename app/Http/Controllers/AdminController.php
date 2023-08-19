<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Coupon;
use App\Models\MarketingStaff;
use App\Models\SupportStaff;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function getAllCoupons()
    {
        $coupons = Coupon::all(); // Retrieve all coupons from the database
        return view('admin.couponManagement', compact('coupons'));
    }

    public function addCoupon()
    {
        return view('admin.addCoupon');
    }

    public function storeCoupon(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'discount' => 'required|numeric',
                'redemption_points' => 'required|numeric',
                'conditions' => 'required',
            ]);

            Coupon::create([
                'name' => $request->name,
                'discount' => $request->discount,
                'redemption_points' => $request->redemption_points,
                'conditions' => $request->conditions,
            ]);

            return redirect()->route('admin.couponManagement')->with('success', 'Coupon added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the coupon. Please try again.');
        }
    }

    public function editCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.editCoupon', compact('coupon'));
    }

    public function updateCoupon(Request $request, $id)
    {
        try {
            $coupon = Coupon::findOrFail($id);

            $request->validate([
                'name' => 'required|unique:coupons,name,' . $id,
                'discount' => 'required|numeric',
                'redemption_points' => 'required|numeric',
            ]);

            $coupon->update([
                'name' => $request->name,
                'discount' => $request->discount,
                'redemption_points' => $request->redemption_points,
                'conditions' => $request->conditions,
            ]);

            return redirect()->route('admin.couponManagement')->with('success', 'Coupon updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the coupon. Please try again.');
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdminRegisterForm()
    {
        return view('admin.registerStaff', ['url' => 'admin']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMarketingStaffRegisterForm()
    {
        return view('admin.registerStaff', ['url' => 'marketingStaff']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSupportStaffRegisterForm()
    {
        return view('admin.registerStaff', ['url' => 'supportStaff']);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function createAdmin(Request $request)
    {
        try {
            $this->validator($request->all())->validate();
            Admin::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return redirect()->intended('admin/register/admin')->with('success', 'New admin registered successful!');
        } catch (QueryException $e) {
            // Handle database integrity constraint violation
            if ($e->getCode() == 23000) { // Integrity constraint violation error code
                $errorMessage = "The email address is already registered.";
                return redirect()->back()->withInput()->withErrors([$errorMessage]);
            }
            // If the exception is not due to a duplicate email, re-throw it
            throw $e;
        } catch (ValidationException $e) {
            // If validation fails, redirect back with validation errors
            return redirect()->back()->withInput()->withErrors($e->errors());
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function createMarketingStaff(Request $request)
    {
        try {
            $this->validator($request->all())->validate();
            MarketingStaff::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return redirect()->intended('admin/register/marketingStaff')->with('success', 'New marketing staff registered successful!');
        } catch (QueryException $e) {
            // Handle database integrity constraint violation
            if ($e->getCode() == 23000) { // Integrity constraint violation error code
                $errorMessage = "The email address is already registered.";
                return redirect()->back()->withInput()->withErrors([$errorMessage]);
            }
            // If the exception is not due to a duplicate email, re-throw it
            throw $e;
        } catch (ValidationException $e) {
            // If validation fails, redirect back with validation errors
            return redirect()->back()->withInput()->withErrors($e->errors());
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function createSupportStaff(Request $request)
    {
        try {
            $this->validator($request->all())->validate();
            SupportStaff::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return redirect()->intended('admin/register/supportStaff')->with('success', 'New support staff registered successful!');
        } catch (QueryException $e) {
            // Handle database integrity constraint violation
            if ($e->getCode() == 23000) { // Integrity constraint violation error code
                $errorMessage = "The email address is already registered.";
                return redirect()->back()->withInput()->withErrors([$errorMessage]);
            }
            // If the exception is not due to a duplicate email, re-throw it
            throw $e;
        } catch (ValidationException $e) {
            // If validation fails, redirect back with validation errors
            return redirect()->back()->withInput()->withErrors($e->errors());
        }
    }

    public function searchCustomers(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $filterBy = $request->input('filterBy', 'first_name'); // Default filter

        $query = Customer::query();

        if (!empty($keyword)) {
            $query->where($filterBy, 'LIKE', "%$keyword%");
        }

        $customers = $query->get(); // Retrieve the customers that match the search criteria

        return view('searchCustomer', compact('customers', 'keyword', 'filterBy'));
    }

    public function viewCustomer($id)
    {
        $customer = Customer::findOrFail($id); // Retrieve the customer by ID

        return view('customerDetails', compact('customer'));
    }
}
