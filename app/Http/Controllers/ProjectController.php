<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ProjectCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Project;
use App\Models\Service;
use App\Models\Booking;

use Illuminate\Support\Facades\Log;



class ProjectController extends Controller
{
   // ProjectController.php
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

    // Debugging: Log the retrieved projects
    \Log::info('Retrieved Projects:', $projects->toArray());

    // Return the view with the filtered projects and optionally the booking_id
    return view('project.index', compact('projects', 'booking_id'));
}

    
    
    

    


    public function create($booking_id = null)
    {
        // Define the discount options as an associative array
        $discounts = [
            0 => '0',  // 0% discount
            1 => '1',  // 10% discount
            2 => '2',  // 20% discount
            3 => '3',  // 30% discount
            4 => '4',  // 40% discount
            5 => '5',  // 50% discount
            6 => '6',  // 60% discount
            7 => '7',  // 70% discount
            8 => '8',  // 80% discount
            9 => '9',  // 90% discount
            10 => '10', // 100% discount
            12 => '12',  // 100% discount
            15 => '15'  // 100% discount
        ];

        // Return the view with the booking_id and discounts
        return view('project.adminCreate', compact('booking_id','discounts'));
    }


    public function view($id)
    {
        // Retrieve the project by its ID
        $project = Project::with('booking', 'service')->findOrFail($id);
    
        // Return a view with the project details
        return view('project.view', compact('project'));
    }

        public function hold(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        // Change project status to 'hold'
        $project->project_status = 'hold';
        $project->save();

        // Optional: Add a success message to the session
        return redirect()->back()->with('success', 'Project status updated to hold.');
    }

     // Method to activate a project
     public function activate($id)
     {
         // Find the project by ID
         $project = Project::findOrFail($id);
 
         // Update the project status to 'active'
         $project->project_status = 'active';
         $project->save();
 
         // Redirect back with a success message
         return redirect()->back()->with('success', 'Project has been activated.');
     }


     public function generateReport()
     {
         // Retrieve all projects
         $projects = Project::all(); // You can filter by criteria if necessary
 
         // Pass the projects to the view
         return view('projects.reports', compact('projects'));
     }

        // app/Http/Controllers/ProjectController.php

            public function show($id)
            {
                // Find the project by its ID
                $project = Project::findOrFail($id);

                // Pass the project to the view
                return view('project.show', compact('project'));
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
    
    public function adminIndex(Request $request)
    {
        // Fetch the query parameters for filtering
        $statusFilter = $request->query('project_status');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        // Fetch projects (not bookings) with associated services and bookings
        $query = Project::with(['booking', 'service']);
        
        // Apply status filter if present
        if ($statusFilter) {
            $query->where('project_status', $statusFilter);
        }
        
        // Apply date filters if present
        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }
    
        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        // Default sorting is latest to oldest, but can be adjusted here if needed.
        $query->orderBy('created_at', 'desc'); // Adjust this as needed for default behavior.
        
        // Paginate the results
        $projects = $query->paginate(10);
        
        // Pass the filters to the view
        return view('project.adminIndex', compact('projects', 'statusFilter', 'startDate', 'endDate'));
    }
    
    

    public function adminShow($id)
    {
        // Fetch the project by ID
        $projects = Project::with('service', 'booking', 'progress')->findOrFail($id);
        
        // Return a view with the project details
        return view('project.adminShow', compact('projects')); // Pass the single project
    }
    
    
    

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'service_ids' => 'required|array', // Accept an array of service IDs
            'service_ids.*' => 'exists:services,id', // Ensure each service ID exists in the services table
            'lot_area' => 'required|numeric|min:1',
            'cost' => 'required|numeric|min:0', // Accept a cost value
            'discount' => 'nullable|numeric|min:0|max:100',
            'start_date' => 'required|date|after:today', // Validate start date
            'description' => 'nullable|string',
        ]);
    
        // Get the first selected service ID for service_id
        $service_id = $request->service_ids[0];
    
        // Retrieve the service and its details
        $service = Service::find($service_id);
    
        // Fetch the booking to get the province
        $booking = Booking::find($request->booking_id);
    
        // Get the cost from the request
        $cost = $request->cost; 
        $total_cost = $cost; // Initialize total_cost with the cost
    
        // Apply discount if available
        if ($request->discount) {
            $discountAmount = ($cost * ($request->discount / 100));
            $total_cost -= $discountAmount; // Subtract discount from total_cost
        }
    
        try {
            // Log the project creation details
            Log::info('Creating project', [
                'booking_id' => $request->booking_id,
                'service_id' => $service_id,
                'lot_area' => $request->lot_area,
                'discount' => $request->discount,
                'total_cost' => $total_cost,
                'start_date' => $request->start_date,
            ]);
    
            // Create a new project record with project_status set to 'pending'
            $project = Project::create([
                'booking_id' => $request->booking_id,
                'service_id' => $service_id, // Use the first selected service ID for main service
                'service_ids' => json_encode($request->service_ids), // Save service_ids as JSON
                'lot_area' => $request->lot_area,
                'cost' => $cost, // Store the initial cost
                'total_cost' => $total_cost, // Store the total cost calculated
                'description' => $request->description,
                'discount' => $request->discount,
                'start_date' => $request->start_date,
                'project_status' => 'pending',
            ]);
    
            // Update the booking status to completed
            $booking->update(['booking_status' => 'visited']);
    
            // Send the email notification to the booking email
            try {
                Mail::to($booking->email)->send(new ProjectCreatedMail($project));
            } catch (\Exception $emailException) {
                Log::error('Error sending email: ' . $emailException->getMessage());
            }
    
            // Update session alerts
            $alerts = session('alerts', []);
            $alerts[] = [
                'message' => "You have a new project with service name: " . $service->name,
            ];
            session(['alerts' => $alerts]);
    
            return redirect()->route('projects.adminIndex')->with('success', 'Project added successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating project: ' . $e->getMessage());
            return back()->with('error', 'There was an error creating the project. Please try again.');
        }
    }
    
    
    
    

