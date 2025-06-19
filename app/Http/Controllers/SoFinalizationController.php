<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SoFinalization;
use App\Models\So;
use Illuminate\Support\Facades\DB;

class SoFinalizationController extends Controller
{
    public function index()
    {
        $finalizedLinkedNumbers = SoFinalization::where('is_finalized', 1)
            ->pluck('linked_so_number')
            ->map(function ($val) {
                return (int) preg_replace('/[^0-9]/', '', preg_replace('/^SO[-_]?/i', '', $val));
            })
            ->toArray();

        $rawSOList = DB::table('so')
            ->whereNotNull('so_number')
            ->select('id', 'so_number')
            ->get();

        $grouped = $rawSOList->groupBy(function ($so) {
            return (int) preg_replace('/[^0-9]/', '', preg_replace('/^SO[-_]?/i', '', $so->so_number));
        });

        $duplicates = $grouped->filter(function ($group, $normalizedKey) use ($finalizedLinkedNumbers) {
            return count($group) > 1 && !in_array($normalizedKey, $finalizedLinkedNumbers);
        })->map(function ($group, $normalizedKey) {
            return (object) [
                'normalized' => $normalizedKey,
                'count' => $group->count(),
                'so_ids' => $group->pluck('id')->implode(','),
                'finalized_id' => $group->min('id'),
                'display_so_number' => $group->min('so_number'),
            ];
        })->values();

        return view('so_finalizations.index', compact('duplicates'));
    }


    public function store(Request $request)
    {


        $request->validate([
            'finalized_so_id' => 'required|exists:so,id',
            'removed_so_ids' => 'required|string',
            'linked_so_number' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        SoFinalization::create([
            'finalized_so_id' => $request->finalized_so_id,
            'removed_so_ids' => json_decode($request->removed_so_ids),
            'linked_so_number' => 'SO-' . ltrim(preg_replace('/^SO[-_]?/i', '', $request->linked_so_number)),
            'remarks' => $request->remarks,
            'is_finalized' => 1,
            'created_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('so_finalizations.index')
            ->with('success', 'SO with ' . $request->linked_so_number . ' has been finalized, successfully.');
    }

    public function edit($soNumber)
    {
        $normalizedKey = (int) preg_replace('/[^0-9]/', '', preg_replace('/^SO[-_]?/i', '', $soNumber));

        $soList = \App\Models\So::with([
            'quotation',
            'quotation.createdBy',
            'quotationDetail.country',
            'quotationDetail.shippingPort',
            'quotationDetail.shippingPortOfLoad',
            'quotationDetail.paymentterms',
            'call',
            'empProfile',
            'vehicles.variant',
            'vehicles.purchasingOrder',
            'vehicles.interior',
            'vehicles.exterior',
        ])
            ->whereNotNull('so_number')
            ->get()
            ->filter(function ($so) use ($normalizedKey) {
                $normalized = (int) preg_replace('/[^0-9]/', '', preg_replace('/^SO[-_]?/i', '', $so->so_number));
                return $normalized === $normalizedKey;
            });

        if ($soList->isEmpty()) {
            abort(404, 'No SOs found for normalized: ' . $soNumber);
        }

        return view('so_finalizations.edit', [
            'soList' => $soList,
            'displaySoNumber' => $soNumber
        ]);
    }
}
