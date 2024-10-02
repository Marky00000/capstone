<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Mail\BookingSuccessMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth; // Make sure to include this at the top




class BookingController extends Controller
{
    public function index()
    {
        // Fetch bookings for the currently authenticated user
        $bookings = Booking::where('user_id', Auth::id())->paginate(10);


        return view('booking.index', compact('bookings'));
    }
    
    public function adminBooking(Request $request)
    {
        $query = Booking::query();

        // Apply booking status filter if provided
        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->input('booking_status'));
        }
    
        // Apply date filters if provided
        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }
    
        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }
    
        // Fetch filtered bookings
        $bookings = $query->get();
    
    
        // Pass the filters and sort order to the view
        return view('booking.adminBooking', compact('bookings'));
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


    return view('booking.form', compact('cities'));    }
  


    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'contact' => 'required|string',
            'email' => 'required|string|email', // Email provided in the request
            'site_visit_date' => 'required|date',
            'user_id' => 'required|exists:users,id', // Ensure user exists
            'address' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
        ]);
    
        // Create a new booking and include user_id
        $booking = Booking::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'email' => $request->email,
            'site_visit_date' => $request->site_visit_date,
            'user_id' => $request->user_id, // Set user_id here
            'address' => $request->address,
            'province' => $request->province,
            'city' => $request->city,
        ]);
    
        // Prepare all booking details for the email
        $bookingDetails = [
            'id' => $booking->id,
            'name' => $booking->name,
            'contact' => $booking->contact,
            'email' => $booking->email,
            'site_visit_date' => $booking->site_visit_date,
            'address' => $booking->address,
            'province' => $booking->province,
            'city' => $booking->city,
            'user_id' => $booking->user_id, // Include user_id in the details
        ];  
    
        // Optionally, get user email from the user_id and send the email
        $userEmail = User::find($booking->user_id)->email;
        Mail::to($userEmail)->send(new BookingSuccessMail($bookingDetails));
    
        return response()->json(['message' => 'Booking created successfully.']);
    }

    public function cancelBooking($id) {
        $booking = Booking::findOrFail($id);
        
        // Check if the booking status is 'pending'
        if ($booking->booking_status === 'pending') {
            $booking->booking_status = 'cancelled'; // Change status to 'cancelled'
            $booking->save();
            
            return response()->json(['message' => 'Booking canceled successfully.']);
        } else {
            return response()->json(['message' => 'Booking cannot be canceled as it is already completed or cancelled.'], 400);
        }
    }
    

    public function confirmBooking($id) {
        $booking = Booking::findOrFail($id);
    
        // Only change status if it's pending
        if ($booking->booking_status === 'pending') {
            $booking->booking_status = 'confirmed'; // Change status to confirmed
            $booking->save();
        }
    
        session()->flash('success', 'Booking confirmed successfully!'); // Set flash message
        return response()->json(['message' => 'Booking confirmed successfully.']);
    }

    
    public function declineBooking($id, Request $request) {
        $booking = Booking::find($id);
    
        // Check if the booking exists
        if (!$booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }
    
        // Update the booking status to 'declined'
        $booking->booking_status = 'declined';
        $booking->save();
    
        session()->flash('success', 'Booking has been declined!'); // Set flash message
        return response()->json(['message' => 'Booking has been declined!']);
    }

public function view($id)
{
    // Fetch the booking using the provided ID
    $booking = Booking::findOrFail($id);

    // Return a view with the booking data
    return view('booking.view', compact('booking'));
}

public function edit($id)
{
    $booking = Booking::findOrFail($id); // Fetch the booking by ID
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

    return view('booking.edit', compact('booking', 'cities')); // Return the edit view with the booking data and cities
}



public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'contact' => 'required|string|max:15',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'province' => 'required|string|max:100',
        'site_visit_date' => 'required|date',
    ]);

    $booking = Booking::findOrFail($id);
    
    // Use only to get the expected fields
    $booking->update($request->only([
        'name', 
        'email', 
        'contact', 
        'address', 
        'city', 
        'province', 
        'site_visit_date'
    ]));

    return redirect()->route('booking.index')->with('success', 'Booking updated successfully.');
}



    
}
