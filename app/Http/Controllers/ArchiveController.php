<?php

namespace App\Http\Controllers;

use App\Models\Renovation;
use App\Models\Landscape;
use App\Models\SwimmingPool;
use App\Models\Service;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index()
    {
        try {
            // Fetch all services with status 'archive'
            $archivedServices = Service::where('status', 'archive')->get();
            

            return view('archive.index', [
                'services' => $archivedServices,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching archived services: ' . $e->getMessage());
            return back()->with('error', 'Failed to fetch archived services.');
        }
    }

    public function restore($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->status = 'available'; // Set status to 'available'
            $service->save();

            return redirect()->route('archive.index')->with('success', 'Service restored successfully.');
        } catch (\Exception $e) {
            \Log::error('Error restoring service: ' . $e->getMessage());
            return back()->with('error', 'Failed to restore service.');
        }
    }

    // Method to delete archived service
    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete(); // Permanently delete the service

            return redirect()->route('archive.index')->with('success', 'Service deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting service: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete service.');
        }
    }
}
