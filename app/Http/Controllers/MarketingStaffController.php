<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\CheckoutProduct;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketingStaffController extends Controller
{
    public function marketingStaffHome()
    {
        $data = '';

        return view('marketingStaff.marketingStaffHome', compact(
            'data',
        ));
    }

    public function reportGeneration()
    {
        $data = '';

        return view('marketingStaff.reportGeneration', compact(
            'data',
        ));
    }

    public function leadManagement()
    {
        $data = '';

        return view('marketingStaff.leadManagement', compact(
            'data',
        ));
    }

    public function updateRfmScores()
    {
        // $latestDate = Checkout::max('date');
        $latestDate = Carbon::now();

        $customers = Customer::all();

        foreach ($customers as $customer) {
            $latestCheckoutDate = CheckoutProduct::join('checkouts', 'checkout_products.checkout_id', '=', 'checkouts.id')
                ->where('checkouts.customer_id', $customer->id)
                ->max('checkouts.date');

            if ($latestCheckoutDate) {
                $recencyScore = $latestDate->diffInDays($latestCheckoutDate);
            } else {
                // Set a default recency score if no checkout history
                $recencyScore = 365; // Assuming 1 year
            }

            $frequencyScore = $customer->checkouts->count(); // Assuming checkouts relation in Customer model
            $monetaryScore = DB::table('checkouts')
                ->join('checkout_products', 'checkouts.id', '=', 'checkout_products.checkout_id')
                ->join('products', 'checkout_products.product_id', '=', 'products.id')
                ->where('checkouts.customer_id', $customer->id)
                ->sum(DB::raw('products.unit_price * checkout_products.quantity'));

            // Update the RFM scores in the customers table
            $customer->r_score = $recencyScore;
            $customer->f_score = $frequencyScore;
            $customer->m_score = $monetaryScore;
            $customer->save();
        }

        return redirect()->route('marketingStaff.marketingStaffHome')->with('success', 'RFM scores updated successfully!');
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
