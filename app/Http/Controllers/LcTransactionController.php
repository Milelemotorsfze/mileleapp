<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationLcDetail;
use Illuminate\Http\Request;

/**
 * Dedicated Letter of Credit transaction view.
 *
 * Read-only over the quotation module: it reports the LC requirement matrix and
 * flags every transaction whose documentation is incomplete, so shipment is not
 * released before the checklist and compliance status are cleared.
 */
class LcTransactionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');

        $quotations = Quotation::query()
            ->active()
            ->where('nature_of_deal', 'letter_of_credit')
            ->with(['call', 'lcDetail', 'createdBy'])
            ->orderByDesc('id')
            ->get();

        // Every LC quotation gets a detail object so the matrix is never blank.
        $rows = $quotations->map(function (Quotation $quotation) {
            $lcDetail = $quotation->lcDetail ?: new QuotationLcDetail([
                'quotation_id' => $quotation->id,
                'compliance_status' => 'pending',
            ]);

            return [
                'quotation' => $quotation,
                'lc' => $lcDetail,
                'blockers' => $lcDetail->shipmentBlockers(),
                'can_ship' => $lcDetail->canProceedToShipment(),
                'days_to_expiry' => $lcDetail->daysToExpiry(),
            ];
        });

        $summary = [
            'total' => $rows->count(),
            'cleared' => $rows->where('can_ship', true)->count(),
            'blocked' => $rows->where('can_ship', false)->count(),
            'expired' => $rows->filter(fn ($row) => $row['lc']->isExpired())->count(),
            'expiring_soon' => $rows->filter(function ($row) {
                $days = $row['days_to_expiry'];

                return $days !== null && $days >= 0 && $days <= 14;
            })->count(),
        ];

        $rows = match ($filter) {
            'blocked' => $rows->where('can_ship', false),
            'cleared' => $rows->where('can_ship', true),
            'expired' => $rows->filter(fn ($row) => $row['lc']->isExpired()),
            'expiring_soon' => $rows->filter(function ($row) {
                $days = $row['days_to_expiry'];

                return $days !== null && $days >= 0 && $days <= 14;
            }),
            default => $rows,
        };

        return view('lc.index', [
            'rows' => $rows->values(),
            'summary' => $summary,
            'filter' => $filter,
            'documents' => QuotationLcDetail::DOCUMENTS,
        ]);
    }

    /**
     * Shipment gate. Returns whether the LC documentation for a quotation is
     * complete, plus the outstanding blockers when it is not.
     */
    public function shipmentClearance($quotationId)
    {
        $quotation = Quotation::query()->active()->findOrFail($quotationId);

        if (! $quotation->isLetterOfCredit()) {
            return response()->json([
                'letter_of_credit' => false,
                'can_proceed' => true,
                'blockers' => [],
            ]);
        }

        $lcDetail = $quotation->lcDetail ?: new QuotationLcDetail([
            'quotation_id' => $quotation->id,
            'compliance_status' => 'pending',
        ]);

        return response()->json([
            'letter_of_credit' => true,
            'can_proceed' => $lcDetail->canProceedToShipment(),
            'blockers' => $lcDetail->shipmentBlockers(),
            'missing_documents' => $lcDetail->missingDocuments(),
            'compliance_status' => $lcDetail->complianceStatusLabel(),
        ]);
    }
}
