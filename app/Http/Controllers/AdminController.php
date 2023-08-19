<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getAllCoupons()
    {
        $coupons = Coupon::all(); // Retrieve all coupons from the database
        return view('admin.couponManagement', compact('coupons'));
    }

    public function addCoupon()
    {
        return view('admin.addCoupon');
    }

    public function storeCoupon(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'discount' => 'required|numeric',
                'redemption_points' => 'required|numeric',
                'conditions' => 'required',
            ]);

            Coupon::create([
                'name' => $request->name,
                'discount' => $request->discount,
                'redemption_points' => $request->redemption_points,
                'conditions' => $request->conditions,
            ]);

            return redirect()->route('admin.couponManagement')->with('success', 'Coupon added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the coupon. Please try again.');
        }
    }

    public function editCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.editCoupon', compact('coupon'));
    }

    public function updateCoupon(Request $request, $id)
    {
        try {
            $coupon = Coupon::findOrFail($id);

            $request->validate([
                'name' => 'required|unique:coupons,name,' . $id,
                'discount' => 'required|numeric',
                'redemption_points' => 'required|numeric',
            ]);

            $coupon->update([
                'name' => $request->name,
                'discount' => $request->discount,
                'redemption_points' => $request->redemption_points,
                'conditions' => $request->conditions,
            ]);

            return redirect()->route('admin.couponManagement')->with('success', 'Coupon updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the coupon. Please try again.');
        }
    }
}
