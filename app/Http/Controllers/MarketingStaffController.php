<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\CheckoutProduct;
use App\Models\Customer;
use App\Models\CustomerCoupon;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Sentiment\Analyzer;

class MarketingStaffController extends Controller
{
    public function reportGeneration()
    {
        $data = '';

        return view('marketingStaff.reportGeneration', compact(
            'data',
        ));
    }

    public function customerInsights()
    {
        $customerDistribution = $this->calculateCustomerDistribution();
        $customerGrowth = $this->calculateCustomerGrowth();
        $churnData = $this->calculateCustomerChurn();
        $couponRedemptionData = $this->calculateCouponRedemption();
        $interactionData = $this->calculateCustomerServiceInteraction();
        $topCustomers = $this->getTopCustomers(10); //get top 10 purchase customers
        $cltvData = $this->calculateCLTV();

        return view('marketingStaff.customerInsights', compact(
            'customerDistribution',
            'customerGrowth',
            'churnData',
            'couponRedemptionData',
            'interactionData',
            'topCustomers',
            'cltvData',
        ));
    }

    public function leadInsights()
    {
        $activityCounts = $this->leadActivityAnalysis();
        $$sentimentCounts = $this->feedbackSentimentAnalysis();

        return view('marketingStaff.leadInsights', compact(
            'activityCounts',
            '$sentimentCounts',
        ));
    }

    private function feedbackSentimentAnalysis()
    {
        $feedbackData = DB::table('leads')
            ->select('feedback')
            ->whereNotNull('feedback')
            ->get();

        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount = 0;

        $analyzer = new Analyzer();

        foreach ($feedbackData as $feedbackEntry) {
            $feedbackText = $feedbackEntry->feedback;

            // Use VADER Sentiment Analysis
            $sentiment = $analyzer->getSentiment($feedbackText);

            // Determine sentiment
            if ($sentiment['compound'] >= 0.05) {
                $positiveCount++;
            } elseif ($sentiment['compound'] <= -0.05) {
                $negativeCount++;
            } else {
                $neutralCount++;
            }
        }

        $sentimentCounts = [
            'Positive' => $positiveCount,
            'Negative' => $negativeCount,
            'Neutral' => $neutralCount,
        ];

        return $sentimentCounts;
    }

    private function leadActivityAnalysis()
    {
        $activityData = DB::table('leads')
            // ->where('marketing_staff_id', Auth::user()->id)
            ->select('activity')
            ->get();

        $activityCounts = [];

        foreach ($activityData as $activityRecord) {
            $activityTypes = explode(', ', $activityRecord->activity);
            foreach ($activityTypes as $type) {
                if (!empty($type)) {
                    if (!isset($activityCounts[$type])) {
                        $activityCounts[$type] = 1;
                    } else {
                        $activityCounts[$type]++;
                    }
                }
            }
        }

        return $activityCounts;
    }

    public function productInsights()
    {
        // Product Insights
        $products = Product::all();
        $salesData = $this->calculateProductSales($products);

        return view('marketingStaff.productInsights', compact(
            'products',
            'salesData',
        ));
    }

    public function marketingStaffHome()
    {
        return view('marketingStaff.marketingStaffHome');
    }

    private function calculateCustomerDistribution()
    {
        $customerData = $this->getCustomerDistributionData();

        // Retrieve quantile values from the database
        $quantiles = $this->getQuantileValues();

        $rfmScores = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]; // Assuming these are the column names for RFM scores
        $customerSegments = ['Silver', 'Gold', 'Platinum', 'NA']; // Sample customer segments

        $customerDistribution = [];
        foreach ($rfmScores as $rfmScore) {
            foreach ($customerSegments as $segment) {
                $customerDistribution[$rfmScore][$segment] = 0;
            }
        }

        foreach ($customerData as $customer) {
            $rfmScore = $this->calculateRFMScore($customer->r_score, $customer->f_score, $customer->m_score, $quantiles);
            $segment = $customer->c_segment;
            $customerDistribution[$rfmScore][$segment]++;
        }

