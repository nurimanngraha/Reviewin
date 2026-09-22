<?php

namespace App\Http\Controllers;

use App\Services\DeviceRedirectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RedirectController extends Controller
{
    public function __construct(
        protected DeviceRedirectService $redirectService
    ) {}

    /**
     * Handle public dynamic device scan/tap
     */
    public function handle(string $code, Request $request): RedirectResponse|Response|View
    {
        return $this->redirectService->handle($code, $request);
    }
}
