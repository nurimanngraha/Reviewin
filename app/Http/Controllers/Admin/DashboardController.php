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
    public function index(): View|\Illuminate\Http\Response
    {
        try {
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
                DB::raw("SUM(CASE WHEN scan_type = 'qr' THEN 1 ELSE 0 END) as qr_count"),
                DB::raw("SUM(CASE WHEN scan_type = 'nfc' THEN 1 ELSE 0 END) as nfc_count")
            )
                ->where('scanned_at', '>=', $startDate)
                ->groupBy(DB::raw('DATE(scanned_at)'))
                ->orderBy(DB::raw('DATE(scanned_at)'), 'asc')
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
        } catch (\Throwable $e) {
            $secret = substr(hash('sha256', config('app.key')), 0, 16);
            return response('<div style="background:#0f172a;color:#f8fafc;padding:30px;font-family:sans-serif;min-height:100vh;">' .
                '<h1 style="color:#f43f5e;margin-bottom:10px;">⚠️ Database Belum Lengkap / Terjadi Kesalahan</h1>' .
                '<p style="color:#cbd5e1;font-size:15px;">Dashboard mendeteksi masalah saat membaca database: <strong style="color:#fca5a5;">' . e($e->getMessage()) . '</strong></p>' .
                '<p style="color:#94a3b8;font-size:13px;line-height:1.6;">Hal ini biasanya terjadi jika tabel database di InfinityFree (seperti tabel devices, businesses, scans) belum ter-migrasi secara lengkap saat setup awal.</p>' .
                '<div style="margin-top:25px;display:flex;gap:15px;flex-wrap:wrap;">' .
                '<a href="' . url('/system/migrate?key=' . $secret . '&fresh=1') . '" onclick="return confirm(\'Lakukan migrasi fresh untuk melengkapi semua tabel dan akun admin?\')" style="display:inline-block;padding:12px 24px;background:#ef4444;color:white;text-decoration:none;border-radius:10px;font-weight:bold;">🔄 Jalankan Migrasi Fresh Sekarang &rarr;</a>' .
                '<a href="' . url('/system/logs?key=' . $secret) . '" style="display:inline-block;padding:12px 24px;background:#334155;color:white;text-decoration:none;border-radius:10px;font-weight:bold;">Lihat Detail Log Error</a>' .
                '</div></div>', 500);
        }
    }
}
