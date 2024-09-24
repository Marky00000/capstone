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
        // Get the currently logged-in user
        $user = auth()->user();
    
        // Check if the user has an associated booking ID
        if ($booking_id) {
            // Retrieve projects associated with the user's bookings
            $projects = Project::where('booking_id', $booking_id)
                               ->whereHas('booking', function($query) use ($user) {
                                   $query->where('user_id', $user->id);
                               })
                               ->paginate(10);
        } else {
            // If no booking ID is provided, return projects associated with the user
            $projects = Project::whereHas('booking', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })->paginate(10);
        }
    
        // Return the view with the filtered projects and optionally the booking_id
        return view('project.index', compact('projects', 'booking_id'));
    }
    
    
    


    public function create($booking_id = null)
    {
        // Define the discount options as an associative array
        $discounts = [
            0 => '0%',   // 0% discount
            1 => '1%',  // 10% discount
            2 => '2%',  // 20% discount
            3 => '3%',  // 30% discount
            4 => '4%',  // 40% discount
            5 => '5%',  // 50% discount
            6 => '6%',  // 60% discount
            7 => '7%',  // 70% discount
            8 => '8%',  // 80% discount
            9 => '9%',  // 90% discount
            10 => '10%'  // 100% discount
        ];

        // Return the view with the booking_id and discounts
        return view('project.adminCreate', compact('booking_id','discounts'));
    }

    public function calculateCost(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'category' => 'required|string',
            'complexity' => 'required|string',
            'lot_area' => 'required|numeric|min:1',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);
    
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
    
        // Extract the parameters
        $category = strtolower($request->category);
        $complexity = strtolower(str_replace(' ', '_', $request->complexity));
    
        // Define region type or fetch from the booking as needed
        $regionType = 'northern_mindanao'; // Example value, replace as necessary
    
        // Get base price based on service, region, and complexity
        $baseAmount = $pricing[$category][$regionType][$complexity] ?? 0;
    
        // Calculate total amount
        $amount = $baseAmount * $request->lot_area;
    
        // Apply discount if provided
        if ($request->discount) {
            $discountAmount = ($request->discount / 100) * $amount;
            $amount -= $discountAmount;
        }
    
        // Return the cost as a JSON response
        return response()->json(['cost' => $amount]);
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