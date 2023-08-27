<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\CheckoutProduct;
use App\Models\Customer;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function getAllLeadsForMarketingStaff()
    {
        // Retrieve all tickets where support_staff_id matches the logged-in support staff's ID
        $leads = Lead::where('marketing_staff_id', Auth::user()->id)
            ->orderByRaw(
                "
        CASE
            WHEN status = 'New' THEN 1
            WHEN status = 'Interested' THEN 2
            WHEN status = 'Not Interested' THEN 3
            WHEN status = 'Contacted' THEN 4
            ELSE 5
        END"
            )
            ->get();

        if ($leads->isEmpty()) {
            return view('marketingStaff.leadManagement')->with('error', 'No leads found.');
        }

        // Group tickets by query type
        $groupedLeads = $leads->groupBy('status');

        return view('marketingStaff.leadManagement', compact('groupedLeads', 'leads'));
    }

    public function viewLeadDetails($id)
    {
        // Retrieve the ticket by ID
        $lead = Lead::findOrFail($id);

        // Check if the ticket belongs to the logged-in support staff
        if ($lead->marketing_staff_id !== Auth::user()->id) {
            return redirect()->route('marketingStaff.leadManagement')->with('error', 'Access denied.');
        }

        return view('marketingStaff.leadDetails', compact('lead'));
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

    public function updateLeadStatus(Request $request)
    {
        $leadId = $request->input('lead_id');
        $newStatus = $request->input('new_status');

        $lead = Lead::findOrFail($leadId);
        $oldStatus = $lead->status; // Get the current status before updating
        $lead->status = $newStatus;

        $lead->save();

        return response()->json([
            'updatedLead' => [
                'id' => $lead->id,
                'oldStatus' => $oldStatus,
                'newStatus' => $newStatus,
            ],
        ]);
    }

    public function addLead()
    {
        return view('marketingStaff.addLead');
    }

    public function storeLead(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'contact' => 'required',
                'email' => 'required',
                'gender' => 'required',
                'activity' => 'required',
            ]);

            Lead::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'contact' => $request->contact,
                'email' => $request->email,
                'gender' => $request->gender,
                'status' => 'New',
                'activity' => $request->activity,
                'marketing_staff_id' => Auth::user()->id,
            ]);

            return redirect()->route('marketingStaff.leadManagement')->with('success', 'Lead added successfully!');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with('error', 'An error occurred while adding the lead. Please try again.');
        }
    }
}
