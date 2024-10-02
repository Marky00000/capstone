<?php

// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Support\Facades\Auth; // Import Auth to get the logged-in user
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    // Method to show all payments for the logged-in user
    public function index()
    {
        // Fetch only payments that belong to the currently logged-in user
        $payments = Payment::where('id', Auth::id())->get();

        // Return the view for payments, passing the filtered payments data
        return view('payment.index', compact('payments'));
    }

    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        return view('payment.initial', compact('project'));
    }


    public function adminIndex(Request $request)
    {
        $query = Payment::query();
    
        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }
    
        // Apply date filters if present
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('created_at', '>=', $request->start_date);
        }
    
        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('created_at', '<=', $request->end_date);
        }
    
        // Default sorting is latest to oldest
        $query->orderBy('created_at', 'desc'); // Adjust this as needed for default behavior.
    
        $payments = $query->paginate(10); // Adjust pagination as needed
    
        return view('payment.adminIndex', compact('payments'));
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
    
        // Determine the payment type based on project cost
        if ($request->payment_amount <= ($project->total_cost * 0.50)) {
            $paymentType = 'initial';
        } elseif ($request->payment_amount > ($project->total_cost * 0.50) && $request->payment_amount <= ($project->total_cost * 0.80)) {
            $paymentType = 'midterm';
        } else {
            $paymentType = 'final';
        }
    
        // Create a new payment record
        $payment = Payment::create([
            'project_id' => $request->project_id,
            'payment_type' => $paymentType,
            'payment_image' => $imagePath,
            'payment_method' => $request->payment_method,
            'amount' => $request->payment_amount, // Store the payment amount
            'remarks' => $request->remarks, // Add remarks if needed
        ]);
    
        // Return a JSON response
        return response()->json([
            'success' => true,
            'redirect' => route('payments.index'),
        ]);
    }
    
    public function edit($id)
    {
        // Retrieve the payment by its ID
        $payment = Payment::findOrFail($id);
    
        // Get the associated project using the relationship
        $project = $payment->project; // Assuming you have a 'project' relationship in Payment model
    
        // Pass both payment and project data to the view
        return view('payment.adminEdit', compact('payment', 'project'));
    }
    
    public function update(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'payment_method' => 'required|string',
        'amount' => 'required|numeric|min:0',
    ]);

    // Find the payment record
    $payment = Payment::findOrFail($id);

    // Update payment details
    $payment->payment_method = $request->input('payment_method');
    $payment->amount = $request->input('amount');
    $payment->payment_status = 'approve'; // Set status to approved
    $payment->save();

    // Update total_paid in the project
    $project = Project::findOrFail($payment->project_id);
    $totalPayments = Payment::where('project_id', $project->id)
        ->where('payment_status', 'approve') // Only sum approved payments
        ->sum('amount');

    // Update the project's total_paid
    $project->total_paid = $totalPayments;
    $project->project_status = 'active';
    $project->save();

    return redirect()->route('admin.payments.index')->with('success', 'Payment approved and total paid updated successfully.');
}
    
 

public function showPaymentForm($id)
{
    $project = Project::findOrFail($id);
    return view('your.view.name', compact('project'));
}


public function show($id)
{
    $payment = Payment::findOrFail($id); // Fetch the payment record by ID

    // Fetch total paid amount for the specific project

    return view('payment.show', compact('payment')); // Pass payment and totalPaid to the view
}

public function adminshow($id)
{
    $payment = Payment::findOrFail($id); // Fetch the payment record by ID

    // Fetch total paid amount for the specific project

    return view('payment.adminShow', compact('payment')); // Pass payment and totalPaid to the view
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


public function adminviewPayments($projectId)
{
    $project = Project::findOrFail($projectId); // Fetch the project
    $payments = Payment::where('project_id', $projectId)->get(); // Fetch payments for the project

    // Calculate total paid amount for the project
    $totalPaid = Payment::where('project_id', $projectId)->sum('amount'); // Sum the 'amount' for the project

    // Pass total paid to the view correctly
    return view('payment.view', compact('project', 'payments', 'totalPaid')); // Pass the correct variable name
}

  // Approve a payment
public function approve($id)
{
    // Fetch the payment and ensure it exists
    $payment = Payment::findOrFail($id);
    
    // Change the payment status to 'approve'
    $payment->payment_status = 'approve';
    
    // Update the total_paid in the project
    $project = Project::findOrFail($payment->project_id);
    $project->project_status = 'active'; // Increment total_paid by the payment amount

    // Add the current payment amount to the project's total_paid
    $project->total_paid += $payment->amount; // Increment total_paid by the payment amount
    $project->save(); // Save the updated project

    // Save the approved payment
    $payment->save();

    return redirect()->route('admin.payments.index')->with('success', 'Payment approved successfully!');
}


  // Decline a payment
  public function decline($id)
  {
      $payment = Payment::findOrFail($id);
      $payment->payment_status = 'decline';
      $payment->save();

      return redirect()->route('admin.payments.index')->with('success', 'Payment has been Declined!');
  }


}
