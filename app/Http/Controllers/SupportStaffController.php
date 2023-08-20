<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportStaffController extends Controller
{

    public function getAllTicketsForSupportStaff()
    {
        // Retrieve all tickets where support_staff_id matches the logged-in support staff's ID
        $tickets = Ticket::where('support_staff_id', Auth::user()->id)
            ->where('query_type', '!=', 'Closed') // Exclude tickets with query_type "Closed"
            ->orderBy('query_type')
            ->get();

        if ($tickets->isEmpty()) {
            return view('supportStaff.customerService')->with('error', 'No tickets found.');
        }

        // Group tickets by query type
        $groupedTickets = $tickets->groupBy('query_type');

        return view('supportStaff.customerService', compact('groupedTickets', 'tickets'));
    }

    public function viewTicketDetails($id)
    {
        // Retrieve the ticket by ID
        $ticket = Ticket::findOrFail($id);

        // Check if the ticket belongs to the logged-in support staff
        if ($ticket->support_staff_id !== Auth::user()->id) {
            return redirect()->route('supportStaff.customerService')->with('error', 'Access denied.');
        }

        return view('supportStaff.ticketDetails', compact('ticket'));
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
        $customer = Customer::findOrFail($id);

        if (!$customer) {
            return redirect()->route('supportStaff.customerService')->with('error', 'Customer not found.');
        }

        return view('customerDetails', compact('customer'));
    }

    public function updateTicketStatus(Request $request)
    {
        $ticketId = $request->input('ticket_id');
        $newStatus = $request->input('new_status');

        $ticket = Ticket::findOrFail($ticketId);
        $oldStatus = $ticket->status; // Get the current status before updating
        $ticket->status = $newStatus;
        $ticket->save();

        return response()->json([
            'updatedTicket' => [
                'id' => $ticket->id,
                'oldStatus' => $oldStatus,
                'newStatus' => $newStatus,
            ],
        ]);
    }
}
