<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Mail;
use Auth;

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
        // Validate the request
        $request->validate([
            'otp' => 'required|numeric'
        ]);
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Verify OTP for the authenticated user
        if ($user->otp == $request->otp) {
            // Clear OTP and log the user in
            $user->update([
                'otp' => null,
            ]);
    
            return redirect()->route('welcome');
        } else {
            return redirect()->back()->with('error', 'Your OTP is incorrect.');
        }
    }
    
}