        return $customerDistribution;
    }

    private function getCustomerDistributionData()
    {
        // Replace this with your actual data retrieval logic
        return DB::table('customers')
            ->select('r_score', 'f_score', 'm_score', 'c_segment')
            ->get();
    }

    private function getQuantileValues()
    {
        $customers = DB::table('customers')
            ->select('r_score', 'f_score', 'm_score')
            ->get();

        $quantiles = [
            'r_score' => [],
            'f_score' => [],
            'm_score' => [],
        ];

        foreach (['r_score', 'f_score', 'm_score'] as $scoreType) {
            $sortedScores = $customers->pluck($scoreType)->sort()->values();
            $quantiles[$scoreType][0.25] = $sortedScores[ceil($sortedScores->count() * 0.25) - 1];
            $quantiles[$scoreType][0.50] = $sortedScores[ceil($sortedScores->count() * 0.50) - 1];
            $quantiles[$scoreType][0.75] = $sortedScores[ceil($sortedScores->count() * 0.75) - 1];
        }

        return $quantiles;
    }

    private function calculateRFMScore($r, $f, $m, $quantiles)
    {
        $rScore = $this->RScoring($r, 'r_score', $quantiles);
        $fScore = $this->FnMScoring($f, 'f_score', $quantiles);
        $mScore = $this->FnMScoring($m, 'm_score', $quantiles);

        // Combine the scores to get the RFM score
        $rfmScore = $rScore + $fScore + $mScore;

        return $rfmScore;
    }

    private function RScoring($x, $p, $d)
    {
        if ($x <= $d[$p][0.25]) {
            return 1;
        } elseif ($x <= $d[$p][0.50]) {
            return 2;
        } elseif ($x <= $d[$p][0.75]) {
            return 3;
        } else {
            return 4;
        }
    }

    private function FnMScoring($x, $p, $d)
    {
        if ($x <= $d[$p][0.25]) {
            return 4;
        } elseif ($x <= $d[$p][0.50]) {
            return 3;
        } elseif ($x <= $d[$p][0.75]) {
            return 2;
        } else {
            return 1;
        }
    }

    private function calculateCustomerGrowth()
    {
        $customerGrowth = [];
        $oldestDate = Customer::oldest('created_at')->value('created_at');
        $startDate = Carbon::parse($oldestDate); // Use the oldest created_at date
        $endDate = Carbon::now();

        while ($startDate <= $endDate) {
            $quarterEndDate = $startDate->copy()->addMonths(3)->endOfDay();
            $newCustomersCount = Customer::whereBetween('created_at', [$startDate, $quarterEndDate])->count();

            $customerGrowth[] = [
                'date' => $startDate->format('Y-m-d'),
                'new_customers' => $newCustomersCount,
            ];

            $startDate->addMonths(3); // Set interval as a quarter of a year
        }

        return $customerGrowth;
    }

    private function calculateCustomerChurn()
    {
        $churnData = [];
        $startDate = Carbon::now()->subMonths(12); // Change the start date to 1 year ago
        $endDate = Carbon::now();

        $activeCustomers = DB::table('customers')
            ->select('id')
            ->whereIn('id', function ($query) use ($startDate, $endDate) {
                $query->select('customer_id')
                    ->from('checkouts')
                    ->whereBetween('date', [$startDate, $endDate]);
            })
            ->get()
            ->pluck('id');

        $churnCustomersCount = DB::table('customers')
            ->whereNotIn('id', $activeCustomers)
            ->count();

        $activeCustomersCount = $activeCustomers->count();

        $churnData['churned'] = $churnCustomersCount;
        $churnData['active'] = $activeCustomersCount;

        return $churnData;
    }

    private function calculateCouponRedemption()
    {
        $distributedCouponsCount = CustomerCoupon::count(); // Count of all distributed coupons
        $redeemedCouponsCount = CustomerCoupon::where('status', 'Redeemed')->count(); // Count of redeemed coupons

        $couponRedemptionData = [
            'distributed' => $distributedCouponsCount,
            'redeemed' => $redeemedCouponsCount,
        ];

        return $couponRedemptionData;
    }

    private function calculateCustomerServiceInteraction()
    {
        $interactionTypes = Ticket::pluck('query_type')->unique();
        $interactionCounts = [];

        foreach ($interactionTypes as $type) {
            $interactionCounts[$type] = Ticket::where('query_type', $type)->count();
        }

        return $interactionCounts;
    }

    private function getTopCustomers($numbersOfTopCustomer)
    {
        $topCustomers = DB::table('customers')
            ->select('id', 'first_name', 'last_name', 'm_score as total_purchase_amount')
            ->orderByDesc('total_purchase_amount')
            ->take($numbersOfTopCustomer)
            ->get();

        return $topCustomers;
    }

    private function calculateCLTV()
    {
        // Assuming you have customer segments defined in the customers table
        $customerSegments = ['Silver', 'Gold', 'Platinum'];

        $cltvData = [];

        foreach ($customerSegments as $segment) {
            $averagePurchaseAmount = Customer::where('c_segment', $segment)
                ->avg('m_score');

            $averagePurchaseFrequency = Customer::where('c_segment', $segment)
                ->avg('f_score');

            $averageCustomerLifespan = Customer::where('c_segment', $segment)
                ->with(['checkouts' => function ($query) {
                    $query->orderBy('date', 'asc');
                }])
                ->get()
                ->map(function ($customer) {
                    if ($customer->checkouts->isEmpty()) {
                        return 0;
                    }

                    $firstPurchaseDate = $customer->checkouts->first()->date;
                    $lastPurchaseDate = $customer->checkouts->last()->date;

                    return $lastPurchaseDate->diffInDays($firstPurchaseDate);
                })
                ->avg(); // Calculate average lifespan in days

            // Calculate CLTV using a formula that makes sense for your business
            $cltv = $averagePurchaseAmount * $averagePurchaseFrequency * $averageCustomerLifespan;

            $cltvData[$segment] = $cltv;
        }

        return $cltvData;
    }

    private function calculateProductSales($products)
    {
        $salesData = [];

        $oldestDate = Checkout::orderBy('date')->limit(1)->value('date');
        $startDate = Carbon::parse($oldestDate)->subMonths(6);
        $endDate = Carbon::now();

        $chunkSize = 1000; // Number of records to process in each chunk
        $totalProducts = count($products);

        for ($offset = 0; $offset < $totalProducts; $offset += $chunkSize) {
            $productIds = $products->pluck('id')->slice($offset, $chunkSize);

            $productQuantities = CheckoutProduct::whereIn('product_id', $productIds)
                ->join('checkouts', 'checkout_products.checkout_id', '=', 'checkouts.id')
                ->whereBetween('checkouts.date', [$startDate, $endDate])
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity_sold'))
                ->groupBy('product_id')
                ->orderByDesc('total_quantity_sold') // Order by total_quantity_sold in descending order
                ->limit(10) // Limit to top 10 products
                ->get();

            foreach ($productQuantities as $productQuantity) {
                $product = $products->firstWhere('id', $productQuantity->product_id);
                if ($product) {
                    $salesData[$product->id] = [
                        'product_name' => $product->name,
                        'total_quantity_sold' => $productQuantity->total_quantity_sold,
                    ];
                }
            }
        }

        return $salesData;
    }

    public function updateCSegment()
    {
        try {
            $projectPath = base_path(); // Root path of the Laravel project
            $pythonScriptPath = $projectPath . '/kmeans_script.py'; // Path to the Python script

            $command = "python {$pythonScriptPath}";

            $output = shell_exec($command);

            if (!empty($output)) {
                return redirect()->back()->with('success', 'Customer segment updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Script executed, but no output received.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while executing the script: ' . $e->getMessage());
        }
    }

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

            if ($request->has('feedback')) {
                Lead::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'contact' => $request->contact,
                    'email' => $request->email,
                    'gender' => $request->gender,
                    'status' => 'New',
                    'activity' => $request->activity_string,
                    'feedback' => $request->feedback_string,
                    'marketing_staff_id' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // Get the ID of the newly created lead
                $newLeadId = Lead::orderBy('id', 'desc')->first()->id;

                // Update activity_date and feedback_date for the newly created lead
                Lead::where('id', $newLeadId)->update([
                    'activity_date' => Carbon::now(),
                    'feedback_date' => Carbon::now(),
                ]);
            } else {
                Lead::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'contact' => $request->contact,
                    'email' => $request->email,
                    'gender' => $request->gender,
                    'status' => 'New',
                    'activity' => $request->activity_string,
                    'marketing_staff_id' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // Get the ID of the newly created lead
                $newLeadId = Lead::orderBy('id', 'desc')->first()->id;

                // Update activity_date and feedback_date for the newly created lead
                Lead::where('id', $newLeadId)->update([
                    'activity_date' => Carbon::now(),
                ]);
            }

            return redirect()->route('marketingStaff.leadManagement')->with('success', 'Lead added successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1]; // Get the error code

            if ($errorCode === 1062) {
                return redirect()->back()->with('error', 'A lead with the same email already exists.');
            } else {
                return redirect()->back()->with('error', 'An error occurred while adding the lead. Please try again.');
            }
        }
    }

    public function updateLead($id)
    {
        $lead = Lead::findOrFail($id);
        return view('marketingStaff.updateLeadDetails', ['lead' => $lead]);
    }

    public function storeUpdatedLead(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $activityString = '';
        $feedbackString = '';
        $activityDate = $lead->activity_date;
        $feedbackDate = $lead->feedback_date;
        if ($request->input('activity')) {
            $activityString = implode(', ', $request->input('activity'));
            $activityDate = Carbon::now();
        }
        if ($request->input('feedback')) {
            $feedbackString = implode(', ', $request->input('feedback'));
            $feedbackDate = Carbon::now();
        }

        // Update the lead details
        $lead->first_name = $request->input('first_name');
        $lead->last_name = $request->input('last_name');
        $lead->contact = $request->input('contact');
        $lead->email = $request->input('email');
        $lead->gender = $request->input('gender');
        $lead->activity = $activityString;
        $lead->feedback = $feedbackString;
        $lead->activity_date = $activityDate;
        $lead->feedback_date = $feedbackDate;

        // Save the updated lead
        $lead->save();

        return redirect()->route('marketingStaff.leadManagement', ['id' => $lead->id])
            ->with('success', 'Lead details updated successfully.');
    }
}
