<?php

namespace App\Http\Controllers;

use App\Auth\CustomPasswordBroker as AuthCustomPasswordBroker;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ValidationException;

class CustomerResetPasswordController extends Controller
{
    // use ResetsPasswords;
    use SendsPasswordResetEmails;

    protected $customPasswordBroker;

    public function __construct(AuthCustomPasswordBroker $customPasswordBroker)
    {
        $this->customPasswordBroker = $customPasswordBroker;
    }

    // The name of the guard to use for customers
    protected $guard = 'customer';

    // The name of the password broker to use for customers
    protected $broker = 'customers';

    // The view for the password reset form
    protected $linkRequestView = 'auth.passwords.email'; // Customize this as needed

    // The view for the password reset confirmation
    protected $resetView = 'auth.passwords.reset'; // Customize this as needed

    // The email subject for the password reset link
    protected $subject = 'Your Password Reset Link';

    /**
     * Show the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view($this->linkRequestView);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // Here, you can customize the logic for sending the reset link email.
        // For example, you can add additional validation or checks.

        $response = $this->customPasswordBroker()->sendTheResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * Show the password reset view for the given token.
     *
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm($token = null)
    {
        return view($this->resetView)->with(['token' => $token, 'email' => request('email')]);
    }

    /**
     * Reset the given customer's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        $credentials = $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );

        // Here, you can customize the logic for resetting the password.
        // For example, you can add additional checks or updates.

        $response = $this->broker()->reset(
            $credentials,
            function ($customer, $password) {
                $this->resetPassword($customer, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        try {
            // Validate the email
            $request->validate([
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    'exists:customers', // Validate that the email exists in the "customers" table
                ],
            ], [
                'email.exists' => 'The provided email address does not exist in our records.',
            ]);

            // If the validation passes, you can continue your logic here

            return true; // Validation passed
        } catch (ValidationException $e) {
            // If the validation fails, you can handle it here
            return false; // Validation failed
        }
    }

    /**
     * Get the response for a failed password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }
}
