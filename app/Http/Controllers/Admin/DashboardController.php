<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activation;
use App\Models\Business;
use App\Models\Device;
use App\Models\Scan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard overview
     */
    public function index(): View
    {
        $totalDevices = Device::count();
        $unactivatedDevices = Device::unactivated()->count();
        $activeDevices = Device::active()->count();
        $inactiveDevices = Device::inactive()->count();
        $blockedDevices = Device::blocked()->count();
        $totalBusinesses = Business::count();

        $totalScans = Scan::count();
        $totalQrScans = Scan::qr()->count();
        $totalNfcScans = Scan::nfc()->count();

        // Recent Scans
        $recentScans = Scan::with(['device', 'business'])
            ->latest('scanned_at')
            ->take(8)
            ->get();

        // Recent Activations
        $recentActivations = Activation::with(['device', 'business', 'user'])
            ->latest('activated_at')
            ->take(5)
            ->get();

        // Last 14 days scans chart data
        $startDate = Carbon::now()->subDays(13)->startOfDay();
        $rawChartData = Scan::select(
            DB::raw('DATE(scanned_at) as scan_date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN scan_type = "qr" THEN 1 ELSE 0 END) as qr_count'),
            DB::raw('SUM(CASE WHEN scan_type = "nfc" THEN 1 ELSE 0 END) as nfc_count')
        )
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

        return view('admin.dashboard', [
            'totalDevices' => $totalDevices,
            'unactivatedDevices' => $unactivatedDevices,
            'activeDevices' => $activeDevices,
            'inactiveDevices' => $inactiveDevices,
            'blockedDevices' => $blockedDevices,
            'totalBusinesses' => $totalBusinesses,
            'totalScans' => $totalScans,
            'totalQrScans' => $totalQrScans,
            'totalNfcScans' => $totalNfcScans,
            'recentScans' => $recentScans,
            'recentActivations' => $recentActivations,
            'chartLabels' => $chartLabels,
            'chartQrData' => $chartQrData,
            'chartNfcData' => $chartNfcData,
            'chartTotalData' => $chartTotalData,
        ]);
    }
}
