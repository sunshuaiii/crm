<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerCoupon;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    public function salesPerformanceReport($startDate, $endDate)
    {
        // Initialize an empty array to store the report datasets
        $reportDatasets = [];

        // Section 1: Sales Overview
        $totalRevenue = 0;

        $checkouts = DB::table('checkouts')
            ->join('checkout_products', 'checkouts.id', '=', 'checkout_products.checkout_id')
            ->join('products', 'checkout_products.product_id', '=', 'products.id')
            ->whereBetween('checkouts.date', [$startDate, $endDate])
            ->select(
                'checkouts.id',
                'checkout_products.product_id',
                'checkout_products.quantity',
                'products.unit_price'
            )
            ->get();

        foreach ($checkouts as $checkout) {
            // Calculate revenue for each checkout product
            $revenue = $checkout->unit_price * $checkout->quantity;

            // Add the revenue to the total
            $totalRevenue += $revenue;
        }

        $totalOrders = DB::table('checkouts')
            ->whereBetween('date', [$startDate, $endDate])
            ->count();

        $averageOrderValue = $totalRevenue / max($totalOrders, 1);

        // Section 2: Product Performance
        $bestsellingProducts = $this->getBestsellingProducts($startDate, $endDate);
        $productRevenueContribution = $this->getProductRevenueContribution($startDate, $endDate);
        $productSalesTrends = $this->getProductSalesTrends($startDate, $endDate);

        // Section 3: Customer Analysis
        $highValueCustomers = $this->getTopHighValueCustomers($startDate, $endDate);
        $repeatCustomerRate = $this->calculateRepeatCustomerRate($startDate, $endDate);
        $customerRetentionRate = 1 - $repeatCustomerRate;

        // Section 5: Product Affinity Analysis
        $productAffinityAnalysis = $this->identifyProductAffinity($startDate, $endDate);

        // Section 6: Payment Method Preferences
        $paymentMethodPreferences = $this->analyzePaymentMethodPreferences($startDate, $endDate);

        // Section 7: Redemption of Coupons and Points
        $couponRedemptionRate = $this->calculateCouponRedemptionRate($startDate, $endDate);

        // Section 8: Sales by RFM Segment
        $salesByCSegment = $this->analyzeSalesByCSegment($startDate, $endDate);

        // Section 9: RFM and Product Performance
        $cSegmentProductCorrelation = $this->correlateCSegmentWithProductPerformance($startDate, $endDate);

        // Section 10: RFM and Coupon Redemption
        $cSegmentCouponRedemptionAnalysis = $this->analyzeCSegmentCouponRedemption($startDate, $endDate);

        // Add the datasets to the reportDatasets array
        $reportDatasets['Sales Overview'] = [
            'Total Revenue' => $totalRevenue,
            'Total Orders' => $totalOrders,
            'Average Order Value' => $averageOrderValue,
        ];

        $reportDatasets['Product Performance'] = [
            'Bestselling Products' => $bestsellingProducts,
            'Product Revenue Contribution' => $productRevenueContribution,
            'Product Sales Trends' => $productSalesTrends,
        ];

        $reportDatasets['Customer Analysis'] = [
            'High-Value Customers' => $highValueCustomers,
            'Repeat Customer Rate' => $repeatCustomerRate,
            'Customer Retention Rate' => $customerRetentionRate,
        ];

        $reportDatasets['Product Affinity Analysis'] = $productAffinityAnalysis;

        $reportDatasets['Payment Method Preferences'] = $paymentMethodPreferences;

        $reportDatasets['Redemption of Coupons and Points'] = $couponRedemptionRate;

        $reportDatasets['Sales by Customer Segment'] = $salesByCSegment;

        $reportDatasets['Customer Segment and Product Performance'] = $cSegmentProductCorrelation;

        $reportDatasets['Customer Segment and Coupon Redemption'] = $cSegmentCouponRedemptionAnalysis;

        // Add other sections and datasets as needed

        return $reportDatasets;
    }

    private function getBestsellingProducts($startDate, $endDate)
    {
        // Retrieve the bestselling products within the specified date range
        $bestsellingProducts = DB::table('checkout_products')
            ->select('products.name', DB::raw('SUM(checkout_products.quantity) as total_quantity'))
            ->join('products', 'checkout_products.product_id', '=', 'products.id')
            ->join('checkouts', 'checkout_products.checkout_id', '=', 'checkouts.id')
            ->whereBetween('checkouts.date', [$startDate, $endDate])
            ->groupBy('products.name')
            ->orderByDesc('total_quantity')
            ->limit(10) // Get the top 10 bestselling products
            ->get();

        return $bestsellingProducts;
    }

    private function getProductRevenueContribution($startDate, $endDate)
    {
        // Retrieve product revenue contribution within the specified date range
        $productRevenueContribution = DB::table('checkout_products')
            ->select('products.name', DB::raw('SUM(checkout_products.quantity * products.unit_price) as total_revenue'))
            ->join('products', 'checkout_products.product_id', '=', 'products.id')
            ->join('checkouts', 'checkout_products.checkout_id', '=', 'checkouts.id')
            ->whereBetween('checkouts.date', [$startDate, $endDate])
            ->groupBy('products.name')
            ->orderByDesc('total_revenue')
            ->get();

        return $productRevenueContribution;
    }

    private function getProductSalesTrends($startDate, $endDate)
    {
        // Retrieve product sales trends within the specified date range
        $productSalesTrends = DB::table('checkout_products')
            ->select(
                'products.name',
                DB::raw('DATE_FORMAT(checkouts.date, "%Y-%m-%d") as order_date'),
                DB::raw('SUM(checkout_products.quantity) as total_quantity')
            )
            ->join('products', 'checkout_products.product_id', '=', 'products.id')
            ->join('checkouts', 'checkout_products.checkout_id', '=', 'checkouts.id')
            ->whereBetween('checkouts.date', [$startDate, $endDate])
            ->groupBy('products.name', 'order_date')
            ->orderBy('order_date')
            ->get();

        return $productSalesTrends;
    }

    private function getTopHighValueCustomers($startDate, $endDate)
    {
        // Retrieve customers who made checkouts within the date range
        $customers = Customer::whereHas('checkouts', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        })->get();

        // Initialize an array to store customer total amounts
        $customerTotalAmounts = [];

        foreach ($customers as $customer) {
            // Retrieve customer's checkouts with related checkout products and products
            $checkouts = $customer->checkouts()
                ->with(['checkoutProducts.product'])
                ->get();

            // Initialize a variable to store the monetary score
            $totalAmount = 0;

            // Calculate the monetary score
            foreach ($checkouts as $checkout) {
                foreach ($checkout->checkoutProducts as $checkoutProduct) {
                    $product = $checkoutProduct->product;
                    if ($product && $product->unit_price !== null) {
                        $totalAmount += ($product->unit_price * $checkoutProduct->quantity);
                    }
                }
            }

            // Store the customer's total amount in the array
            $customerTotalAmounts[] = [
                'customer' => $customer,
                'totalAmount' => $totalAmount,
            ];
        }

        // Sort the array in descending order based on total amount
        usort($customerTotalAmounts, function ($a, $b) {
            return $b['totalAmount'] - $a['totalAmount'];
        });

        // Get the top 10 customers with the highest total amounts
        $topHighValueCustomers = array_slice($customerTotalAmounts, 0, 10);

        return $topHighValueCustomers;
    }

    private function calculateRepeatCustomerRate($startDate, $endDate)
    {
        // Calculate the repeat customer rate within the specified date range
        $uniqueCustomerCount = DB::table('checkouts')
            ->select('customer_id')
            ->whereBetween('date', [$startDate, $endDate])
            ->distinct()
            ->count('customer_id');

        $totalCustomerCount = DB::table('customers')
            ->count();

        if ($totalCustomerCount > 0) {
            $repeatCustomerRate = $uniqueCustomerCount / $totalCustomerCount;
        } else {
            $repeatCustomerRate = 0; // Handle the case when there are no customers
        }

        return $repeatCustomerRate;
    }

    private function identifyProductAffinity($startDate, $endDate)
    {
        // Retrieve product affinity data within the specified date range
        $productAffinityAnalysis = DB::table('checkout_products as cp1')
            ->select(
                'p1.name as product_1',
                'p2.name as product_2',
                DB::raw('COUNT(*) as purchase_count')
            )
            ->join('checkout_products as cp2', 'cp1.checkout_id', '=', 'cp2.checkout_id')
            ->join('products as p1', 'cp1.product_id', '=', 'p1.id')
            ->join('products as p2', 'cp2.product_id', '=', 'p2.id')
            ->join('checkouts', 'cp1.checkout_id', '=', 'checkouts.id')
            ->where('cp1.product_id', '<', 'cp2.product_id') // Avoid duplicate combinations
            ->whereBetween('checkouts.date', [$startDate, $endDate]) // Filter by 'created_at' in the 'checkouts' table
            ->groupBy('product_1', 'product_2')
            ->orderByDesc('purchase_count')
            ->limit(10) // Get the top 10 product pairs with the highest purchase count
            ->get();

        return $productAffinityAnalysis;
    }

    private function analyzePaymentMethodPreferences($startDate, $endDate)
    {
        // Retrieve data on payment method preferences within the specified date range
        $paymentMethodPreferences = DB::table('checkouts')
            ->select('payment_method', DB::raw('COUNT(*) as payment_count'))
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->get();

        return $paymentMethodPreferences;
    }

    private function calculateCouponRedemptionRate($startDate, $endDate)
    {
        // Calculate the coupon redemption rate within the specified date range
        $totalCouponsRedeemed = DB::table('customer_coupons')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->where('status', 'Redeemed')
            ->count();

        $totalCouponsClaimed = DB::table('customer_coupons')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        if ($totalCouponsClaimed > 0) {
            $couponRedemptionRate = $totalCouponsRedeemed / $totalCouponsClaimed;
        } else {
            $couponRedemptionRate = 0; // Handle the case when there are no coupons claimed
        }

        return $couponRedemptionRate;
    }

    private function analyzeSalesByCSegment($startDate, $endDate)
    {
        // Retrieve data on sales distribution within the specified date range among different customer segments 
        $salesByCSegment = DB::table('customers')
            ->join('checkouts', 'customers.id', '=', 'checkouts.customer_id')
            ->join('checkout_products', 'checkouts.id', '=', 'checkout_products.checkout_id')
            ->join('products', 'checkout_products.product_id', '=', 'products.id')
            ->whereBetween('checkouts.date', [$startDate, $endDate])
            ->select('customers.c_segment', DB::raw('SUM(products.unit_price * checkout_products.quantity) as total_sales'))
            ->groupBy('customers.c_segment')
            ->get();

        return $salesByCSegment;
    }

    private function correlateCSegmentWithProductPerformance($startDate, $endDate)
    {
        // Correlate customer segment with the performance of specific products sold within the specified date range
        $rfmProductCorrelation = DB::table('customers')
            ->join('checkouts', 'customers.id', '=', 'checkouts.customer_id')
            ->join('checkout_products', 'checkouts.id', '=', 'checkout_products.checkout_id')
            ->join('products', 'checkout_products.product_id', '=', 'products.id')
            ->whereBetween('checkouts.date', [$startDate, $endDate])
            ->select('customers.c_segment', 'products.id as product_id', DB::raw('SUM(products.unit_price * checkout_products.quantity) as total_sales'))
            ->groupBy('customers.c_segment', 'product_id')
            ->get();

        return $rfmProductCorrelation;
    }

    private function analyzeCSegmentCouponRedemption($startDate, $endDate)
    {
        // Analyze coupon redemption rates across customer segments to tailor coupon offers
        $cSegmentCouponRedemptionAnalysis = DB::table('customers')
            ->join('customer_coupons', 'customers.id', '=', 'customer_coupons.customer_id')
            ->whereBetween('customer_coupons.start_date', [$startDate, $endDate])
            ->select('customers.c_segment', DB::raw('SUM(CASE WHEN customer_coupons.status = "Redeemed" THEN 1 ELSE 0 END) as coupons_redeemed'))
            ->groupBy('customers.c_segment')
            ->get();

        return $cSegmentCouponRedemptionAnalysis;
    }
}
