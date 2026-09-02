<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Letter of Credit transaction requirements captured against a quotation.
 * Only used when Quotation::nature_of_deal === 'letter_of_credit'.
 */
class QuotationLcDetail extends Model
{
    use HasFactory;

    protected $table = 'quotation_lc_details';

    protected $fillable = [
        'quotation_id',
        'lc_number',
        'issuing_bank',
        'lc_expiry_date',
        'doc_commercial_invoice',
        'doc_bill_of_lading',
        'doc_packing_list',
        'doc_certificate_of_origin',
        'doc_inspection_certificate',
        'doc_others',
        'doc_others_details',
        'compliance_status',
        'compliance_remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'lc_expiry_date' => 'date',
        'doc_commercial_invoice' => 'boolean',
        'doc_bill_of_lading' => 'boolean',
        'doc_packing_list' => 'boolean',
        'doc_certificate_of_origin' => 'boolean',
        'doc_inspection_certificate' => 'boolean',
        'doc_others' => 'boolean',
    ];

    /**
     * Checklist column => label. Order drives the UI and the LC transaction view.
     * The checklist is advisory: ticking it is optional and never blocks a shipment.
     */
    public const DOCUMENTS = [
        'doc_commercial_invoice' => 'Commercial Invoice',
        'doc_bill_of_lading' => 'Bill of Lading',
        'doc_packing_list' => 'Packing List',
        'doc_certificate_of_origin' => 'Certificate of Origin (COO)',
        'doc_inspection_certificate' => 'Inspection Certificate',
        'doc_others' => 'Others',
    ];

    public const COMPLIANCE_STATUSES = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'bank_processing' => 'Bank Processing',
        'under_review' => 'Under Review',
        'compliant' => 'Compliant',
        'discrepant' => 'Discrepant',
    ];

    /**
     * Value used by the issuing-bank dropdown when the bank is not on the list.
     */
    public const OTHER_BANK = '__other__';

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    /**
     * Issuing banks offered in the dropdown: the bank master plus any issuing
     * bank already captured on an LC, so the list grows with real usage. An LC
     * can be issued by any overseas bank, so the form still allows a free-text
     * entry through the "Other" option.
     *
     * @return array<int, string>
     */
    public static function issuingBankOptions(): array
    {
        $fromMaster = DB::table('bank_master')->pluck('bank_name');
        $fromLcs = static::query()
            ->whereNotNull('issuing_bank')
            ->distinct()
            ->pluck('issuing_bank');

        return $fromMaster->merge($fromLcs)
            ->map(fn ($name) => trim((string) $name))
            ->filter()
            ->unique(fn ($name) => mb_strtolower($name))
            ->sort(SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();
    }

    /**
     * Checklist entries that have not been ticked yet.
     *
     * Advisory only: the checklist is optional, so these are reported for
     * visibility and never prevent a shipment from being cleared.
     *
     * @return array<int, string> Document labels
     */
    public function missingDocuments(): array
    {
        $missing = [];
        foreach (self::DOCUMENTS as $column => $label) {
            if (! $this->{$column}) {
                $missing[] = $label;
            }
        }

        return $missing;
    }

    public function isDocumentationComplete(): bool
    {
        return $this->missingDocuments() === [];
    }

    public function receivedDocumentCount(): int
    {
        return count(self::DOCUMENTS) - count($this->missingDocuments());
    }

    /**
     * Core LC terms that must be recorded before the transaction is workable.
     *
     * @return array<int, string>
     */
    public function missingLcTerms(): array
    {
        $missing = [];
        if (blank($this->lc_number)) {
            $missing[] = 'LC Number';
        }
        if (blank($this->issuing_bank)) {
            $missing[] = 'Issuing Bank';
        }
        if (blank($this->lc_expiry_date)) {
            $missing[] = 'LC Expiry Date';
        }

        return $missing;
    }

    public function isExpired(): bool
    {
        return $this->lc_expiry_date !== null
            && $this->lc_expiry_date->endOfDay()->isPast();
    }

    public function daysToExpiry(): ?int
    {
        if ($this->lc_expiry_date === null) {
            return null;
        }

        return Carbon::today()->diffInDays($this->lc_expiry_date->startOfDay(), false);
    }

    /**
     * Every reason the shipment must not be released yet.
     *
     * The document checklist is deliberately excluded: it is optional, so an
     * unticked document is surfaced through missingDocuments() as an advisory
     * rather than held here as a hard blocker.
     *
     * @return array<int, string>
     */
    public function shipmentBlockers(): array
    {
        $blockers = [];

        foreach ($this->missingLcTerms() as $term) {
            $blockers[] = $term.' is not recorded';
        }

        if ($this->isExpired()) {
            $blockers[] = 'LC expired on '.$this->lc_expiry_date->format('d-M-Y');
        }

        if ($this->compliance_status !== 'compliant') {
            $blockers[] = 'Compliance status is '.$this->complianceStatusLabel();
        }

        return $blockers;
    }

    public function canProceedToShipment(): bool
    {
        return $this->shipmentBlockers() === [];
    }

    public function complianceStatusLabel(): string
    {
        return self::COMPLIANCE_STATUSES[$this->compliance_status] ?? 'Pending';
    }
}
