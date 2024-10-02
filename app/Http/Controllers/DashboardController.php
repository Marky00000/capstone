<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use App\Models\Project;
use App\Models\Payment; // Ensure you import your Payment model
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch total services with status 'available'
        $totalServices = Service::where('status', 'available')->count();

        // Fetch total bookings with status 'confirmed'
        $totalBookings = Booking::count();

        // Fetch total projects with status 'active'
        $totalProjects = Project::where('project_status', 'active')->count();

        $totalRevenue = Payment::where('payment_status', 'approve')->sum('amount');

        // Fetch revenue data for the chart
        $revenues = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Prepare chart data
        $chartData = [
            'labels' => array_map(fn($m) => date('F', mktime(0, 0, 0, $m, 1)), array_keys($revenues->toArray())), // Month names
            'data' => $revenues->values()->toArray() // Total amounts
        ];

        return view('dashboard', compact('totalServices', 'totalBookings', 'totalProjects', 'totalRevenue', 'chartData'));
    }
}
