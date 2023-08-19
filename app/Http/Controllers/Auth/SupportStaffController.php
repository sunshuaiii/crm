<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class SupportStaffController extends Controller
{


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
