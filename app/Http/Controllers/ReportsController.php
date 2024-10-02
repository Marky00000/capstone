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
        $query = Payment::where('payment_status', 'approve');
    
        // Apply date filters if provided
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }
    
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }
    
        $payments = $query->get();
    
        return view('reports.rates', compact('payments'));
    }
    
    
}