//     public function edit($id)
//     {
//         // Retrieve the project by ID
//         $project = Project::findOrFail($id);
    
//         // Get the booking_id from the project
//         $booking_id = $project->booking_id; // Assuming booking_id is a column in the Project table
    
//         // Define the discount options as an associative array
//         $discounts = [
//             0 => '0',   // 0% discount
//             1 => '1',   // 10% discount
//             2 => '2',   // 20% discount
//             3 => '3',   // 30% discount
//             4 => '4',   // 40% discount
//             5 => '5',   // 50% discount
//             6 => '6',   // 60% discount
//             7 => '7',   // 70% discount
//             8 => '8',   // 80% discount
//             9 => '9',   // 90% discount
//             10 => '10', // 100% discount
//             12 => '12', // Another discount option, can be considered as additional
//             15 => '15'  // Another discount option, can be considered as additional
//         ];
    
//         // Return the view with project, booking_id, and discounts
//         return view('project.adminCreate', compact('project', 'booking_id', 'discounts'));
//     }
    

//         public function update(Request $request, $id)
// {
//     // Validate the incoming request data
//     $request->validate([
//         'booking_id' => 'required|exists:bookings,id',
//         'service_id' => 'required|exists:services,id',
//         'lot_area' => 'required|numeric|min:1',
//         'discount' => 'nullable|numeric|min:0|max:100',
//         'start_date' => 'required|date|after:today', // Validate start date
//         'description' => 'nullable|string',
//     ]);

//     // Retrieve the existing project to update
//     $project = Project::findOrFail($id);
    
//     // Retrieve the service and its details
//     $service = Service::find($request->service_id);

//     // Fetch the booking to get the province
//     $booking = Booking::find($request->booking_id);
//     $province = strtolower($booking->province); // Ensure province is lowercased for consistency

//     // Map province names to corresponding regions for pricing
//     $regionMapping = [
//         'northern_mindanao' => 'northern_mindanao',
//         'northern mindanao' => 'northern_mindanao',
//         'bukidnon' => 'northern_mindanao',
//         'lanao del norte' => 'northern_mindanao',
//         // Add more province mappings as necessary
//     ];

