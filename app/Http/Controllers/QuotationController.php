<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Service;
use App\Models\TaskLog;
use App\Models\Design;


class QuotationController extends Controller
{

    public function getDesigns($type)
{
    // Define valid categories
    $validCategories = ['landscaping', 'swimmingpool', 'maintenance', 'renovation', 'package'];
    $validTypes = ['landscaping', 'swimmingpool', 'maintenance', 'renovation', 'package'];
    
    
    // Validate service category
    if (!in_array($type, $validCategories)) {
        return response()->json(['error' => 'Invalid category'], 400);
    }

    // Fetch designs from services based on category and status
    $designs = Service::where('category', $type)
        ->where('status', 'available') // Filter only available services
        ->select('id', 'name', 'design', 'description', 'complexity') // Ensure 'complexity' is a column in your 'services' table
        ->get();

    // Add a 'type' field for renovation designs
    if ($type === 'renovation') {
        $designs->each(function ($design) {
            $design->type = 'Renovation'; // Add 'type' field
        });
    }

    // Ensure the design URL is absolute or correct relative path
    $designs->each(function ($design) {
        $design->design = asset('storage/' . $design->design); 
    });

    return response()->json($designs);
}


public function details($id)
{
    // Retrieve the specific quotation using the id
    $quotation = Quotation::findOrFail($id);

    // Pass the quotation data to the view
    return view('quotation.details', compact('quotation'));
}
    
    

   
public function create()
{
    $cities = [
    'NCR' => [ // NCR
        ['id' => 'Manila', 'name' => 'Manila'],
        ['id' => 'Quezon City', 'name' => 'Quezon City'],
        ['id' => 'Caloocan', 'name' => 'Caloocan'],
        ['id' => 'Makati', 'name' => 'Makati'],
        ['id' => 'Taguig', 'name' => 'Taguig'],
        ['id' => 'Pasig', 'name' => 'Pasig'],
        ['id' => 'Malabon', 'name' => 'Malabon'],
        ['id' => 'Marikina', 'name' => 'Marikina'],
        ['id' => 'Navotas', 'name' => 'Navotas'],
        ['id' => 'Valenzuela', 'name' => 'Valenzuela'],
        ['id' => 'San Juan', 'name' => 'San Juan'],
        ['id' => 'Pateros', 'name' => 'Pateros'],
    ],
    'CAR' => [ // CAR
        ['id' => 'Baguio City', 'name' => 'Baguio City'],
        ['id' => 'Abra', 'name' => 'Abra'],
        ['id' => 'Apayao', 'name' => 'Apayao'],
        ['id' => 'Benguet', 'name' => 'Benguet'],
        ['id' => 'Ifugao', 'name' => 'Ifugao'],
        ['id' => 'Kalinga', 'name' => 'Kalinga'],
        ['id' => 'Mountain Province', 'name' => 'Mountain Province'],
    ],
    'Ilocos Region' => [ // Ilocos Region
        ['id' => 'Vigan', 'name' => 'Vigan'],
        ['id' => 'Laoag', 'name' => 'Laoag'],
        ['id' => 'Dagupan', 'name' => 'Dagupan'],
        ['id' => 'San Fernando', 'name' => 'San Fernando'],
        ['id' => 'Alaminos', 'name' => 'Alaminos'],
        ['id' => 'Urdaneta', 'name' => 'Urdaneta'],
    ],
    'Cagayan Valley' => [ // Cagayan Valley
        ['id' => 'Tuguegarao', 'name' => 'Tuguegarao'],
        ['id' => 'Ilagan', 'name' => 'Ilagan'],
        ['id' => 'Cauayan', 'name' => 'Cauayan'],
        ['id' => 'Santiago', 'name' => 'Santiago'],
        ['id' => 'Aparri', 'name' => 'Aparri'],
    ],
    'Central Luzon' => [ // Central Luzon
        ['id' => 'San Fernando', 'name' => 'San Fernando'],
        ['id' => 'Angeles', 'name' => 'Angeles'],
        ['id' => 'Olongapo', 'name' => 'Olongapo'],
        ['id' => 'Tarlac', 'name' => 'Tarlac'],
        ['id' => 'Pampanga', 'name' => 'Pampanga'],
        ['id' => 'Bulacan', 'name' => 'Bulacan'],
        ['id' => 'Zambales', 'name' => 'Zambales'],
    ],
    'CALABARZON' => [ // CALABARZON
        ['id' => 'Cavite', 'name' => 'Cavite'],
        ['id' => 'Batangas', 'name' => 'Batangas'],
        ['id' => 'Laguna', 'name' => 'Laguna'],
        ['id' => 'Rizal', 'name' => 'Rizal'],
        ['id' => 'Quezon', 'name' => 'Quezon'],
    ],
    'MIMAROPA' => [ // MIMAROPA
        ['id' => 'Calapan', 'name' => 'Calapan'],
        ['id' => 'Romblon', 'name' => 'Romblon'],
        ['id' => 'Puerto Princesa', 'name' => 'Puerto Princesa'],
        ['id' => 'Marinduque', 'name' => 'Marinduque'],
        ['id' => 'Occidental Mindoro', 'name' => 'Occidental Mindoro'],
        ['id' => 'Oriental Mindoro', 'name' => 'Oriental Mindoro'],
    ],
    'Bicol Region' => [ // Bicol Region
        ['id' => 'Legazpi', 'name' => 'Legazpi'],
        ['id' => 'Naga', 'name' => 'Naga'],
        ['id' => 'Iriga', 'name' => 'Iriga'],
        ['id' => 'Sorsogon', 'name' => 'Sorsogon'],
        ['id' => 'Catanduanes', 'name' => 'Catanduanes'],
    ],
    'Western Visayas' => [ // Western Visayas
        ['id' => 'Iloilo', 'name' => 'Iloilo'],
        ['id' => 'Bacolod', 'name' => 'Bacolod'],
        ['id' => 'Roxas', 'name' => 'Roxas'],
        ['id' => 'Kabankalan', 'name' => 'Kabankalan'],
        ['id' => 'San Carlos', 'name' => 'San Carlos'],
    ],
    'Central Visayas' => [ // Central Visayas
        ['id' => 'Cebu City', 'name' => 'Cebu City'],
        ['id' => 'Tagbilaran', 'name' => 'Tagbilaran'],
        ['id' => 'Dumaguete', 'name' => 'Dumaguete'],
        ['id' => 'Mandaue', 'name' => 'Mandaue'],
        ['id' => 'Lapu-Lapu', 'name' => 'Lapu-Lapu'],
    ],
    'Eastern Visayas' => [ // Eastern Visayas
        ['id' => 'Tacloban', 'name' => 'Tacloban'],
        ['id' => 'Ormoc', 'name' => 'Ormoc'],
        ['id' => 'Calbayog', 'name' => 'Calbayog'],
        ['id' => 'Catbalogan', 'name' => 'Catbalogan'],
        ['id' => 'Borongan', 'name' => 'Borongan'],
    ],
    'Zamboanga Peninsula' => [ // Zamboanga Peninsula
        ['id' => 'Zamboanga City', 'name' => 'Zamboanga City'],
        ['id' => 'Dipolog', 'name' => 'Dipolog'],
        ['id' => 'Dapitan', 'name' => 'Dapitan'],
        ['id' => 'Pagadian', 'name' => 'Pagadian'],
        ['id' => 'Dipolog', 'name' => 'Dipolog'],
    ],
    'Northern Mindanao' => [ // Northern Mindanao
        ['id' => 'Cagayan de Oro', 'name' => 'Cagayan de Oro'],
        ['id' => 'Bukidnon', 'name' => 'Bukidnon'],
        ['id' => 'Misamis Oriental', 'name' => 'Misamis Oriental'],
        ['id' => 'Misamis Occidental', 'name' => 'Misamis Occidental'],
    ],
    'Davao Region' => [ // Davao Region
        ['id' => 'Davao City', 'name' => 'Davao City'],
        ['id' => 'Tagum', 'name' => 'Tagum'],
        ['id' => 'Digos', 'name' => 'Digos'],
        ['id' => 'Panabo', 'name' => 'Panabo'],
        ['id' => 'Samal', 'name' => 'Samal'],
    ],
    'SOCCSKSARGEN' => [ // SOCCSKSARGEN
        ['id' => 'General Santos', 'name' => 'General Santos'],
        ['id' => 'Koronadal', 'name' => 'Koronadal'],
        ['id' => 'Tacurong', 'name' => 'Tacurong'],
        ['id' => 'Kidapawan', 'name' => 'Kidapawan'],
        ['id' => 'Cotabato', 'name' => 'Cotabato'],
    ],
    'Caraga' => [ // Caraga
        ['id' => 'Butuan', 'name' => 'Butuan'],
        ['id' => 'Surigao City', 'name' => 'Surigao City'],
        ['id' => 'Bislig', 'name' => 'Bislig'],
        ['id' => 'Tandag', 'name' => 'Tandag'],
        ['id' => 'Cabadbaran', 'name' => 'Cabadbaran'],
    ],
    'BARMM' => [ // BARMM
        ['id' => 'Cotabato City', 'name' => 'Cotabato City'],
        ['id' => 'Marawi City', 'name' => 'Marawi City'],
        ['id' => 'Lamitan', 'name' => 'Lamitan'],
        ['id' => 'Lanao del Sur', 'name' => 'Lanao del Sur'],
        ['id' => 'Lanao del Norte', 'name' => 'Lanao del Norte'],
        ['id' => 'Basilan', 'name' => 'Basilan'],
        ['id' => 'Sulu', 'name' => 'Sulu'],
        ['id' => 'Tawi-Tawi', 'name' => 'Tawi-Tawi'],
    ],
];
return view('quotation.form', compact('cities'));    
}

    

