<?php

namespace App\Services;

use App\Models\Activation;
use App\Models\Business;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeviceActivationService
{
    /**
     * Activate a device for a business owner
     */
    public function activate(Device $device, User $user, array $data, Request $request): Device
    {
        if ($device->status !== 'unactivated') {
            throw new \RuntimeException('Perangkat ini sudah pernah diaktivasi atau tidak dalam status unactivated.');
        }

        // Validate Activation Code (Kode Kartu) if device has one
        if (!empty($device->activation_code)) {
            $inputCode = strtoupper(trim($data['activation_code'] ?? ''));
            $expectedCode = strtoupper(trim($device->activation_code));

            if ($inputCode !== $expectedCode) {
                throw new \InvalidArgumentException('Kode Kartu (Activation Code) tidak sesuai dengan kartu fisik perangkat ini.');
            }
        }

        return DB::transaction(function () use ($device, $user, $data, $request) {
            $business = null;
            $placeId = trim($data['google_place_id'] ?? '');
            $generatedReviewUrl = !empty($placeId) 
                ? 'https://search.google.com/local/writereview?placeid=' . urlencode($placeId)
                : ($data['google_review_url'] ?? null);

            // Option 1: Existing Business selected
            if (!empty($data['business_id'])) {
                $business = Business::where('id', $data['business_id'])
                    ->where('user_id', $user->id)
                    ->firstOrFail();

                $updateData = [];
                if (!empty($placeId)) {
                    $updateData['google_place_id'] = $placeId;
                    $updateData['google_review_url'] = $generatedReviewUrl;
                } elseif (!empty($data['google_review_url'])) {
                    $updateData['google_review_url'] = $data['google_review_url'];
                }

                if (!empty($updateData)) {
                    $business->update($updateData);
                }
            } else {
                // Option 2: Create new business on the fly
                $slug = Str::slug($data['business_name']);
                if (Business::where('slug', $slug)->exists()) {
                    $slug .= '-' . Str::random(5);
                }

                $business = Business::create([
                    'user_id' => $user->id,
                    'name' => $data['business_name'],
                    'slug' => $slug,
                    'category' => $data['category'] ?? null,
                    'address' => $data['address'] ?? null,
                    'phone' => $data['phone'] ?? $user->phone,
                    'email' => $data['email'] ?? $user->email,
                    'google_place_id' => $placeId ?: null,
                    'google_review_url' => $generatedReviewUrl,
                ]);
            }

            // Update Device details to ACTIVE
            $device->update([
                'business_id' => $business->id,
                'name' => !empty($data['device_label']) ? $data['device_label'] : ($device->name ?: $business->name . ' - Device'),
                'status' => 'active',
                'activated_at' => now(),
            ]);

            // Record Activation History
            Activation::create([
                'device_id' => $device->id,
                'business_id' => $business->id,
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => substr($request->userAgent() ?? '', 0, 500),
                'notes' => $data['notes'] ?? 'Aktivasi mandiri dengan validasi Kode Kartu & Google Place ID.',
                'activated_at' => now(),
            ]);

            return $device->fresh(['business']);
        });
    }

    /**
     * Admin reset device to unactivated state
     */
    public function resetDevice(Device $device, string $reason = 'Reset oleh Administrator'): void
    {
        DB::transaction(function () use ($device, $reason) {
            $device->update([
                'status' => 'unactivated',
                'business_id' => null,
                'activated_at' => null,
                'notes' => ($device->notes ? $device->notes . "\n" : '') . '[' . now()->toDateTimeString() . '] ' . $reason,
            ]);
        });
    }
}
