<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationDetail;
use App\Models\QuotationLcDetail;
use App\Models\So;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        if (! $user || ! $user->canAccessLcTransactions()) {
            return redirect()->route('not_access_page');
        }

        $filter = $request->input('filter', 'all');

        $quotations = Quotation::query()
            ->active()
            ->where('nature_of_deal', 'letter_of_credit')
            ->with(['call', 'lcDetail', 'createdBy'])
            ->unless($user->canViewAllLcTransactions(), fn ($query) => $this->scopeToOwnRecords($query, $user))
            ->orderByDesc('id')
            ->get();

        // PFI details live in quotation_details; pull them in one query, not per row.
        $details = QuotationDetail::with(['country', 'shippingPort', 'shippingPortOfLoad'])
            ->whereIn('quotation_id', $quotations->pluck('id'))
            ->get()
            ->keyBy('quotation_id');

        $salesOrders = So::query()
            ->whereIn('quotation_id', $quotations->pluck('id'))
            ->orderByDesc('id')
            ->get()
            ->keyBy('quotation_id');

        // Every LC quotation gets a detail object so the matrix is never blank.
        $rows = $quotations->map(function (Quotation $quotation) use ($details, $salesOrders) {
            $lcDetail = $quotation->lcDetail ?: new QuotationLcDetail([
                'quotation_id' => $quotation->id,
                'compliance_status' => 'pending',
            ]);

            $detail = $details->get($quotation->id);
            $call = $quotation->call;
            $documentDate = $quotation->date ? Carbon::parse($quotation->date) : null;
            $validityDays = $detail && $detail->document_validity !== null
                ? (int) $detail->document_validity
                : null;

            return [
                'quotation' => $quotation,
                'lc' => $lcDetail,
                'blockers' => $lcDetail->shipmentBlockers(),
                'can_ship' => $lcDetail->canProceedToShipment(),
                'days_to_expiry' => $lcDetail->daysToExpiry(),
                // Advisory only - the checklist is optional and never blocks a shipment.
                'documents_not_ticked' => $lcDetail->missingDocuments(),
                'documents_received' => $lcDetail->receivedDocumentCount(),

                // PFI document identity - document_number is the quotation id, the
                // same value the proforma invoice PDF prints.
                'pfi_number' => $quotation->id,
                'document_type' => $quotation->document_type,
                'document_date' => $documentDate,
                'validity_days' => $validityDays,
                'valid_until' => $documentDate && $validityDays !== null
                    ? $documentDate->copy()->addDays($validityDays)
                    : null,

                // Client, straight off the lead record the PFI is raised against.
                'client_company' => $call->company_name ?? null,
                'client_name' => $call->name ?? null,
                'client_contact_person' => $call->client_contact_person ?? null,
                'client_phone' => $call->phone ?? null,

                // Delivery, as printed on the PFI.
                'destination_country' => $detail->country->name ?? null,
                'destination_port' => $detail->shippingPort->name ?? null,
                'port_of_loading' => $detail->shippingPortOfLoad->name ?? null,
                'incoterm' => $detail->incoterm ?? null,
                'place_of_supply' => $detail->place_of_supply ?? null,
                'category' => $quotation->shipping_method === 'CNF' ? 'Local' : 'Export',

                // Commercial terms. payment_terms is free text on quotation_details,
                // not a foreign key, so it is shown exactly as captured.
                'payment_terms' => $detail->payment_terms ?? null,
                'advance_amount' => $detail->advance_amount ?? null,
                'payment_due_date' => $detail && $detail->due_date
                    ? Carbon::parse($detail->due_date)
                    : null,
                'bank' => $this->bankLabel($detail->selected_bank ?? null),

                'sales_person' => $quotation->createdBy->name ?? null,
                'sales_order' => $salesOrders->get($quotation->id),
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
            // Short column headers; the full label stays on the th title attribute.
            'documentHeadings' => ['Invoice', 'BL', 'Packing', 'COO', 'Inspection', 'Others'],
        ]);
    }

    /**
     * Mirrors ProformaInvoiceController::userCanAccessProforma() as a query scope:
     * the quotation's creator, the lead's assigned rep, or the lead's creator.
     */
    private function scopeToOwnRecords(Builder $query, User $user): Builder
    {
        return $query->where(function (Builder $scoped) use ($user) {
            $scoped->where('quotations.created_by', $user->id)
                ->orWhereHas('call', function (Builder $call) use ($user) {
                    $call->where('sales_person', $user->id)
                        ->orWhere('created_by', $user->id);
                });
        });
    }

    /**
     * quotation_details.selected_bank holds a slug ("rak-usd"). Render the same
     * label the proforma invoice form and PDF use.
     */
    private function bankLabel(?string $slug): ?string
    {
        if (blank($slug)) {
            return null;
        }

        $banks = [
            'rak' => 'RAK BANK',
            'hbz' => 'HBZ BANK',
            'city' => 'CITY BANK',
        ];

        $parts = explode('-', $slug);
        $bank = $banks[$parts[0]] ?? strtoupper($parts[0]);
        $currency = isset($parts[1]) ? ' '.strtoupper($parts[1]) : '';

        return $bank.$currency;
    }

    /**
     * Shipment gate. Returns whether the LC documentation for a quotation is
     * complete, plus the outstanding blockers when it is not.
     */
    public function shipmentClearance($quotationId)
    {
        $user = Auth::user();
        if (! $user || ! $user->canAccessLcTransactions()) {
            return response()->json(['message' => 'Not authorised.'], 403);
        }

        $quotation = Quotation::query()
            ->active()
            ->unless($user->canViewAllLcTransactions(), fn ($query) => $this->scopeToOwnRecords($query, $user))
            ->findOrFail($quotationId);

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
