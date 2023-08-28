<?php

namespace App\Http\Controllers;

use App\Models\CheckoutProduct;
use App\Models\Customer;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;

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

    public function updateCSegment(Request $request)
    {
        $pythonScript = base_path('kmeans_script.py');

        $process = new Process(["python", $pythonScript]);
        $process->run();

        if ($process->isSuccessful()) {
            return redirect()->back()->with('success', 'Python script executed.');
        } else {
            return redirect()->back()->with('error', 'An error occurred while executing the Python script.');
        }
    }

    // // Extract the log-transformed values into separate arrays
    // $rScores = $logTfdData->pluck('r_score')->toArray();
    // $fScores = $logTfdData->pluck('f_score')->toArray();
    // $mScores = $logTfdData->pluck('m_score')->toArray();

    // // Combine them into a multi-dimensional array
    // $data = [];
    // foreach ($rScores as $index => $rScore) {
    //     $data[] = [$rScore, $fScores[$index], $mScores[$index]];
    // }

    // // Initialize and fit the StandardScaler
    // $scaler = new StandardScaler();
    // $scaler->fit($data);

    // // Scale the data
    // $scaledData = $scaler->transform($data);

    // public function updateCSegment()
    // {
    //     // Step 1: Fetch the scores from the database
    //     $customers = DB::table('customers')->select('r_score', 'f_score', 'm_score')->get();

    //     // Transform Eloquent collection into an array
    //     $scores = $customers->map(function ($customer) {
    //         return [
    //             'r_score' => $customer->r_score,
    //             'f_score' => $customer->f_score,
    //             'm_score' => $customer->m_score,
    //         ];
    //     })->toArray();

    //     // Create a collection from the scores array
    //     $RFMScores = collect($scores);

    //     // Step 2: Perform Log transformation to bring data into normal or near-normal distribution
    //     // Apply natural logarithm and round to three decimal places
    //     $logTfdData = $RFMScores->map(function ($score) {
    //         return [
    //             'r_score' => round(log($score['r_score']), 3),
    //             'f_score' => round(log($score['f_score']), 3),
    //             'm_score' => round(log($score['m_score']), 3),
    //         ];
    //     });

    //     // Step 3: Bring the data on the same scale
    //     // Calculate means and standard deviations for each column
    //     $meanValues = [
    //         'r_score' => $logTfdData->avg('r_score'),
    //         'f_score' => $logTfdData->avg('f_score'),
    //         'm_score' => $logTfdData->filter(function ($item) {
    //             // Exclude zero and negative values from the calculation
    //             return $item['m_score'] > 0;
    //         })->avg('m_score'),
    //     ];

    //     $stdDevValues = [
    //         'r_score' => sqrt(
    //             $logTfdData->map(function ($item) use ($meanValues) {
    //                 return pow($item['r_score'] - $meanValues['r_score'], 2);
    //             })->sum() / ($logTfdData->count() - 1)
    //         ),

    //         'f_score' => sqrt(
    //             $logTfdData->map(function ($item) use ($meanValues) {
    //                 return pow($item['f_score'] - $meanValues['f_score'], 2);
    //             })->sum() / ($logTfdData->count() - 1)
    //         ),

    //         'm_score' => sqrt(
    //             $logTfdData->filter(function ($item) {
    //                 // Exclude zero and negative values from the calculation
    //                 return $item['m_score'] > 0;
    //             })->map(function ($item) use ($meanValues) {
    //                 return pow($item['m_score'] - $meanValues['m_score'], 2);
    //             })->sum() / ($logTfdData->count() - 1)
    //         ),
    //     ];

    //     // Standardize the data
    //     $standardizedData = $logTfdData->map(function ($item) use ($meanValues, $stdDevValues) {
    //         return [
    //             '0' => ($item['r_score'] - $meanValues['r_score']) / sqrt($stdDevValues['r_score']),
    //             '1' => ($item['f_score'] - $meanValues['f_score']) / sqrt($stdDevValues['f_score']),
    //             '2' => ($item['m_score'] - $meanValues['m_score']) / sqrt($stdDevValues['m_score']),
    //         ];
    //     });

    //     // Step 4: K-Means Clustering
    //     // Convert the Collection to a proper array for clustering
    //     $standardizedDataArray = $standardizedData->toArray();
    //     // dd($standardizedDataArray);

    //     // Create a KMeans instance and perform clustering
    //     $kMeans = new KMeans(3, KMeans::INIT_KMEANS_PLUS_PLUS, 1000);
    //     $clusterAssignments = $kMeans->cluster($standardizedDataArray);

    //     // Extract the cluster assignments as a flat array
    //     $clusterAssignmentsFlat = array_column($clusterAssignments, 0);

    //     dd($clusterAssignmentsFlat);

    //     // Add the 'c_segment' column to the Collection
    //     $RFMScores->each(function ($item, $index) use ($clusterAssignments) {
    //         $item->c_segment = (int) $clusterAssignments[$index];
    //     });

    //     // dd($RFMScores[0]);

    //     // Update the database with the new cluster segment values
    //     foreach ($RFMScores as $index => $score) {
    //         $clusterIndex = $index;
    //         DB::table('customers')
    //             ->where('r_score', $score['r_score'])
    //             ->where('f_score', $score['f_score'])
    //             ->where('m_score', $score['m_score'])
    //             ->update(['c_segment' => $clusterAssignments[$clusterIndex]]);
    //     }

    //     return redirect()->route('marketingStaff.marketingStaffHome')->with('success', 'Customer segments updated successfully!');
    // }

    public function updateRfmScores()
    {
        // $latestDate = Checkout::max('date');
        $latestDate = Carbon::now();

        $customers = Customer::all();
        // $customers = Customer::where('id', 10000)->get();

        foreach ($customers as $customer) {
            $latestCheckoutDate = CheckoutProduct::join('checkouts', 'checkout_products.checkout_id', '=', 'checkouts.id')
                ->where('checkouts.customer_id', $customer->id)
                ->max('checkouts.date');

            if ($latestCheckoutDate) {
                $recencyScore = $latestDate->diffInDays($latestCheckoutDate) + 1;
            } else {
                // Set a default recency score if no checkout history
                $recencyScore = 365; // Assuming 1 year
            }

            $frequencyScore = $customer->checkouts->count(); // Assuming checkouts relation in Customer model
            // $monetaryScore = DB::table('checkouts')
            //     ->join('checkout_products', 'checkouts.id', '=', 'checkout_products.checkout_id')
            //     ->join('products', 'checkout_products.product_id', '=', 'products.id')
            //     ->where('checkouts.customer_id', $customer->id)
            //     ->sum(DB::raw('products.unit_price * checkout_products.quantity'));

            // Retrieve customer's checkouts with related checkout products and products
            $checkouts = $customer->checkouts()
                ->with(['checkoutProducts.product'])
                ->get();

            // Initialize a variable to store the monetary score
            $monetaryScore = 0;

            // Calculate the monetary score
            foreach ($checkouts as $checkout) {
                foreach ($checkout->checkoutProducts as $checkoutProduct) {
                    $product = $checkoutProduct->product;
                    if ($product && $product->unit_price !== null) {
                        $monetaryScore += ($product->unit_price * $checkoutProduct->quantity);
                    }
                }
            }

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
