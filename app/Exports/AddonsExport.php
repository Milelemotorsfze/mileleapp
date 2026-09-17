<?php

namespace App\Exports;

use App\Models\AddonDetails;
use App\Models\AddonTypes;
use App\Models\SupplierAddons;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AddonsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected const TYPES = ['P' => 'Accessories', 'SP' => 'Spare Parts', 'K' => 'Kit'];

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $addons;

    /**
     * Lookups for kit purchase prices, built once instead of per kit item.
     */
    protected $kitLookups;

    /**
     * Headings of the columns the user is not allowed to see.
     */
    protected $hiddenColumns;

    public function __construct(Collection $addons, array $hiddenColumns = [])
    {
        $this->addons = $addons;
        $this->hiddenColumns = $hiddenColumns;
    }

    public function collection(): Collection
    {
        return $this->addons;
    }

    public function headings(): array
    {
        return array_keys($this->visibleColumns(array_flip(self::COLUMNS)));
    }

    /**
     * @param \App\Models\AddonDetails $addon
     */
    public function map($addon): array
    {
        return array_values($this->visibleColumns($this->row($addon)));
    }

    protected const COLUMNS = [
        'Addon Code',
        'Addon Type',
        'Addon Name',
        'Description',
        'Part Numbers',
        'Brand / Model Lines',
        'Model Year Start',
        'Model Year End',
        'Least Purchase Price (AED)',
        'Selling Price (AED)',
        'Suppliers',
        'Fixing Charges Included',
        'Fixing Charge Amount (AED)',
        'Payment Condition',
        'Status',
        'Additional Remarks',
        'Created At',
        'Updated At',
    ];

    protected function visibleColumns(array $row): array
    {
        return array_diff_key($row, array_flip($this->hiddenColumns));
    }

    protected function row($addon): array
    {
        $row = array_fill_keys(self::COLUMNS, null);
        $activeSuppliers = $addon->AddonSuppliers->where('status', 'active');

        $purchasePrice = '';
        if (!in_array('Least Purchase Price (AED)', $this->hiddenColumns)) {
            if ($addon->addon_type_name == 'K') {
                $kitPrices = $addon->KitItems->map(fn ($item) => $this->kitItemTotalPurchasePrice($item));
                $purchasePrice = ($kitPrices->isNotEmpty() && !$kitPrices->contains(fn ($p) => $p == 0)) ? $kitPrices->sum() : '';
            } else {
                $purchasePrice = $activeSuppliers->min('purchase_price_aed') ?? '';
            }
        }

        return array_merge($row, [
            'Addon Code' => $addon->addon_code,
            'Addon Type' => self::TYPES[$addon->addon_type_name] ?? $addon->addon_type_name,
            'Addon Name' => optional($addon->AddonName)->name,
            'Description' => optional($addon->AddonDescription)->description,
            'Part Numbers' => $addon->partNumbers->pluck('part_number')->filter()->implode(', '),
            'Brand / Model Lines' => $this->brandsAndModels($addon),
            'Model Year Start' => $addon->model_year_start,
            'Model Year End' => $addon->model_year_end,
            'Least Purchase Price (AED)' => $purchasePrice,
            'Selling Price (AED)' => optional($addon->SellingPrice)->selling_price,
            'Suppliers' => $activeSuppliers->map(fn ($s) => optional($s->Suppliers)->supplier)->filter()->unique()->implode(', '),
            'Fixing Charges Included' => $addon->fixing_charges_included,
            'Fixing Charge Amount (AED)' => $addon->fixing_charge_amount,
            'Payment Condition' => $addon->payment_condition,
            'Status' => $addon->status,
            'Additional Remarks' => $addon->additional_remarks,
            'Created At' => optional($addon->created_at)->format('d-m-Y H:i'),
            'Updated At' => optional($addon->updated_at)->format('d-m-Y H:i'),
        ]);
    }

    protected function brandsAndModels($addon): string
    {
        if ($addon->is_all_brands == 'yes') {
            return 'All Brands';
        }

        return $addon->AddonTypes->groupBy('brand_id')->map(function ($types) {
            $brand = optional($types->first()->brands)->brand_name ?? '-';
            if ($types->contains('is_all_model_lines', 'yes')) {
                return $brand . ' (All Model Lines)';
            }
            $modelLines = $types->map(fn ($t) => optional($t->modelLines)->model_line)->filter()->unique()->implode(', ');
            return $modelLines ? $brand . ' (' . $modelLines . ')' : $brand;
        })->implode('; ');
    }

    /**
     * Same result as KitCommonItem::getKitItemTotalPurchasePriceAttribute(), without the per-item queries.
     */
    protected function kitItemTotalPurchasePrice($item)
    {
        if (!$this->kitLookups) {
            $types = AddonTypes::whereNotNull('model_number')->get(['addon_details_id', 'model_number']);
            $prices = SupplierAddons::where('status', 'active')->get(['addon_details_id', 'purchase_price_aed'])->groupBy('addon_details_id');
            $this->kitLookups = [
                'spByDescription' => AddonDetails::where('addon_type_name', 'SP')->get(['id', 'description'])->groupBy('description')->map->pluck('id'),
                'modelNumbersByAddon' => $types->groupBy('addon_details_id')->map->pluck('model_number'),
                'addonsByModelNumber' => $types->groupBy('model_number')->map->pluck('addon_details_id'),
                // the accessor orders by price ASC and takes the first row, so a null price wins
                'leastPrice' => $prices->map(fn ($rows) => $rows->contains(fn ($r) => $r->purchase_price_aed === null) ? null : $rows->min('purchase_price_aed')),
            ];
        }
        $lookups = $this->kitLookups;

        $spIds = $lookups['spByDescription'][$item->item_id] ?? collect();
        $sameModelAddonIds = ($lookups['modelNumbersByAddon'][$item->addon_details_id] ?? collect())->unique()
            ->flatMap(fn ($modelNumber) => $lookups['addonsByModelNumber'][$modelNumber] ?? []);
        $prices = $spIds->intersect($sameModelAddonIds)->unique()
            ->filter(fn ($id) => $lookups['leastPrice']->has($id))
            ->map(fn ($id) => $lookups['leastPrice'][$id]);

        if ($prices->isEmpty() || $prices->contains(null)) {
            return 0;
        }
        return $item->quantity * $prices->min();
    }
}
