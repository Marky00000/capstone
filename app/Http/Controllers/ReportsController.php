<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Payment;

class ReportsController extends Controller
{
    public function projects()
    {
        // Logic to retrieve project reports
        return view('reports.projects'); // Make sure to create this view file
    }

    public function rates(Request $request)
    {
        // Start a query on the Payment model
        $query = Payment::query();
    
        // Initialize a variable for total revenue
        $totalRevenue = 0;
    
        // Apply date filters if provided
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }
    
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }
    
        // Get all payments based on the filters
        $payments = $query->paginate(8); // Adjust the number as needed for pagination
    
        // Calculate total revenue based on current filters
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $totalRevenue = $query->sum('amount'); // Total revenue of filtered payments
        } else {
            // Calculate total revenue from all payments when no filters are applied
            $totalRevenue = Payment::sum('amount');
        }
    
        return view('reports.rates', compact('payments', 'totalRevenue')); // Return the view with payments data and total revenue
    }
    
    

    
    
}
