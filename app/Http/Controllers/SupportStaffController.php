<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SupportStaff;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SupportStaffController extends Controller
{
    public function supportStaffHome()
    {
        $supportStaffId = Auth::user()->id;
        $allTickets = Ticket::all();
        $tickets = Ticket::where('support_staff_id', $supportStaffId)->get();

        $closedTickets = Ticket::where('support_staff_id', $supportStaffId)->where('status', 'Closed')->count();
        $inProgressTickets = Ticket::where('support_staff_id', $supportStaffId)->where('status', '!=', 'Closed')->count();

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
            $atickets = Ticket::where('query_type', $queryType)
                ->where('support_staff_id', $supportStaffId)
                ->whereNotNull('response_time')
                ->get();

            $totalResponseTime = 0;
            $validTicketsCount = 0;

            foreach ($atickets as $ticket) {
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

        // Resolution Time Analysis
        $resolutionTimeData = [];

        foreach ($queryTypes as $queryType) {
            $btickets = Ticket::where('query_type', $queryType)
                ->where('status', $status)
                ->where('support_staff_id', $supportStaffId)
                ->whereNotNull('resolution_time')
                ->get();

            $totalResolutionTime = 0;
            $validTicketsCount = count($btickets);

            foreach ($btickets as $ticket) {
                $totalResolutionTime += $ticket->resolution_time;
            }

            if ($validTicketsCount > 0) {
                $averageResolutionTime = $totalResolutionTime / $validTicketsCount;
                $resolutionTimeData[$queryType] = $averageResolutionTime;
            } else {
                $resolutionTimeData[$queryType] = 0; // No valid tickets
            }
        }

        // Time-Based Analysis
        foreach ($allTickets as $ticket) {
            $creationTime = $ticket->created_at;
            $hour = $creationTime->hour;
            $dayOfWeek = $creationTime->dayOfWeek;

            if (!isset($ticketCreationDistribution[$hour])) {
                $ticketCreationDistribution[$hour] = 0;
            }

            $ticketCreationDistribution[$hour]++;
        }

        // Ticket Aging Analysis
        $ageIntervalData = []; // Array to store ticket counts for each age interval

        // Define your age intervals (e.g., 0-3 hours, 4-8 hours, etc.)
        $ageIntervals = [
            '0-1 hours' => [],
            '2-3 hours' => [],
            '4-8 hours' => [],
            '9-13 hours' => [],
            '14-18 hours' => [],
            '19-24 hours' => [],
            // Define more age intervals if needed
        ];

        foreach ($tickets as $ticket) {
            $ageInSeconds = now()->diffInSeconds($ticket->created_at);
            $ageInHours = round($ageInSeconds / 3600); // Convert age to hours

            // Assign ticket to an appropriate age interval
            foreach ($ageIntervals as $interval => $range) {
                $rangeParts = explode('-', $interval);
                $minAge = (int) $rangeParts[0];
                $maxAge = (int) $rangeParts[1];

                if ($ageInHours >= $minAge && $ageInHours <= $maxAge) {
                    $ageIntervals[$interval][] = $ticket;
                    break;
                }
            }
        }

        // Populate $ageIntervalData with ticket counts for each age interval
        foreach ($ageIntervals as $interval => $ticketsInInterval) {
            $ageIntervalData[$interval] = count($ticketsInInterval);
        }

        // $ageIntervalData = [];
        // $ageIntervals = [];
        // $maxAge = 0; // To track the longest ticket age

        // // Define the interval boundaries based on your distribution
        // $intervalBoundaries = [1, 3, 5, 8]; // Example boundaries, you can adjust these

        // foreach ($tickets as $ticket) {
        //     if ($ticket->status == 'Open' || $ticket->status == 'Pending') {
        //         $ageInSeconds = now()->diffInSeconds($ticket->created_at);
        //         $ageInHours = round($ageInSeconds / 3600); // Convert age to hours

        //         if ($ageInHours > $maxAge) {
        //             $maxAge = $ageInHours;
        //         }

        //         // Assign ticket to an appropriate interval based on boundaries
        //         $assigned = false;
        //         foreach ($intervalBoundaries as $index => $boundary) {
        //             if ($ageInHours <= $boundary) {
        //                 $ageIntervals[] = ($index === 0 ? "0-{$boundary} hours" : ($boundary + 1) . "-{$boundary} hours");
        //                 $ageIntervalData[] = 0;
        //                 $assigned = true;
        //                 break;
        //             }
        //         }

        //         if (!$assigned) {
        //             $ageIntervals[] = ">{$intervalBoundaries[count($intervalBoundaries) - 1]} hours";
        //             $ageIntervalData[] = 0;
        //         }
        //     }
        // }

        // foreach ($tickets as $ticket) {
        //     if ($ticket->status == 'Open' || $ticket->status == 'Pending') {
        //         $ageInSeconds = now()->diffInSeconds($ticket->created_at);
        //         $ageInHours = round($ageInSeconds / 3600); // Convert age to hours

        //         // Increment the count of the appropriate interval
        //         foreach ($intervalBoundaries as $index => $boundary) {
        //             if ($ageInHours <= $boundary) {
        //                 $ageIntervalData[$index]++;
        //                 break;
        //             }
        //         }

        //         if ($ageInHours > $intervalBoundaries[count($intervalBoundaries) - 1]) {
        //             $ageIntervalData[count($ageIntervalData) - 1]++;
        //         }
        //     }
        // }



        // Closed rate analysis
        // Get closed and total ticket counts for each query type
        $closedRateData = [];
        foreach ($queryTypes as $queryType) {
            $closedTicketsCount = Ticket::where('support_staff_id', $supportStaffId)
                ->where('query_type', $queryType)
                ->where('status', 'Closed')
                ->count();

            $totalTicketsCount = Ticket::where('support_staff_id', $supportStaffId)
                ->where('query_type', $queryType)
                ->count();

            // Calculate closed rate (percentage)
            if ($totalTicketsCount > 0) {
                $closedRate = ($closedTicketsCount / $totalTicketsCount) * 100;
            } else {
                $closedRate = 0; // Avoid division by zero
            }

            $closedRateData[$queryType] = $closedRate;
        }

        return view('supportStaff.supportStaffHome', compact(
            'closedTickets',
            'inProgressTickets',
            'totalTickets',
            'queryTypeCounts',
            'ticketStatusCounts',
            'segmentTicketCounts',
            'customerSegments',
            'responseTimeData',
            'queryTypes',
            'ticketStatuses',
            'resolutionTimeData',
            'ticketCreationDistribution',
            'ageIntervalData',
            'closedRateData'
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

        // update response time or resolution time
        if ($oldStatus == 'New' && $newStatus != 'New') {
            $ticket->response_time = now()->diffInSeconds($ticket->created_at);
        }
        if ($oldStatus != 'Closed' && $newStatus == 'Closed') {
            $ticket->resolution_time = now()->diffInSeconds($ticket->created_at);
        }

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
