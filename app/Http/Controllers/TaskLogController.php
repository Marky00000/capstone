<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskLog; // Import your model

class TaskLogController extends Controller
{
    public function index()
    {
        // Get task logs for the authenticated user, ordered by action_date
        $taskLogs = TaskLog::where('user_id', auth()->id())
                           ->orderBy('action_date', 'desc')
                           ->paginate(10); 
        
        return view('tasklog.index', compact('taskLogs')); // Pass the logs to your view
    }

    public function adminIndex()
    {
         // Get task logs for the authenticated user, ordered by action_date
         $taskLogs = TaskLog::where('user_id', auth()->id())
         ->orderBy('action_date', 'desc')
         ->paginate(10); 
                
        return view('tasklog.adminIndex', compact('taskLogs')); // Pass the logs to the admin view
    }
}