//     // Determine region type based on the province from the booking
//     $regionType = $regionMapping[$province] ?? 'other';

//     // Define pricing based on service, region, and complexity
//     $pricing = [
//         'landscaping' => [
//             'northern_mindanao' => [
//                 'very_easy' => 2000,
//                 'easy' => 2100,
//                 'medium' => 2200,
//                 'hard' => 2300,
//                 'very_hard' => 2400,
//             ],
//             'other' => [
//                 'very_easy' => 2500,
//                 'easy' => 2600,
//                 'medium' => 2700,
//                 'hard' => 2800,
//                 'very_hard' => 2900,
//             ],
//         ],
//         // Other service pricing definitions...
//         'maintenance' => [
//             'northern_mindanao' => [
//                 'very_easy' => 200,
//                 'easy' => 200,
//                 'medium' => 200,
//                 'hard' => 200,
//                 'very_hard' => 200,
//             ],
//             'other' => [
//                 'very_easy' => 400,
//                 'easy' => 400,
//                 'medium' => 400,
//                 'hard' => 400,
//                 'very_hard' => 400
//             ],
//         ],
//     ];

//     // Determine service type
//     $serviceType = strtolower($service->category);
    
//     // Determine complexity
//     $complexity = strtolower(str_replace(' ', '_', $service->complexity)); // Example: 'Very Easy' becomes 'very_easy'

//     // Adjust service type for renovation category
//     $effectiveServiceType = ($serviceType === 'renovation') ? $service->type : $serviceType;

//     // Get base price based on service, region, and complexity
//     $baseAmount = $pricing[$effectiveServiceType][$regionType][$complexity] ?? 0;
//     if ($baseAmount === 0) {
//         Log::warning('Base amount not found for service', [
//             'service_type' => $effectiveServiceType,
//             'region_type' => $regionType,
//             'complexity' => $complexity,
//         ]);
//     }

//     // Calculate total amount without applying discount
//     $amount = $baseAmount * $request->lot_area;

//     // Apply discount if available
//     if ($request->discount) {
//         // Calculate the discount amount based on the current amount
//         $discountAmount = ($amount * ($request->discount / 100));
//         // Reduce the amount by the discount
//         $amount -= $discountAmount; 
//     }

//     try {
//         // Log the project update details
//         Log::info('Updating project', [
//             'project_id' => $project->id,
//             'booking_id' => $request->booking_id,
//             'service_id' => $request->service_id,
//             'lot_area' => $request->lot_area,
//             'discount' => $request->discount,
//             'final_amount' => $amount,
//             'start_date' => $request->start_date,
//         ]);

//         // Update the project record
//         $project->update([
//             'booking_id' => $request->booking_id,
//             'service_id' => $request->service_id,
//             'lot_area' => $request->lot_area,
//             'total_cost' => $amount,
//             'description' => $request->description,
//             'discount' => $request->discount,
//             'start_date' => $request->start_date,
//         ]);

//         // Optionally update the booking status based on your business logic
//         // $booking->update(['booking_status' => 'completed']); // Uncomment if needed

//         // Send the email notification to the booking email (if needed)
//         try {
//             Mail::to($booking->email)->send(new ProjectUpdatedMail($project)); // You may need to create this Mailable
//         } catch (\Exception $emailException) {
//             Log::error('Error sending email: ' . $emailException->getMessage());
//         }

//         // Update session alerts
//         $service = Service::find($project->service_id); // Assuming you have service_id in the project
//         $alerts = session('alerts', []); // Retrieve existing alerts or initialize an empty array
//         $alerts[] = [
//             'message' => "The project with service name: " . $service->name . " has been updated.", // Custom message
//         ];

//         session(['alerts' => $alerts]); // Store the updated alerts back to the session

//         return redirect()->route('projects.adminIndex')->with('success', 'Project updated successfully.');
//     } catch (\Exception $e) {
//         // Log error and return back with an error message
//         Log::error('Error updating project: ' . $e->getMessage());
//         return back()->with('error', 'There was an error updating the project. Please try again.');
//     }
// }


    
}
