<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Device;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScanLogController extends Controller
{
    /**
     * Display real-time telemetry and scan logs
     */
    public function index(Request $request): View
    {
        $query = Scan::with(['device', 'business'])->latest('scanned_at');

        if ($request->filled('scan_type')) {
            $query->where('scan_type', $request->scan_type);
        }

        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        if ($request->filled('business_id')) {
            $query->where('business_id', $request->business_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('scanned_at', $request->date);
        }

        $scans = $query->paginate(20)->withQueryString();
        $devices = Device::orderBy('device_code')->get();
        $businesses = Business::orderBy('name')->get();

        return view('admin.scans.index', [
            'scans' => $scans,
            'devices' => $devices,
            'businesses' => $businesses,
            'filters' => $request->only(['scan_type', 'device_id', 'business_id', 'date']),
        ]);
    }
}
