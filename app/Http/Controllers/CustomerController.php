<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CustomerCoupon;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\Facades\DNS1DFacade;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function profile()
    {
        return view('customer.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->gender = $request->input('gender');
        $user->save();

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
    }

    public function showQRandBarCode()
    {
        $user = Auth::user();
        $qrCode = QrCode::size(100)->generate($user->id)->toHtml();
        $barCode = DNS1DFacade::getBarcodeHTML($user->id, 'C39');

        return view('customer.membership', compact('qrCode', 'barCode'));
    }

    public function submitContactForm(Request $request)
    {
        // Create a new ticket
        $ticket = new Ticket();
        $ticket->customer_id = Auth::user()->id;
        $ticket->status = 'New';
        $ticket->message = $request->input('message');
        $ticket->query_type = $request->input('query_type');

        // Find the support staff with the least number of assigned tickets
        $leastAssignedSupportStaff = Ticket::select('support_staff_id', DB::raw('COUNT(*) as ticket_count'))
            ->where('status', '<>', 'Closed')
            ->groupBy('support_staff_id')
            ->orderBy('ticket_count', 'asc')
            ->first();

        if ($leastAssignedSupportStaff) {
            $ticket->support_staff_id = $leastAssignedSupportStaff->support_staff_id;
        }

        $ticket->save();

        return redirect()->route('customer.support.contactUs')->with('success', 'Your message has been sent. Our team will get back to you shortly.');
    }

    public function getCustomerCouponsInfo()
    {
        $customer = Auth::user();

        $couponsInfo = CustomerCoupon::select('coupons.name', 'coupons.discount', 'coupons.conditions', 'customer_coupons.end_date', 'customer_coupons.code')
            ->join('coupons', 'customer_coupons.coupon_id', '=', 'coupons.id')
            ->where('customer_coupons.customer_id', $customer->id)
            ->where('customer_coupons.status', 'Claimed')
            ->get();

        return view('customer.coupons', ['couponsInfo' => $couponsInfo]);
    }

    public function getCouponsInfo()
    {
        $customer = Auth::user();

        $customerCouponsInfo = CustomerCoupon::select('coupons.name', 'coupons.discount', 'coupons.conditions', 'customer_coupons.end_date', 'customer_coupons.code')
            ->join('coupons', 'customer_coupons.coupon_id', '=', 'coupons.id')
            ->where('customer_coupons.customer_id', $customer->id)
            ->where('customer_coupons.status', 'Claimed')
            ->get();

        $allCouponsInfo = Coupon::all(); // Fetch all available coupons


        return view('customer.coupons', ['customerCouponsInfo' => $customerCouponsInfo, 'allCouponsInfo' => $allCouponsInfo]);
    }

    public function claimCoupon(Request $request)
    {
        $couponId = $request->input('coupon_id');
        $customer = Auth::user();
        $coupon = Coupon::find($couponId);

        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found.'], 404);
        }

        if ($customer->points >= $coupon->redemption_points) {
            // Generate a unique coupon code
            $couponCode = $this->generateCouponCode();

            // Calculate end date (30 days from now)
            $endDate = Carbon::now()->addDays(30);

            // Create a new customer_coupon record
            $customerCoupon = new CustomerCoupon();
            $customerCoupon->customer_id = $customer->id;
            $customerCoupon->coupon_id = $coupon->id;
            $customerCoupon->status = 'Claimed';
            $customerCoupon->code = $couponCode;
            $customerCoupon->start_date = Carbon::now();
            $customerCoupon->end_date = $endDate;
            $customerCoupon->save();

            // Update customer points
            $customer->points -= $coupon->redemption_points;
            $customer->save();

            // return redirect()->route('customer.coupons')->with('success', 'Coupon claimed successfully.');
            return response()->json(['message' => 'Coupon claimed successfully.'], 200);
        } else {
            // return redirect()->route('customer.coupons')->with('error', 'Insufficient points to claim this coupon.');
            return response()->json(['message' => 'Insufficient points to claim this coupon.'], 403);
        }
    }

    // Helper function to generate a unique coupon code using a combination of random numbers and timestamp
    private function generateCouponCode()
    {
        $timestamp = now()->timestamp; // Get current timestamp
        $randomDigits = '';

        // Generate random digits
        for ($i = 0; $i < 5; $i++) {
            $randomDigits .= rand(0, 9);
        }

        // Combine timestamp and random digits to create a unique code
        $couponCode = $timestamp . $randomDigits;

        // Check if the coupon code already exists
        while (CustomerCoupon::where('code', $couponCode)->exists()) {
            // If it exists, regenerate the random digits
            $randomDigits = '';
            for ($i = 0; $i < 5; $i++) {
                $randomDigits .= rand(0, 9);
            }

            // Combine timestamp and new random digits
            $couponCode = $timestamp . $randomDigits;
        }

        return $couponCode;
    }
}
