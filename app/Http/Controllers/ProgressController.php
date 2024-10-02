<?php

namespace App\Http\Controllers;
use App\Models\Project; //use Illuminate\Http\Request;
use App\Models\Progress; // Ensure you have a Progress model
use Illuminate\Http\Request;

class ProgressController extends Controller
{
  /**
     * Display the progress of a specific project.
     *
     * @param int $projectId
     * @return \Illuminate\View\View
     */
    public function index($projectId)
    {
        // Fetch the project by ID
        $project = Project::with('service')->findOrFail($projectId);
        
        // Fetch the progress related to the project
        $progress = Progress::where('project_id', $project->id)->get();

        // Return the view with the project and its progress
        return view('progress.index', compact('project', 'progress'));
    }


    public function view($projectId)
    {
        // Fetch the project by ID
        $project = Project::with('service')->findOrFail($projectId);
        
        // Fetch the progress related to the project
        $progress = Progress::where('project_id', $project->id)->get();
    
        // Return the view with the project and its progress
        return view('progress.view', compact('project', 'progress'));
    }
    
    


    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'phase' => 'required|in:phase_one,phase_two,phase_three',
            'phase_progress' => 'required|in:0,10,20,30,40,50,60,70,80,90,100',
            'image' => 'nullable|image|max:2048', // Optional image
            'remarks' => 'nullable|string', // Make remarks required
        ]);
    
        // Handle file upload for the image if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('project_images', 'public');
        }
    
        // Set default value for remarks if empty
        $remarks = $request->remarks ?: 'No remarks'; // Use 'No remarks' if remarks is empty
    
        // Create new tracking entry
        Progress::create([
            'project_id' => $request->project_id,
            'phase' => $request->phase,
            'phase_progress' => $request->phase_progress,
            'image' => $imagePath,
            'remarks' => $remarks, // Save remarks (default or provided)
        ]);
    
        return response()->json(['success' => true, 'message' => 'Project progress stored successfully!']);
    }
    
    
}
