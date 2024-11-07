<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use App\Models\Project;
use App\Models\Payment; 
use Carbon\Carbon;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch total services with status 'available'
        $totalServices = Service::where('status', 'available')->count();

        // Fetch total bookings
        $totalBookings = Booking::count();

        // Fetch total projects with status 'pending', 'active', 'hold', or 'finished'
        $totalProjects = Project::whereIn('project_status', ['pending', 'active', 'hold', 'cancel', 'finish'])->count();

        // Calculate total revenue
        $totalRevenue = Payment::sum('amount');

        // Fetch revenue data for the chart
        $revenues = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Prepare chart data for revenue
        $chartData = [
            'labels' => array_map(fn($m) => date('F', mktime(0, 0, 0, $m, 1)), range(1, 12)), // Month names
            'data' => array_map(fn($m) => $revenues->get($m, 0), range(1, 12)) // Fill in 0 for months without revenue
        ];

        // Booking status distribution
        $bookingStatusCounts = Booking::selectRaw('booking_status, COUNT(*) as count')
            ->groupBy('booking_status')
            ->pluck('count', 'booking_status');

        // Prepare data for booking status pie chart
        $bookingStatusData = [
            'labels' => ['Pending', 'Confirmed', 'Visited', 'Cancelled', 'Declined'],
            'data' => [
                $bookingStatusCounts->get('pending', 0),
                $bookingStatusCounts->get('confirmed', 0),
                $bookingStatusCounts->get('visited', 0),
                $bookingStatusCounts->get('cancelled', 0),
                $bookingStatusCounts->get('declined', 0),
            ]
        ];

        // Project status distribution
        $projectStatusCounts = Project::selectRaw('project_status, COUNT(*) as count')
            ->groupBy('project_status')
            ->pluck('count', 'project_status');

        // Prepare data for project status bar chart
        $projectStatusData = [
            'labels' => ['Pending', 'Active', 'Hold','Cancelled', 'Finished'],
            'data' => [
                $projectStatusCounts->get('pending', 0),
                $projectStatusCounts->get('active', 0),
                $projectStatusCounts->get('hold', 0),
                $projectStatusCounts->get('cancel', 0),
                $projectStatusCounts->get('finish', 0),
            ]
        ];

        // Prepare data for bookings and payments over time
        $bookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $payments = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $bookingsPaymentsData = [
            'labels' => array_map(fn($m) => date('F', mktime(0, 0, 0, $m, 1)), range(1, 12)),
            'bookings' => array_map(fn($m) => $bookings->get($m, 0), range(1, 12)),
            'payments' => array_map(fn($m) => $payments->get($m, 0), range(1, 12)),
        ];

        return view('dashboard', compact('totalServices', 'totalBookings', 'totalProjects', 'totalRevenue', 'chartData', 'bookingStatusData', 'projectStatusData', 'bookingsPaymentsData'));
    }
}
