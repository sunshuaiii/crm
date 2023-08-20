<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Coupon;
use App\Models\MarketingStaff;
use App\Models\SupportStaff;
use App\Models\Customer;
use App\Models\CustomerCoupon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function adminHome()
    {
        $claimedCoupons = CustomerCoupon::where('status', "Claimed")->get()->count();
        $redeemedCoupons = CustomerCoupon::where('status', "Redeemed")->get()->count();
        $totalAvailableCoupons = CustomerCoupon::where('status', "Claimed")->whereDate('end_date', '>=', now())->count();
        $expiredCoupons = CustomerCoupon::where('status', "Claimed")->whereDate('end_date', '<=', now())->count();

        // Redemption Rate by Coupon
        $redemptionRates = CustomerCoupon::select(
            'coupons.name',
            DB::raw('SUM(CASE WHEN customer_coupons.status = "Redeemed" THEN 1 ELSE 0 END) AS redeemed_count'),
            DB::raw('SUM(CASE WHEN customer_coupons.status = "Claimed" THEN 1 ELSE 0 END) AS claimed_count')
        )
            ->join('coupons', 'customer_coupons.coupon_id', '=', 'coupons.id')
            ->groupBy('coupons.name')
            ->get();

        // Top Redeemed Coupons
        $topRedeemedCoupons = CustomerCoupon::select(
            'coupons.name',
            DB::raw('COUNT(*) AS redeemed_count')
        )
            ->join('coupons', 'customer_coupons.coupon_id', '=', 'coupons.id')
            ->where('customer_coupons.status', 'Redeemed')
            ->groupBy('coupons.name')
            ->orderByDesc('redeemed_count')
            ->limit(5) // You can change the limit as needed
            ->get();

        // Redemption Points vs. Discount
        $redemptionVsDiscount = Coupon::select(
            'name',
            DB::raw('AVG(redemption_points) AS avg_redemption_points'),
            DB::raw('AVG(discount) AS avg_discount')
        )
            ->groupBy('name')
            ->get();

        // Coupon Status Distribution
        $couponStatusDistribution = CustomerCoupon::select(
            'status',
            DB::raw('COUNT(*) AS status_count')
        )
            ->groupBy('status')
            ->get();

        // Coupon Usage Over Time
        $couponUsageOverTime = CustomerCoupon::select(
            DB::raw('DATE(updated_at) as date'),
            DB::raw('COUNT(*) as coupon_count')
        )
            ->where('status', 'Redeemed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Coupon Expiry Analysis
        $expiryAnalysis = CustomerCoupon::select(
            DB::raw('DATEDIFF(end_date, NOW()) as remaining_days'),
            DB::raw('COUNT(*) as coupon_count')
        )
            ->groupBy('remaining_days')
            ->orderBy('remaining_days')
            ->get();

        // Query data for coupon usage by customer segments
        $segmentLabels = ['Segment A', 'Segment B', 'Segment C', 'Segment D'];

        $claimedCouponsBySegment = [];
        $redeemedCouponsBySegment = [];

        foreach ($segmentLabels as $segmentLabel) {
            // Get claimed coupon count by segment
            $claimedCount = CustomerCoupon::whereHas('customer', function ($query) use ($segmentLabel) {
                $query->where('c_segment', $segmentLabel);
            })->where('status', 'Claimed')->count();

            // Get redeemed coupon count by segment
            $redeemedCount = CustomerCoupon::whereHas('customer', function ($query) use ($segmentLabel) {
                $query->where('c_segment', $segmentLabel);
            })->where('status', 'Redeemed')->count();

            $claimedCouponsBySegment[] = $claimedCount;
            $redeemedCouponsBySegment[] = $redeemedCount;
        }

        $totalMarketingStaff = MarketingStaff::all()->count();
        $totalSupportStaff = SupportStaff::all()->count();

        return view('admin.adminHome', compact(
            'claimedCoupons',
            'redeemedCoupons',
            'totalAvailableCoupons',
            'expiredCoupons',
            'redemptionRates',
            'topRedeemedCoupons',
            'redemptionVsDiscount',
            'couponStatusDistribution',
            'couponUsageOverTime',
            'expiryAnalysis',
            'segmentLabels',
            'claimedCouponsBySegment',
            'redeemedCouponsBySegment',
            'totalMarketingStaff',
            'totalSupportStaff'
        ));
    }

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
        $filterBy = $request->input('filterBy', 'id'); // Default filter

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
