<?php

namespace App\Services;

use App\Models\Calls;
use App\Models\QuotationDetail;
use App\Models\QuotationItem;
use App\Models\QuotationVins;
use App\Models\So;
use App\Models\User;
use App\Models\Soitems;
use App\Models\SoVariant;
use App\Models\Vehicles;
use App\Support\OrderStockType;
use App\Support\QuotationLeadResolver;
use Illuminate\Support\Collection;

class WorkOrderSoDataService
{
    public function findActiveSoByNumber(string $soNumber): ?So
    {
        $soNumber = trim($soNumber);
        if ($soNumber === '') {
            return null;
        }

        return So::query()
            ->where('so_number', $soNumber)
            ->where(function ($query) {
                $query->where('status', '!=', 'Cancelled')
                    ->orWhereNull('status');
            })
            ->first();
    }

    public function buildWorkOrderPayload(So $so): array
    {
        $so->loadMissing(['salesperson:id,name,email', 'quotation']);

        $quotation = $so->quotation;
        $calls = QuotationLeadResolver::resolve($quotation);

        $quotationDetail = $quotation
            ? QuotationDetail::with(['country', 'shippingPort', 'shippingPortOfLoad', 'paymentterms'])
                ->where('quotation_id', $quotation->id)
                ->first()
            : null;

        $customer = $this->resolveCustomerFields($calls, $quotationDetail);

        $salesPersonId = $so->sales_person_id ?? $quotation?->created_by;
        $salesPersonName = $so->salesperson?->name;
        if (!$salesPersonName && $salesPersonId) {
            $salesPersonName = User::where('id', $salesPersonId)->value('name');
        }

        $total = $this->toFloat($so->total);
        $amountReceived = $this->toFloat($so->paidinso) + $this->toFloat($so->paidinperforma);
        $stockType = OrderStockType::normalize($so->stock_type);

        $vehicleIds = $this->resolveSoVehicleIds($so);
        $vehicles = $vehicleIds->isEmpty()
            ? collect()
            : Vehicles::withTrashed()
                ->select('id', 'vin', 'engine', 'varaints_id', 'int_colour', 'ex_colour', 'latest_location', 'territory', 'documents_id', 'purchasing_order_id')
                ->whereIn('id', $vehicleIds)
                ->whereNotNull('vin')
                ->with([
                    'variant.master_model_lines.brand',
                    'interior',
                    'exterior',
                    'warehouseLocation',
                    'document',
                    'purchasingOrder.podPort',
                    'purchasingOrder.polPort',
                    'purchasingOrder.fdCountry',
                ])
                ->get()
                ->unique('vin')
                ->values();

        $vehicleQuantity = $vehicles->count();
        if ($vehicleQuantity === 0 && $quotation) {
            $vehicleQuantity = (int) QuotationItem::where('quotation_id', $quotation->id)->sum('quantity');
        }

        return [
            'so_id' => $so->id,
            'so_number' => $so->so_number,
            'so_date' => $so->so_date,
            'so_notes' => $so->notes,
            'so_status' => $so->status,
            'stock_type' => $stockType,
            'cross_trade' => $stockType === OrderStockType::CROSS_TRADE,
            'sales_person_id' => $salesPersonId,
            'sales_person_name' => $salesPersonName,
            'customer_name' => $customer['customer_name'],
            'customer_email' => $customer['customer_email'],
            'customer_company_number' => $customer['customer_company_number'],
            'customer_address' => $customer['customer_address'],
            'customer_representative_name' => $quotationDetail?->representative_name,
            'customer_representative_contact' => $this->normalizePhone($quotationDetail?->representative_number),
            'port_of_loading' => $quotationDetail?->shippingPortOfLoad?->name,
            'port_of_discharge' => $quotationDetail?->shippingPort?->name,
            'final_destination' => $quotationDetail?->country?->name
                ?? $quotationDetail?->final_destination,
            'delivery_location' => $quotationDetail?->place_of_delivery
                ?? $quotationDetail?->place_of_supply,
            'currency' => $quotation?->currency ?? 'AED',
            'so_total_amount' => $total > 0 ? $total : null,
            'so_vehicle_quantity' => $vehicleQuantity > 0 ? $vehicleQuantity : null,
            'amount_received' => $amountReceived > 0 ? $amountReceived : null,
            'balance_amount' => ($total > 0 && $amountReceived > 0) ? max(0, $total - $amountReceived) : null,
            'incoterm' => $quotationDetail?->incoterm,
            'payment_terms' => $quotationDetail?->paymentterms?->name,
            'quotation_document_type' => $quotation?->document_type,
            'consignee' => $customer['customer_name'],
            'vins' => $vehicles->map(fn (Vehicles $vehicle) => $this->formatVehicleRow($vehicle))->values()->all(),
        ];
    }