    public function index()
    {
        $user = auth()->user();
        $quotations = Quotation::where('user_id', $user->id)->get();

        return view('quotation.index', compact('quotations'));
    }

    public function view()
    {
        $user = auth()->user();
        $quotations = Quotation::where('user_id', $user->id)->paginate(10);
    
        return view('quotation.view', compact('quotations'));
    }
    

    // public function create()
    // {
    //     $services = Service::all();
    //     return view('quotation.form', compact('services'));
    // }

    public function store(Request $request)
{
    $userId = Auth::id();
    // Validate the incoming request data
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'service_id' => 'required|exists:services,id',
        'address' => 'required|string',
        'city' => 'required|string',
        'region' => 'required|string',
        'lot_area' => 'required|numeric|min:1',
    ]);

    // Retrieve the service and its details
    $service = Service::find($request->service_id);

    // Define pricing and working days based on service, region, and complexity
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
                'easy' => 10500,
                'medium' => 11000,
                'hard' => 11500,
                'very_hard' => 12000,
            ],
            'other' => [
                'very_easy' => 12500,
                'easy' => 13000,
                'medium' => 13500,
                'hard' => 14000,
                'very_hard' => 14500,
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
        'maintenance' => [ // New maintenance category added
            'northern_mindanao' => [
                'very_easy' => 200,
                'easy' => 200,
                'medium' => 200,
                'hard' => 200,
                'very_hard' => 200,
            ],
            'other' => [
                'very_easy' => 400,
                'easy' => 400,
                'medium' => 400,
                'hard' => 400,
                'very_hard' => 400
            ],
        ],
        'package' => [
            'northern_mindanao' => [
                'very_easy' => 1000,
                'easy' => 1100,
                'medium' => 1200,
                'hard' => 1300,
                'very_hard' => 1400,
            ],
            'other' => [
                'very_easy' => 1500,
                'easy' => 1600,
                'medium' => 1700,
                'hard' => 1800,
                'very_hard' => 1900,
            ],
        ],
    ];

    $workingDaysByService = [
        'landscaping' => [
            'northern_mindanao' => [
                'very_easy' => 1,
                'easy' => 1,
                'medium' => 1,
                'hard' => 1,
                'very_hard' => 1,
            ],
            'other' => [
                'very_easy' => 1,
                'easy' => 1,
                'medium' => 1,
                'hard' => 1,
                'very_hard' => 1,
            ],
        ],
        'swimmingpool' => [
            'northern_mindanao' => [
                'very_easy' => 1,
                'easy' => 1,
                'medium' => 1,
                'hard' => 1,
                'very_hard' => 1,
            ],
            'other' => [
                'very_easy' => 1,
                'easy' => 1,
                'medium' => 1,
                'hard' => 1,
                'very_hard' => 1,
            ],
        ],
        'renovation' => [
            'northern_mindanao' => [
                'very_easy' => 1,
                'easy' => 1,
                'medium' => 1,
                'hard' => 1,
                'very_hard' => 1,
            ],
            'other' => [
                'very_easy' => 1,
                'easy' => 1,
                'medium' => 1,
                'hard' => 1,
                'very_hard' => 1,
            ],
        ],
        'package' => [
            'northern_mindanao' => [
                'very_easy' => 1,
                'easy' => 1,
                'medium' => 1,
                'hard' => 1,
                'very_hard' => 1,
            ],
            'other' => [
                'very_easy' => 1,
                'easy' => 1,
                'medium' => 1,
                'hard' => 1,
                'very_hard' => 1,
            ],
        ],
    ];

    // Determine region type
    $regionType = strtolower($request->region) == 'northern mindanao' ? 'northern_mindanao' : 'other';

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

    // Calculate working days based on lot area
    $lotArea = $request->lot_area;

    if (in_array($effectiveServiceType, ['landscaping', 'swimmingpool', 'renovation', 'package'])) {
        if ($lotArea <= 20) {
            $workingDays = 3;
        } elseif ($lotArea <= 30) {
            $workingDays = 5;
        } elseif ($lotArea <= 40) {
            $workingDays = 7;
        } elseif ($lotArea <= 50) {
            $workingDays = 10;
        } elseif ($lotArea <= 60) {
            $workingDays = 12;
        } elseif ($lotArea <= 70) {
            $workingDays = 15;
        } elseif ($lotArea <= 80) {
            $workingDays = 18;
        } elseif ($lotArea <= 90) {
            $workingDays = 20;
        } elseif ($lotArea <= 100) {
            $workingDays = 25;
        } elseif ($lotArea <= 200) {
            $workingDays = 30;
        } elseif ($lotArea <= 300) {
            $workingDays = 40;
        } else {
            $workingDays = 7;
        }
    } else {
        $workingDays = 0; // Default for unknown service types
    }

    try {
        // Create a new quotation record
        $quotation = Quotation::create([
            'user_id' => $request->user_id,
            'service_id' => $request->service_id,
            'address' => $request->address,
            'city' => $request->city,
            'region' => $request->region,
            'lot_area' => $request->lot_area,
            'amount' => $amount,
            'working_days' => $workingDays,
        ]);

         // Create a task log entry
    $user = auth()->user(); // Get the currently authenticated user
    TaskLog::create([
        'user_id' => $user ? $user->id : null, // Use the user's ID or set to null if not authenticated
        'type' => 'Quotation', // Type of action being logged
        'type_id' => $quotation->id, // ID of the created quotation
        'action' => 'Created a new Quotation', // Description of the action
        'action_date' => now(), // Current timestamp
    ]);


        return response()->json([
            'success' => true,
            'message' => 'Quotation created successfully.',
            'redirect_url' => route('quotation.view') // Adjust if needed
        ]);
    } catch (\Exception $e) {
        // Log error and return back with an error message
        Log::error('Error creating quotation: ' . $e->getMessage());
        return back()->with('error', 'There was an error creating the quotation. Please try again.');
    }
}

    

    public function edit($id)
    {
        $quotation = Quotation::findOrFail($id);
        $services = Service::all();

        return view('quotations.edit', compact('quotation', 'services'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'address' => 'required|string',
            'city' => 'required|string',
            'region' => 'required|string',
            'lot_area' => 'required|numeric',
            'working_days' => 'nullable|string',
        ]);

        $quotation = Quotation::findOrFail($id);
        $quotation->update([
            'user_id' => $request->user_id,
            'service_id' => $request->service_id,
            'address' => $request->address,
            'city' => $request->city,
            'region' => $request->region,
            'lot_area' => $request->lot_area,
            'amount' => $request->amount, // Ensure this is being calculated or provided
            'working_days' => $request->working_days,
        ]);

        return response()->json(['success' => true, 'message' => 'Quotation updated successfully.']);
    }

    // Add a method to get cities by region
    public function getCitiesByRegion($regionId)    
    {
        return response()->json($this->cities[$regionId] ?? []);
    }
}
