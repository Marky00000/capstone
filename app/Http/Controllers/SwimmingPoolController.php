<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class SwimmingPoolController extends Controller
{
    public function index()
    {
        try {
            // Fetch only services with the category 'swimmingpool' and status 'available', with pagination
            $services = Service::where('category', 'swimmingpool')
                                ->where('status', 'available')
                                ->paginate(10); // Limit to 10 items per page
    
            return view('swimmingpool.index', ['services' => $services]);
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error fetching swimming pool services: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Failed to fetch swimming pool services.');
        }
    }
    
    

    public function create()
    {
        $swimmingpool_id = 2; 
        $complexityLevels = ['very_easy','easy', 'medium', 'hard', 'very_hard'];
        return view('swimmingpool.form', ['swimmingpool_id' => $swimmingpool_id]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'design' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'complexity' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        // Store the design file
        $path = $request->file('design')->store('designs', 'public');
    
        // Create the SwimmingPool service
        Service::create([
            'name' => $request->name,
            'design' => $path,
            'complexity' => $request->complexity,
            'description' => $request->description,
            'category' => 'swimmingpool', // Ensure the category is correctly set
        ]);
    
        return redirect()->route('swimmingpool')->with('success', 'Service added successfully.');
    }
    
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $complexityLevels = ['very_easy','easy', 'medium', 'hard', 'very_hard'];
        return view('swimmingpool.form', [
            'service' => $service,
            'complexityLevels' => $complexityLevels,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'design' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'complexity' => 'required|string|in:very_easy,easy,medium,hard,very_hard',
            'description' => 'nullable|string',
        ]);

        $service = Service::findOrFail($id);

        if ($request->hasFile('design')) {
            $path = $request->file('design')->store('designs', 'public');
            $service->design = $path;
        }

        $service->name = $request->name;
        $service->complexity = $request->complexity;
        $service->description = $request->description;

        $service->save();

        return redirect()->route('swimmingpool')->with('success', 'Service updated successfully.');
    }
    
    
    public function archive($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->status = 'archive';
            $service->save();
            return redirect()->route('swimmingpool')->with('success', 'Service archived successfully.');
        } catch (\Exception $e) {
            // \Log::error('Error archiving service: ' . $e->getMessage());
            return redirect()->route('swimmingpool')->with('error', 'An error occurred while archiving the service.');
        }
    }
    
  
}
