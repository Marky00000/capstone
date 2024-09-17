<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Mail;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller
{
    /**
     * Show the application login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|max:60',
        ]);
    
        // Check if the user exists
        $checkUser = User::where('email', $request->email)->first();
    
        if (is_null($checkUser)) {
            return redirect()->back()->with('error', 'Your email address is not associated with us.');
        } else {
            // Generate and update OTP
            $otp = rand(100000, 999999);
            User::where('email', $request->email)->update([
                'otp' => $otp,
            ]);
    
            // Send OTP via email
            Mail::send('emails.loginWithOTPEmail', ['otp' => $otp], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Login with OTP');
            });
    
            return redirect()->route('confirm.login.with.otp')->with('success', 'Check your email inbox/spam folder for login with OTP code.');
        }
    }
    

    /**
     * Handle a logout request.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
    
}
