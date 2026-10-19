<?php

namespace App\Http\Controllers;

use App\Services\LocationNameMatcher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Port / destination name checks for the work order form.
 */
class WorkOrderLocationNameController extends Controller
{
    /**
     * Name lists + settings, loaded once by the form so every keystroke is checked instantly
     * in the browser (public/js/custom/location-name-matcher.js).
     */
    public function names(LocationNameMatcher $matcher): JsonResponse
    {
        return response()
            ->json($matcher->clientData())
            ->header('Cache-Control', 'private, max-age=300');
    }

    /**
     * Server-side check of one value. The form uses it to confirm the instant browser result
     * (and as a fallback before the lists have loaded); saving applies the same rule.
     */
    public function check(Request $request, LocationNameMatcher $matcher): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in([LocationNameMatcher::PORT, LocationNameMatcher::COUNTRY])],
            'value' => ['nullable', 'string', 'max:255'],
        ]);

        return response()->json($matcher->match($validated['type'], $validated['value'] ?? ''));
    }
}