    /**
     * Resolve vehicle IDs linked to an SO via vehicles.so_id and soitems/so_variants.
     */
    private function resolveSoVehicleIds(So $so): Collection
    {
        $so->loadMissing('quotation');

        $soVariantIds = SoVariant::withTrashed()
            ->where('so_id', $so->id)
            ->pluck('id');

        $quotationItemIds = $so->quotation_id
            ? QuotationItem::where('quotation_id', $so->quotation_id)->pluck('id')
            : collect();

        $vehicleIds = collect()
            ->merge(Vehicles::withTrashed()->where('so_id', $so->id)->pluck('id'))
            ->merge(
                Soitems::whereIn('so_variant_id', $soVariantIds)
                    ->whereNotNull('vehicles_id')
                    ->pluck('vehicles_id')
            )
            ->merge(
                Soitems::where('so_id', $so->id)
                    ->whereNotNull('vehicles_id')
                    ->pluck('vehicles_id')
            );

        if ($quotationItemIds->isNotEmpty()) {
            $vehicleIds = $vehicleIds->merge(
                Soitems::whereIn('quotation_items_id', $quotationItemIds)
                    ->whereNotNull('vehicles_id')
                    ->pluck('vehicles_id')
            );

            $quotationVinValues = QuotationVins::whereIn('quotation_items_id', $quotationItemIds)
                ->whereNotNull('vin')
                ->pluck('vin')
                ->map(fn ($vin) => strtolower(trim((string) $vin)))
                ->filter()
                ->unique()
                ->values();

            if ($quotationVinValues->isNotEmpty()) {
                $vehicleIds = $vehicleIds->merge(
                    Vehicles::withTrashed()
                        ->whereNotNull('vin')
                        ->where(function ($query) use ($quotationVinValues) {
                            foreach ($quotationVinValues as $vin) {
                                $query->orWhereRaw('LOWER(TRIM(vin)) = ?', [$vin]);
                            }
                        })
                        ->pluck('id')
                );
            }
        }

        return $vehicleIds->filter()->unique()->values();
    }

    private function formatVehicleRow(Vehicles $vehicle): array
    {
        $variant = $vehicle->variant;
        $preferredDestination = $vehicle->purchasingOrder?->fdCountry?->name
            ?? $vehicle->purchasingOrder?->podPort?->name
            ?? '';

        return [
            'id' => '',
            'vehicle_id' => $vehicle->id,
            'vin' => $vehicle->vin ?? '',
            'brand' => $variant?->master_model_lines?->brand?->brand_name ?? '',
            'variant' => $variant?->name ?? '',
            'engine' => $vehicle->engine ?? '',
            'model_description' => $variant?->model_detail ?? '',
            'model_year' => $variant?->my ?? '',
            'model_year_to_mention_on_documents' => $variant?->my ?? '',
            'steering' => $variant?->steering ?? '',
            'exterior_colour' => $vehicle->exterior?->name ?? '',
            'interior_colour' => $vehicle->interior?->name ?? '',
            'warehouse' => $vehicle->warehouseLocation?->name ?? '',
            'territory' => $vehicle->territory ?? '',
            'preferred_destination' => $preferredDestination,
            'import_document_type' => $vehicle->document?->import_type ?? '',
            'ownership_name' => $vehicle->document?->owership ?? '',
            'certification_per_vin' => '',
            'modification_or_jobs_to_perform_per_vin' => '',
            'special_request_or_remarks' => '',
            'shipment' => '',
        ];
    }

    private function toFloat(mixed $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        return (float) preg_replace('/[^\d.-]/', '', (string) $value);
    }

    /**
     * @return array{customer_name: ?string, customer_email: ?string, customer_company_number: ?string, customer_address: ?string}
     */
    private function resolveCustomerFields(?Calls $call, ?QuotationDetail $quotationDetail): array
    {
        $name = $call ? trim((string) ($call->company_name ?: $call->name)) : '';
        if ($name === '' && $quotationDetail?->representative_name) {
            $name = trim((string) $quotationDetail->representative_name);
        }

        $email = $call?->email ? trim((string) $call->email) : null;

        $phone = $call?->phone ? $this->normalizePhone($call->phone) : null;
        if (!$phone && $quotationDetail?->representative_number) {
            $phone = $this->normalizePhone($quotationDetail->representative_number);
        }

        $address = $call?->address ? trim((string) $call->address) : null;

        return [
            'customer_name' => $name !== '' ? $name : null,
            'customer_email' => $email !== '' ? $email : null,
            'customer_company_number' => $phone,
            'customer_address' => $address !== '' ? $address : null,
        ];
    }

    private function normalizePhone(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $phone = trim($phone);
        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        $digits = preg_replace('/\D/', '', $phone);
        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '971')) {
            return '+' . $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '+971' . substr($digits, 1);
        }

        return '+' . $digits;
    }
}
