<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mail;
use Illuminate\Support\Facades\Auth;

class OTPController extends Controller
{
    public function loginwithotppost(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|max:60'
        ]);

        // Check if the user exists
        $checkUser = User::where('email', $request->email)->first();
        if (is_null($checkUser)) {
            return redirect()->back()->with('error', 'Your email address is not associated with us.');
        } else {
            // Generate and update OTP
            $otp = rand(100000, 999999);
            $userUpdate = User::where('email', $request->email)->update([
                'otp' => $otp
            ]);

            // Send OTP via email
            Mail::send('emails.loginWithOTPEmail', ['otp' => $otp], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Login with OTP - markejano');
            });

            return redirect()->route('confirm.login.with.otp')->with('success', 'Check your email inbox/spam folder for login with OTP code.');
        }
    }

    public function confirmloginwithotppost(Request $request)
{
    // Validate the OTP array
    $request->validate([
        'otp' => 'required|array|size:6',
        'otp.*' => 'numeric|digits:1',
    ]);

    // Concatenate OTP input fields
    $otp = implode('', $request->input('otp'));

    // Verify OTP
    $checkUser = User::where('otp', $otp)->first();
    
    if (is_null($checkUser)) {
        return redirect()->back()->with('error', 'The OTP you provided is incorrect.');
    } else {
        // Clear OTP and log the user in
        User::where('otp', $otp)->update([
            'otp' => null,
        ]);

        Auth::login($checkUser);

        // Route based on user role
        if ($checkUser->usertype === 'admin' || $checkUser->usertype === 'super_admin') {
            return redirect()->route('dashboard'); // Adjust the route name if needed
        }

        return redirect()->route('welcome');
    }
}

    
}
