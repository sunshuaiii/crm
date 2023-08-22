<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\CheckoutProduct;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use App\Models\Product;
use App\Models\Ticket;
use App\Rules\AboveEighteen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        try {
            $user = Auth::user();
            $user->username = $request->input('username');
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->gender = $request->input('gender');

            // Additional fields as you've done

            if ($request->input('dob')) {
                $user->dob = $request->input('dob');

                $validator = Validator::make($request->all(), [
                    'dob' => new AboveEighteen,
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }

            $user->save();

            return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the profile.');
        }
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

    public function getCouponsInfo()
    {
        $customer = Auth::user();

        // valid coupons that the customer claimed
        $validCustomerCouponsInfo = CustomerCoupon::select('coupons.name', 'coupons.discount', 'coupons.conditions', 'customer_coupons.end_date', 'customer_coupons.code', 'customer_coupons.coupon_id')
            ->join('coupons', 'customer_coupons.coupon_id', '=', 'coupons.id')
            ->where('customer_coupons.customer_id', $customer->id)
            ->where('customer_coupons.status', 'Claimed')
            ->orderBy('customer_coupons.end_date', 'asc')
            ->whereDate('customer_coupons.end_date', '>=', now())
            ->get();

        // expired coupons that the customer claimed
        $expiredCustomerCouponsInfo = CustomerCoupon::select('coupons.name', 'coupons.discount', 'coupons.conditions', 'customer_coupons.end_date', 'customer_coupons.code', 'customer_coupons.coupon_id')
            ->join('coupons', 'customer_coupons.coupon_id', '=', 'coupons.id')
            ->where('customer_coupons.customer_id', $customer->id)
            ->where('customer_coupons.status', 'Claimed')
            ->orderBy('customer_coupons.end_date', 'asc')
            ->whereDate('customer_coupons.end_date', '<', now())
            ->get();

        // all available coupons for that customer
        $allCouponsInfo = Coupon::where('id', '!=', 8)->get(); // Fetch all available coupons except for new member coupon

        return view('customer.coupons', ['validCustomerCouponsInfo' => $validCustomerCouponsInfo, 'expiredCustomerCouponsInfo' => $expiredCustomerCouponsInfo, 'allCouponsInfo' => $allCouponsInfo]);
    }

    public function claimCoupon(Request $request)
    {
        $couponId = $request->input('coupon_id');
        $customer = Auth::user();
        $coupon = Coupon::find($couponId);

        if (!$coupon) {
            // return response()->json(['message' => 'Sorry, coupon not found.'], 404);
            return redirect()->route('customer.coupons')->with('error', 'Sorry, coupon not found.');
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

            return redirect()->route('customer.coupons')->with('success', '' . $coupon->name . ' claimed successfully.');
            // return response()->json(['message' => 'Coupon claimed successfully.'], 200);
        } else {
            return redirect()->route('customer.coupons')->with('error', 'You have insufficient points to claim the ' . $coupon->name . '.');
            // return response()->json(['message' => 'Insufficient points to claim this coupon.'], 403);
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

    public function getCouponDetails($couponCode)
    {
        $couponDetails = CustomerCoupon::select(
            'coupons.name',
            'coupons.discount',
            'coupons.conditions',
            'coupons.redemption_points',
            'customer_coupons.id',
            'customer_coupons.status',
            'customer_coupons.start_date',
            'customer_coupons.end_date',
            'customer_coupons.code',
            'customer_coupons.coupon_id'
        )
            ->join('coupons', 'customer_coupons.coupon_id', '=', 'coupons.id')
            ->where('customer_coupons.code', $couponCode)
            ->first(); // Use first() instead of get() to retrieve a single result


        if (!$couponDetails) {
            return redirect()->route('customer.coupons')->with('error', 'Coupon details not found.');
        }

        $barCode = DNS1DFacade::getBarcodeHTML($couponDetails->code, 'C39');

        return view('customer.couponDetails', ['couponDetails' => $couponDetails, 'barCode' => $barCode]);
    }

    public function membershipCheckout(Request $request)
    {
        // Get the authenticated customer
        $customer = Auth::user();

        // Generate random payment method
        $paymentMethods = ['Credit card', 'Debit Card', 'Cash', 'E-wallet'];
        $randomPaymentMethod = $paymentMethods[array_rand($paymentMethods)];

        // Create a new checkout record
        $checkout = new Checkout();
        $checkout->date = Carbon::now();
        $checkout->payment_method = $randomPaymentMethod;
        $checkout->customer_id = $customer->id;
        $checkout->customer_coupon_id = null;
        $checkout->save();

        // Generate random number of checkout_products records
        $numProducts = rand(1, 20);

        // Get random product IDs
        $productIds = Product::pluck('id')->toArray();

        // Insert checkout_products records
        for ($i = 0; $i < $numProducts; $i++) {
            $productId = $productIds[array_rand($productIds)];
            $quantity = rand(1, 50);

            $product = Product::find($productId);

            if ($product) {
                $checkoutProduct = new CheckoutProduct();
                $checkoutProduct->checkout_id = $checkout->id;
                $checkoutProduct->product_id = $productId;
                $checkoutProduct->quantity = $quantity;
                $checkoutProduct->save();
            } else {
                // Handle the case when the product is not found
                // For example, you could log an error message or skip the iteration
            }
        }

        $checkouts = Checkout::with('checkoutProducts.product')->find($checkout->id);

        // Calculate the total amount spent for the checkout
        $totalAmount = $checkouts->checkoutProducts->sum(function ($item) {
            return $item->quantity * $item->product->unit_price;
        });

        // Calculate points to be credited (1 point per RM 1 spent)
        $pointsToCredit = floor($totalAmount);

        // Update the customer's points
        $customer->updatePoints($pointsToCredit);

        return redirect()->route('customer.checkoutDetails', ['id' => $checkout->id])->with('success', 'Membership scanned successfully! Thank you for shopping with us!');
    }

    public function redeemCoupon(Request $request, $couponCode)
    {
        // Get the authenticated customer
        $customer = Auth::user();

        // validate the coupon before checkout

        // check if the code is valid
        $couponDetails = CustomerCoupon::where('code', $couponCode)->first();
        if (!$couponDetails) {
            return redirect()->route('customer.coupons')->with('error', 'Coupon not found.');
        }

        // check if the coupon is expired
        $couponValid = CustomerCoupon::where('code', $couponCode)->whereDate('customer_coupons.end_date', '>=', now())->first();
        if (!$couponValid) {
            return redirect()->route('customer.coupons')->with('error', 'The coupon is expired.');
        }

        // check if the coupon is redeemed
        $couponClaimed = CustomerCoupon::where('code', $couponCode)->where('customer_coupons.status', 'Claimed')->first();
        if (!$couponClaimed) {
            return redirect()->route('customer.coupons')->with('error', 'Sorry, you have already redeemed this coupon.');
        }

        // Generate random payment method
        $paymentMethods = ['Credit card', 'Debit Card', 'Cash', 'E-wallet'];
        $randomPaymentMethod = $paymentMethods[array_rand($paymentMethods)];

        // Create a new checkout record
        $checkout = new Checkout();
        $checkout->date = Carbon::now();
        $checkout->payment_method = $randomPaymentMethod;
        $checkout->customer_id = $customer->id;
        $checkout->customer_coupon_id = $couponDetails->id;
        $checkout->save();

        // Generate random number of checkout_products records
        $numProducts = rand(1, 20);

        // Get random product IDs
        $productIds = Product::pluck('id')->toArray();

        // Insert checkout_products records
        for ($i = 0; $i < $numProducts; $i++) {
            $productId = $productIds[array_rand($productIds)];
            $quantity = rand(1, 50);

            $product = Product::find($productId);

            if ($product) {
                $checkoutProduct = new CheckoutProduct();
                $checkoutProduct->checkout_id = $checkout->id;
                $checkoutProduct->product_id = $productId;
                $checkoutProduct->quantity = $quantity;
                $checkoutProduct->save();
            } else {
                // Handle the case when the product is not found
                // For example, you could log an error message or skip the iteration
            }
        }

        // Update the status of the claimed coupon to "Redeemed"
        $customerCoupon = CustomerCoupon::where('code', $couponCode)->first();
        if ($customerCoupon) {
            $customerCoupon->status = 'Redeemed';
            $customerCoupon->save();
        }

        $checkouts = Checkout::with('checkoutProducts.product')->find($checkout->id);

        // Calculate the total amount spent for the checkout
        $totalAmount = $checkouts->checkoutProducts->sum(function ($item) {
            return $item->quantity * $item->product->unit_price;
        });

        // Deduct the coupon discount from the total amount
        $totalAmount -= $couponDetails->coupon->discount;

        if (!$couponDetails) {
            return redirect()->route('customer.coupons')->with('error', 'Coupon not found.');
        }

        // dd($couponDetails->toArray());

        // Calculate points to be credited (1 point per RM 1 spent)
        if ($totalAmount <= 0) {
            $pointsToCredit = 0;
            $totalAmount = 0;
        } else {
            $pointsToCredit = floor($totalAmount);
        }

        // Update the customer's points
        $customer->updatePoints($pointsToCredit);

        return redirect()->route('customer.checkoutDetails', ['id' => $checkout->id])->with('success', 'Coupon claimed successfully! Thank you for shopping with us!');
    }

    public function getCheckoutDetails($id)
    {
        $checkout = Checkout::with('checkoutProducts.product', 'customerCoupon.coupon')->find($id);

        if (!$checkout) {
            return redirect()->route('customer.coupons')->with('error', 'Checkout details not found.');
        }

        // Calculate the total amount spent for the checkout
        $totalAmount = 0;

        foreach ($checkout->checkoutProducts as $checkoutProduct) {
            $totalAmount += ($checkoutProduct->product->unit_price * $checkoutProduct->quantity);
        }

        // Calculate the coupon discount
        $couponDiscount = 0;

        if ($checkout->customerCoupon) {
            $couponDiscount = $checkout->customerCoupon->coupon->discount;
        }

        $finalAmount = $totalAmount - $couponDiscount;

        // Calculate points to be credited (1 point per RM 1 spent)
        if ($totalAmount - $couponDiscount <= 0) {
            $pointsToCredit = 0;
            $finalAmount = 0;
        } else {
            $pointsToCredit = floor($totalAmount - $couponDiscount);
        }

        return view('customer.checkoutDetails', [
            'checkout' => $checkout,
            'totalAmount' => $totalAmount,
            'finalAmount' => $finalAmount,
            'couponDiscount' => $couponDiscount,
            'pointsToCredit' => $pointsToCredit,
        ]);
    }

    public function getCheckoutHistory()
    {
        $customer = Auth::user();
        $checkoutHistory = Checkout::where('customer_id', $customer->id)
            ->with('checkoutProducts.product', 'customerCoupon.coupon')
            ->orderBy('date', 'desc')
            ->get();

        $checkoutSummaries = [];

        foreach ($checkoutHistory as $checkout) {
            $totalAmount = 0;

            foreach ($checkout->checkoutProducts as $checkoutProduct) {
                $totalAmount += ($checkoutProduct->product->unit_price * $checkoutProduct->quantity);
            }

            $couponDiscount = 0;

            if ($checkout->customerCoupon) {
                $couponDiscount = $checkout->customerCoupon->coupon->discount;
            }

            $finalAmount = $totalAmount - $couponDiscount;

            // Calculate points to be credited (1 point per RM 1 spent)
            if ($finalAmount <= 0) {
                $pointsToCredit = 0;
                $finalAmount = 0;
            } else {
                $pointsToCredit = floor($finalAmount);
            }

            $checkoutSummary = [
                'checkout' => $checkout,
                'totalAmount' => $totalAmount,
                'couponDiscount' => $couponDiscount,
                'finalAmount' => $finalAmount,
                'pointsToCredit' => $pointsToCredit,
            ];

            $checkoutSummaries[] = $checkoutSummary;
        }

        return view('customer.checkoutHistory', ['checkoutSummaries' => $checkoutSummaries]);
    }
}
