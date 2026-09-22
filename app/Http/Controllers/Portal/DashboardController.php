<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Device;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show Business Owner Dashboard
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $businesses = $user->businesses()->with('devices')->get();
        $businessIds = $businesses->pluck('id');

        $totalDevices = Device::whereIn('business_id', $businessIds)->count();
        $activeDevices = Device::whereIn('business_id', $businessIds)->where('status', 'active')->count();

        $totalScans = Scan::whereIn('business_id', $businessIds)->count();
        $totalQrScans = Scan::whereIn('business_id', $businessIds)->where('scan_type', 'qr')->count();
        $totalNfcScans = Scan::whereIn('business_id', $businessIds)->where('scan_type', 'nfc')->count();

        // Recent Scans
        $recentScans = Scan::with(['device', 'business'])
            ->whereIn('business_id', $businessIds)
            ->latest('scanned_at')
            ->take(8)
            ->get();

        // Last 14 days chart data
        $startDate = Carbon::now()->subDays(13)->startOfDay();
        $rawChartData = Scan::select(
            DB::raw('DATE(scanned_at) as scan_date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN scan_type = "qr" THEN 1 ELSE 0 END) as qr_count'),
            DB::raw('SUM(CASE WHEN scan_type = "nfc" THEN 1 ELSE 0 END) as nfc_count')
        )
            ->whereIn('business_id', $businessIds)
            ->where('scanned_at', '>=', $startDate)
            ->groupBy('scan_date')
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

        return view('portal.dashboard', [
            'businesses' => $businesses,
            'totalDevices' => $totalDevices,
            'activeDevices' => $activeDevices,
            'totalScans' => $totalScans,
            'totalQrScans' => $totalQrScans,
            'totalNfcScans' => $totalNfcScans,
            'recentScans' => $recentScans,
            'chartLabels' => $chartLabels,
            'chartQrData' => $chartQrData,
            'chartNfcData' => $chartNfcData,
            'chartTotalData' => $chartTotalData,
        ]);
    }
}
