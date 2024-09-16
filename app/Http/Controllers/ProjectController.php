<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Service;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;



class ProjectController extends Controller
{
    public function index($booking_id = null)
    {
        $projects = Project::paginate(10); // Adjust the number as needed
        
        // Return the view with projects and optionally booking_id
        return view('project.index', compact('projects', 'booking_id'));
    }
    
    

    public function create($booking_id = null)
    {
        // You can handle what happens when booking_id is null or present
        return view('project.adminCreate', compact('booking_id'));
    }
    
    
    
    
      /**
     * Fetch designs based on the selected service category.
     *
     * @param string $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDesigns($type)
    {
        // Define valid categories
        $validCategories = ['landscaping', 'swimmingpool', 'maintenance', 'renovation'];
    
        // Validate service category
        if (!in_array($type, $validCategories)) {
            return response()->json(['error' => 'Invalid category'], 400);
        }
    
        // Fetch designs from services based on category and status
        $designs = Service::where('category', $type)
            ->where('status', 'available') // Only available services
            ->select('id', 'name', 'design', 'description', 'complexity') // Adjust fields based on your table
            ->get();
    
        // Add a 'type' field for renovation designs
        if ($type === 'renovation') {
            $designs->each(function ($design) {
                $design->type = 'Renovation'; // Add 'type' field
            });
        }
    
        // Ensure the design URL is correctly formatted
        $designs->each(function ($design) {
            $design->design = asset('storage/' . $design->design); // Assuming images are stored in 'public/storage'
        });
    
        return response()->json($designs);
    }
    

    public function adminIndex()
    {
        $projects = Project::paginate(10); // Adjust the number per page as needed
        return view('project.adminIndex', compact('projects'));
    }
    
    
    
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'service_id' => 'required|exists:services,id',
            'lot_area' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);
         // Retrieve the service and its details
        $service = Service::find($request->service_id);
    
        // Fetch the booking to get the region
        $booking = Booking::find($request->booking_id);
        $region = strtolower($booking->region);
    
        // Define pricing based on service, region, and complexity
        $pricing = [
            'landscaping' => [
                'northern_mindanao' => [
                    'very_easy' => 2000,
                    'easy' => 2100,
                    'medium' => 2200,
                    'hard' => 2300,
                    'very_hard' => 2400,
                ],
                'other' => [
                    'very_easy' => 2500,
                    'easy' => 2600,
                    'medium' => 2700,
                    'hard' => 2800,
                    'very_hard' => 2900,
                ],
            ],
            'swimmingpool' => [
                'northern_mindanao' => [
                    'very_easy' => 10000,
                    'easy' => 11000,
                    'medium' => 12000,
                    'hard' => 13000,
                    'very_hard' => 14000,
                ],
                'other' => [
                    'very_easy' => 15000,
                    'easy' => 16000,
                    'medium' => 17000,
                    'hard' => 18000,
                    'very_hard' => 19000,
                ],
            ],
            'renovation' => [
                'northern_mindanao' => [
                    'very_easy' => 2000,
                    'easy' => 2100,
                    'medium' => 2200,
                    'hard' => 2300,
                    'very_hard' => 2400,
                ],
                'other' => [
                    'very_easy' => 2500,
                    'easy' => 2600,
                    'medium' => 2700,
                    'hard' => 2800,
                    'very_hard' => 2900,
                ],
            ],
        ];
    
        // Determine region type based on the region from the booking
        $regionType = $region == 'northern mindanao' ? 'northern_mindanao' : 'other';
    
        // Determine service type
        $serviceType = strtolower($service->category);
    
        // Determine complexity
        $complexity = strtolower(str_replace(' ', '_', $service->complexity)); // Example: 'Very Easy' becomes 'very_easy'
    
        // Adjust service type for renovation category
        if ($serviceType === 'renovation') {
            $effectiveServiceType = $service->type; // Use the service type (landscaping or swimmingpool) for renovation
        } else {
            $effectiveServiceType = $serviceType;
        }
    
        // Get base price based on service, region, and complexity
        $baseAmount = $pricing[$effectiveServiceType][$regionType][$complexity] ?? 0;
    
        // Calculate total amount
        $amount = $baseAmount * $request->lot_area;
    
        try {
            // Add debug information to ensure only one insertion is happening
            Log::info('Creating project', [
                'booking_id' => $request->booking_id,
                'service_id' => $request->service_id,
                'lot_area' => $request->lot_area,
                'amount' => $amount,
            ]);
    
            // Create a new project record with project_status set to 'pending'
            Project::create([
                'booking_id' => $request->booking_id,
                'service_id' => $request->service_id,
                'lot_area' => $request->lot_area,
                'total_cost' => $amount, // Use calculated amount
                'description' => $request->description,
                'project_status' => 'pending', // Set project_status to 'pending'
            ]);
    
            return redirect()->route('projects.adminIndex')->with('success', 'Project added successfully.');
        } catch (\Exception $e) {
            // Log error and return back with an error message
            Log::error('Error creating project: ' . $e->getMessage());
            return back()->with('error', 'There was an error creating the project. Please try again.');
        }
    }
    
}
