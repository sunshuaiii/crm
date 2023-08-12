<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\MarketingStaff;
use App\Models\SupportStaff;
use App\Rules\AboveEighteen;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
        $this->middleware('guest:customer');
        $this->middleware('guest:admin');
        $this->middleware('guest:marketingStaff');
        $this->middleware('guest:supportStaff');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCustomerRegisterForm()
    {
        return view('auth.register', ['url' => 'customer']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAdminRegisterForm()
    {
        return view('auth.register', ['url' => 'admin']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showMarketingStaffRegisterForm()
    {
        return view('auth.register', ['url' => 'marketingStaff']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSupportStaffRegisterForm()
    {
        return view('auth.register', ['url' => 'supportStaff']);
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function createCustomer(Request $request)
    {
        try {
            // Validate the incoming data
            $this->validator($request->all())->validate();

            $this->validate($request, [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'contact' => 'required|string|max:255',
                'gender' => 'required|string|in:Male,Female,',
                'dob' => ['required', 'date', new AboveEighteen],
            ]);

            // Attempt to create a new customer
            Customer::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'contact' => $request->contact,
                'gender' => $request->gender,
                'dob' => $request->dob,
            ]);

            // Add a success message to the session
            return redirect()->intended('login/customer')->with('success', 'Registration successful! You can now log in.');
        } catch (QueryException $e) {
            // Handle database integrity constraint violation
            if ($e->getCode() == 23000) { // Integrity constraint violation error code
                $errorMessage = "The email address is already registered.";
                return redirect()->back()->withInput()->withErrors([$errorMessage]);
            }
            // If the exception is not due to a duplicate email, re-throw it
            throw $e;
        } catch (ValidationException $e) {
            // If validation fails, redirect back with validation errors
            return redirect()->back()->withInput()->withErrors($e->errors());
        }
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function createAdmin(Request $request)
    {
        try {
            $this->validator($request->all())->validate();
            Admin::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return redirect()->intended('login/admin');
        } catch (QueryException $e) {
            // Handle database integrity constraint violation
            if ($e->getCode() == 23000) { // Integrity constraint violation error code
                $errorMessage = "The email address is already registered.";
                return redirect()->back()->withInput()->withErrors([$errorMessage]);
            }
            // If the exception is not due to a duplicate email, re-throw it
            throw $e;
        } catch (ValidationException $e) {
            // If validation fails, redirect back with validation errors
            return redirect()->back()->withInput()->withErrors($e->errors());
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function createMarketingStaff(Request $request)
    {
        try {
            $this->validator($request->all())->validate();
            MarketingStaff::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return redirect()->intended('login/marketingStaff');
        } catch (QueryException $e) {
            // Handle database integrity constraint violation
            if ($e->getCode() == 23000) { // Integrity constraint violation error code
                $errorMessage = "The email address is already registered.";
                return redirect()->back()->withInput()->withErrors([$errorMessage]);
            }
            // If the exception is not due to a duplicate email, re-throw it
            throw $e;
        } catch (ValidationException $e) {
            // If validation fails, redirect back with validation errors
            return redirect()->back()->withInput()->withErrors($e->errors());
        }
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function createSupportStaff(Request $request)
    {
        try {
            $this->validator($request->all())->validate();
            SupportStaff::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return redirect()->intended('login/supportStaff');
        } catch (QueryException $e) {
            // Handle database integrity constraint violation
            if ($e->getCode() == 23000) { // Integrity constraint violation error code
                $errorMessage = "The email address is already registered.";
                return redirect()->back()->withInput()->withErrors([$errorMessage]);
            }
            // If the exception is not due to a duplicate email, re-throw it
            throw $e;
        } catch (ValidationException $e) {
            // If validation fails, redirect back with validation errors
            return redirect()->back()->withInput()->withErrors($e->errors());
        }
    }
}
