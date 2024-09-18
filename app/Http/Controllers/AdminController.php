<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\UserCreated;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


class AdminController extends Controller
{
     /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    // Fetch users excluding super_admin and paginate them
    $users = User::where('usertype', '!=', 'super_admin')->paginate(10); // Adjust pagination as needed

    // Pass the users data to the view
    return view('admin.index', compact('users'));
}


    // Display the form for creating a new user
    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate random password
        $password = $request->input('password');

        // Create user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($password),
            'usertype' => 'admin', // Set default usertype to admin
        ]);

        // Send email
        Mail::to($user->email)->send(new UserCreated($user, $password));

        return redirect()->route('admin.index')->with('success', 'User created successfully and email sent.');
    }

}
