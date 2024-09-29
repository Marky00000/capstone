<?php

// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    // Method to show all payments
    public function index()
    {
        // Fetch all payments
        $payments = Payment::all();

        // Return the view for payments, passing the payments data
        return view('payment.index', compact('payments'));
    }
    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        return view('payment.initial', compact('project'));
    }

    public function storeInitial(Request $request)
    {
        // Validate the incoming request data for the initial payment
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'payment_method' => 'required|in:cash,gcash,bank_transfer',
            'payment_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_amount' => 'required|numeric|min:0', // Validate the payment amount
        ]);
    
        // Handle the image upload
        $imagePath = $request->file('payment_image')->store('payments', 'public');
    
        // Find the project
        $project = Project::findOrFail($request->project_id);
    
        // Create a new payment record
        $payment = Payment::create([
            'project_id' => $request->project_id,
            'payment_type' => 'initial',
            'payment_image' => $imagePath,
            'payment_method' => $request->payment_method,
            'amount' => $request->payment_amount, // Store the payment amount
            'remarks' => $request->remarks, // Add remarks if needed
        ]);
    
        // Calculate the total paid amount for all payments of this project
        $totalPaid = Payment::where('project_id', $request->project_id)->sum('amount');
    
        // Update the total_paid field in the project
        $project->total_paid = $totalPaid;
        
        // Save the updated project
        $project->save();
    
        // Return a JSON response
        return response()->json([
            'success' => true,
            'redirect' => route('payments.index'),
        ]);
    }
    
    
 



public function show($id)
{
    $payment = Payment::findOrFail($id); // Fetch the payment record by ID

    // Fetch total paid amount for the specific project

    return view('payment.show', compact('payment')); // Pass payment and totalPaid to the view
}

    

public function viewPayments($projectId)
{
    $project = Project::findOrFail($projectId); // Fetch the project
    $payments = Payment::where('project_id', $projectId)->get(); // Fetch payments for the project

    // Calculate total paid amount for the project
    $totalPaid = Payment::where('project_id', $projectId)->sum('amount'); // Sum the 'amount' for the project

    // Pass total paid to the view correctly
    return view('payment.view', compact('project', 'payments', 'totalPaid')); // Pass the correct variable name
}
}
