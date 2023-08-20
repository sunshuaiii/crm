<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SupportStaffController extends Controller
{
    public function supportStaffHome()
    {
        $supportStaffId = Auth::user()->id;
        $tickets = Ticket::where('support_staff_id', $supportStaffId)->get();

        $totalTickets = $tickets->count();

        // Calculate the breakdown of tickets by query type
        $queryTypes = ['Feedback', 'Complaint', 'Query', 'Issue'];
        $queryTypeCounts = [];
        foreach ($queryTypes as $type) {
            $queryTypeCounts[$type] = $tickets->where('query_type', $type)->count();
        }

        // Calculate the distribution of ticket status
        $ticketStatuses = ['New', 'Open', 'Pending', 'Solved', 'Closed'];
        $ticketStatusCounts = [];
        foreach ($ticketStatuses as $status) {
            $ticketStatusCounts[$status] = $tickets->where('status', $status)->count();
        }

        // Customer Segment Analysis
        $customerSegments = ['Segment A', 'Segment B', 'Segment C', 'Segment D'];
        $segmentTicketCounts = [];
        foreach ($customerSegments as $segment) {
            $segmentTicketCounts[$segment] = Ticket::whereHas('customer', function ($query) use ($segment) {
                $query->where('c_segment', $segment);
            })->where('support_staff_id', $supportStaffId)->count();
        }

        // Response Time Analysis
        $responseTimeData = [];
        foreach ($queryTypes as $queryType) {
            $tickets = Ticket::where('query_type', $queryType)
                ->where('support_staff_id', $supportStaffId)
                ->whereNotNull('response_time')
                ->get();

            $totalResponseTime = 0;
            $validTicketsCount = 0;

            foreach ($tickets as $ticket) {
                $totalResponseTime += $ticket->response_time;
                $validTicketsCount++;
            }

            if ($validTicketsCount > 0) {
                $averageResponseTime = $totalResponseTime / $validTicketsCount;
                $responseTimeData[$queryType] = $averageResponseTime;
            } else {
                $responseTimeData[$queryType] = 0; // No valid tickets
            }
        }

        return view('supportStaff.supportStaffHome', compact(
            'totalTickets',
            'queryTypeCounts',
            'ticketStatusCounts',
            'segmentTicketCounts',
            'customerSegments',
            'responseTimeData',
            'queryTypes',
            'ticketStatuses'
        ));
    }

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
