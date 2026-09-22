<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BusinessController extends Controller
{
    /**
     * Display list of businesses
     */
    public function index(Request $request): View
    {
        $query = Business::with(['user', 'devices'])->withCount(['devices', 'scans']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $businesses = $query->latest()->paginate(12)->withQueryString();

        return view('admin.businesses.index', [
            'businesses' => $businesses,
            'search' => $request->search,
        ]);
    }

    /**
     * Show create business form
     */
    public function create(): View
    {
        $users = User::where('role', 'business_owner')->orderBy('name')->get();

        return view('admin.businesses.create', [
            'users' => $users,
        ]);
    }

    /**
     * Store new business
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'google_review_url' => ['required', 'url', 'max:1000'],
            'google_place_id' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'user_id.required' => 'Pemilik bisnis wajib dipilih.',
            'name.required' => 'Nama bisnis wajib diisi.',
            'google_review_url.required' => 'Link Google Review wajib diisi.',
            'google_review_url.url' => 'Format URL Google Review tidak valid.',
        ]);

        $slug = Str::slug($validated['name']);
        if (Business::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(5);
        }

        $business = Business::create([
            'user_id' => $validated['user_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'category' => $validated['category'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'google_review_url' => $validated['google_review_url'],
            'google_place_id' => $validated['google_place_id'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.businesses.show', $business)
            ->with('success', "Bisnis '{$business->name}' berhasil didaftarkan!");
    }

    /**
     * Show business details and connected devices
     */
    public function show(Business $business): View
    {
        $business->load(['user', 'devices.activations']);
        $totalScans = $business->scans()->count();
        $qrScans = $business->scans()->where('scan_type', 'qr')->count();
        $nfcScans = $business->scans()->where('scan_type', 'nfc')->count();

        return view('admin.businesses.show', [
            'business' => $business,
            'totalScans' => $totalScans,
            'qrScans' => $qrScans,
            'nfcScans' => $nfcScans,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit(Business $business): View
    {
        $users = User::where('role', 'business_owner')->orderBy('name')->get();

        return view('admin.businesses.edit', [
            'business' => $business,
            'users' => $users,
        ]);
    }

    /**
     * Update business
     */
    public function update(Request $request, Business $business): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'google_review_url' => ['required', 'url', 'max:1000'],
            'google_place_id' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $business->update($validated);

        return redirect()->route('admin.businesses.show', $business)
            ->with('success', "Data bisnis '{$business->name}' berhasil diperbarui!");
    }

    /**
     * Delete business
     */
    public function destroy(Business $business): RedirectResponse
    {
        $name = $business->name;
        $business->delete();

        return redirect()->route('admin.businesses.index')
            ->with('success', "Bisnis '{$name}' berhasil dihapus.");
    }
}
