<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    /**
     * Checklist column => label. Order drives the UI and the LC transaction view.
     */
    public const DOCUMENTS = [
        'doc_commercial_invoice' => 'Commercial Invoice',
        'doc_bill_of_lading' => 'Bill of Lading',
        'doc_packing_list' => 'Packing List',
        'doc_certificate_of_origin' => 'Certificate of Origin (COO)',
        'doc_inspection_certificate' => 'Inspection Certificate',
    ];

    public const COMPLIANCE_STATUSES = [
        'pending' => 'Pending',
        'under_review' => 'Under Review',
        'compliant' => 'Compliant',
        'discrepant' => 'Discrepant',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    /**
     * Checklist entries that have not been received yet.
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
     * @return array<int, string>
     */
    public function shipmentBlockers(): array
    {
        $blockers = [];

        foreach ($this->missingLcTerms() as $term) {
            $blockers[] = $term.' is not recorded';
        }

        foreach ($this->missingDocuments() as $document) {
            $blockers[] = $document.' not received';
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
