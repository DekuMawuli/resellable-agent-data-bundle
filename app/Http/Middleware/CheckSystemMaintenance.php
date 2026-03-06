<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = optional($request->route())->getName() ?? "";

        // Always allow authenticated admins to pass through.
        if (Auth::check() && Auth::user()?->role === "admin") {
            return $next($request);
        }

        // Allow Livewire transport endpoints so admin toggles still persist.
        if ($request->is("livewire/*")) {
            return $next($request);
        }

        // Let admin area and login flow pass so maintenance can be managed.
        if (
            str_starts_with($routeName, "root.") ||
            in_array($routeName, ["pages.login", "pages.processLogin"], true)
        ) {
            return $next($request);
        }

        $setting = Setting::query()->first();

        if ($setting && $setting->maintenance_mode) {
            return response()->view("pages.maintenance", [
                "message" => blank($setting->maintenance_message)
                    ? "We are undergoing maintenance. Please check back shortly."
                    : $setting->maintenance_message,
            ], 503);
        }

        return $next($request);
    }
}
