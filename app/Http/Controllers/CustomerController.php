<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\SupportStaff;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Milon\Barcode\Facades\DNS1DFacade;

class CustomerController extends Controller
{
    public function profile()
    {
        return view('customer.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->gender = $request->input('gender');
        $user->save();

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully!');
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
}
