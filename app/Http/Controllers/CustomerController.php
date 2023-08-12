<?php

namespace App\Http\Controllers;

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
}
