<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:customer')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:marketingStaff')->except('logout');
        $this->middleware('guest:supportStaff')->except('logout');
    }

    public function showCustomerLoginForm()
    {
        return view('auth.login', ['url' => 'customer']);
    }

    public function customerLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
    
        // Check if a user with the provided email exists
        $user = Customer::where('email', $request->email)->first();
    
        if (!$user) {
            // User with the provided email does not exist
            return back()->withInput($request->only('email'))->withErrors(['email' => 'Email is not registered.']);
        }
    
        if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {
            // Login successful
            return redirect()->intended('/customer')->with('success', 'Login successful.');
        }
    
        // Login failed
        return back()->withInput($request->only('email'))->withErrors(['login' => 'Login failed.']);
    }    

    public function showAdminLoginForm()
    {
        return view('auth.login', ['url' => 'admin']);
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/admin')->with('success', 'Login successful.');
        }
        return back()->withInput($request->only('email'))->withErrors(['login' => 'Login failed.']);
    }

    public function showMarketingStaffLoginForm()
    {
        return view('auth.login', ['url' => 'marketingStaff']);
    }

    public function marketingStaffLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        if (Auth::guard('marketingStaff')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/marketingStaff')->with('success', 'Login successful.');
        }
        return back()->withInput($request->only('email'))->withErrors(['login' => 'Login failed.']);
    }

    public function showSupportStaffLoginForm()
    {
        return view('auth.login', ['url' => 'supportStaff']);
    }

    public function supportStaffLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        if (Auth::guard('supportStaff')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/supportStaff')->with('success', 'Login successful.');
        }
        return back()->withInput($request->only('email'))->withErrors(['login' => 'Login failed.']);
    }

    public function logout(Request $request)
    {
        // Invalidate regular session
        Auth::logout();
        $request->session()->invalidate(); //  invalidate the user's session
        $request->session()->regenerateToken(); // regenerate their CSRF token
        // session(['role' => 'guest']);
        // session()->forget('role');
        return redirect()->intended('/');
    }
}
