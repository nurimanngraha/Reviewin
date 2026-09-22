<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Show settings page for business and review links
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $businesses = $user->businesses()->get();

        $selectedBusinessId = $request->query('business_id', $businesses->first()?->id);
        $selectedBusiness = $businesses->firstWhere('id', $selectedBusinessId) ?? $businesses->first();

        return view('portal.settings', [
            'user' => $user,
            'businesses' => $businesses,
            'selectedBusiness' => $selectedBusiness,
        ]);
    }

    /**
     * Update business information and Google Review URL
     */
    public function updateBusiness(Request $request, Business $business): RedirectResponse
    {
        if ($business->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'google_review_url' => ['required', 'url', 'max:1000'],
            'google_place_id' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama bisnis wajib diisi.',
            'google_review_url.required' => 'Link Google Review wajib diisi.',
            'google_review_url.url' => 'Format URL Google Review tidak valid.',
        ]);

        $business->update($validated);

        return back()->with('success', 'Pengaturan bisnis dan Link Google Review berhasil diperbarui!');
    }
}
