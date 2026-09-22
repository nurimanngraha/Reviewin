<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Show analytics page for the business owner
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $businessIds = $user->businesses()->pluck('id');
        $devices = Device::whereIn('business_id', $businessIds)->orderBy('name')->get();

        $query = Scan::with('device')->whereIn('business_id', $businessIds);

        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        if ($request->filled('scan_type')) {
            $query->where('scan_type', $request->scan_type);
        }

        $totalScans = (clone $query)->count();
        $totalQr = (clone $query)->where('scan_type', 'qr')->count();
        $totalNfc = (clone $query)->where('scan_type', 'nfc')->count();

        // Daily trend (last 14 days)
        $startDate = Carbon::now()->subDays(13)->startOfDay();
        $chartQuery = Scan::select(
            DB::raw('DATE(scanned_at) as scan_date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN scan_type = "qr" THEN 1 ELSE 0 END) as qr_count'),
            DB::raw('SUM(CASE WHEN scan_type = "nfc" THEN 1 ELSE 0 END) as nfc_count')
        )
            ->whereIn('business_id', $businessIds)
            ->where('scanned_at', '>=', $startDate);

        if ($request->filled('device_id')) {
            $chartQuery->where('device_id', $request->device_id);
        }

        $rawChartData = $chartQuery->groupBy('scan_date')
            ->orderBy('scan_date', 'asc')
            ->get()
            ->keyBy('scan_date');

        $chartLabels = [];
        $chartQrData = [];
        $chartNfcData = [];
        $chartTotalData = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::now()->subDays($i)->format('d M');
            $dayData = $rawChartData->get($date);

            $qr = $dayData ? (int) $dayData->qr_count : 0;
            $nfc = $dayData ? (int) $dayData->nfc_count : 0;
            $tot = $dayData ? (int) $dayData->total : 0;

            $chartQrData[] = $qr;
            $chartNfcData[] = $nfc;
            $chartTotalData[] = $tot;
        }

        $scans = $query->latest('scanned_at')->paginate(15)->withQueryString();

        return view('portal.analytics', [
            'devices' => $devices,
            'scans' => $scans,
            'totalScans' => $totalScans,
            'totalQr' => $totalQr,
            'totalNfc' => $totalNfc,
            'chartLabels' => $chartLabels,
            'chartQrData' => $chartQrData,
            'chartNfcData' => $chartNfcData,
            'chartTotalData' => $chartTotalData,
            'filters' => $request->only(['device_id', 'scan_type']),
        ]);
    }
}
