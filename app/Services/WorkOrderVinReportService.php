<?php

namespace App\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * VIN report: one row per VIN linked to a Work Order.
 *
 * Data flow: VIN (w_o_vehicles) -> Work Order -> Sales Order (-> quotation incoterm)
 *            VIN -> Vehicle / Stock record -> Purchase Order (preferred destination) / GRN
 */
class WorkOrderVinReportService
{
    /** Report columns in display order => export heading. */
    public const COLUMNS = [
        'vin' => 'Chassis / VIN Number',
        'wo_number' => 'WO Number',
        'so_number' => 'SO Number',
        'pol' => 'POL (Port of Loading)',
        'pod' => 'POD (Port of Discharge)',
        'actual_destination' => 'Actual Destination',
        'preferred_destination' => 'Preferred Destination',
        'sales_person' => 'Salesperson Name',
        'wo_status' => 'WO Status',
        'delivery_status' => 'Delivery Status',
        'brand' => 'Brand',
        'model_line' => 'Model Line',
        'variant' => 'Variant',
        'model_year' => 'Model Year',
        'engine' => 'Engine Number',
        'exterior_colour' => 'Exterior Color',
        'interior_colour' => 'Interior Color',
        'incoterm' => 'Incoterms',
        'grn_date' => 'GRN Date',
        'wo_date' => 'WO Date',
        'destination_match' => 'Destination Match Status',
    ];

    /** Multi-select filters: request key => report column. */
    public const LIST_FILTERS = [
        'pol' => 'pol',
        'pod' => 'pod',
        'actual_destination' => 'actual_destination',
        'destination_match' => 'destination_match',
        'sales_person' => 'sales_person',
        'wo_status' => 'wo_status',
        'delivery_status' => 'delivery_status',
        'brand' => 'brand',
        'model_line' => 'model_line',
        'variant' => 'variant',
        'model_year' => 'model_year',
        'incoterm' => 'incoterm',
    ];

    public const MATCHING = 'Matching';
    public const NOT_MATCHING = 'Not Matching';
    public const NOT_AVAILABLE = 'Not Available';

    /**
     * Report rows as a flat query (alias "r") so every column can be filtered / sorted by name.
     */
    public function query(?int $limitedToUserId = null): Builder
    {
        return DB::query()->fromSub($this->baseQuery($limitedToUserId), 'r');
    }

    public function applyFilters(Builder $query, array $filters): Builder
    {
        $search = is_string($filters['vin_search'] ?? null) ? trim($filters['vin_search']) : '';
        if ($search !== '') {
            $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $search) . '%';
            $query->where(function ($q) use ($like) {
                $q->where('r.vin', 'like', $like)
                    ->orWhere('r.wo_number', 'like', $like)
                    ->orWhere('r.so_number', 'like', $like);
            });
        }

        foreach (['wo_date', 'grn_date'] as $dateColumn) {
            $from = $this->validDate($filters[$dateColumn . '_from'] ?? null);
            $to = $this->validDate($filters[$dateColumn . '_to'] ?? null);
            if ($from) {
                $query->whereDate('r.' . $dateColumn, '>=', $from);
            }
            if ($to) {
                $query->whereDate('r.' . $dateColumn, '<=', $to);
            }
        }

        foreach (self::LIST_FILTERS as $key => $column) {
            // Only plain string values are accepted (ignores nested arrays from crafted requests).
            $values = array_values(array_filter(
                (array) ($filters[$key] ?? []),
                fn ($v) => is_scalar($v) && trim((string) $v) !== ''
            ));
            if (!empty($values)) {
                $query->whereIn('r.' . $column, $values);
            }
        }

        return $query;
    }

    private function validDate($value): ?string
    {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }
        [$y, $m, $d] = array_map('intval', explode('-', $value));
        return checkdate($m, $d, $y) ? $value : null;
    }

    /**
     * Distinct values for each multi-select filter, taken from the report rows themselves.
     */
    public function filterOptions(?int $limitedToUserId = null): array
    {
        $columns = array_unique(array_values(self::LIST_FILTERS));
        $rows = $this->query($limitedToUserId)->select(array_map(fn ($c) => 'r.' . $c, $columns))->get();

        $options = [];
        foreach (self::LIST_FILTERS as $key => $column) {
            $options[$key] = $rows->pluck($column)
                ->filter(fn ($v) => $v !== null && trim((string) $v) !== '')
                ->unique()
                ->sort(SORT_NATURAL | SORT_FLAG_CASE)
                ->values();
        }
        $options['destination_match'] = collect([self::MATCHING, self::NOT_MATCHING, self::NOT_AVAILABLE]);

        return $options;
    }

    private function baseQuery(?int $limitedToUserId): Builder
    {
        // One SO row per SO number (SO numbers are not unique in the `so` table).
        $soPick = DB::table('so')
            ->whereNull('deleted_at')
            ->groupBy('so_number')
            ->selectRaw('so_number, MAX(id) as id');

        // One quotation detail row per quotation, so a VIN never appears twice.
        $quotationDetailPick = DB::table('quotation_details')
            ->whereNull('deleted_at')
            ->groupBy('quotation_id')
            ->selectRaw('quotation_id, MAX(id) as id');

        $latestWoStatus = '(select s2.id from wo_status s2 where s2.wo_id = wo.id order by s2.status_changed_at desc, s2.id desc limit 1)';
        $latestDeliveryStatus = '(select d2.id from wo_veh_del_status d2 where d2.w_o_vehicle_id = wv.id order by d2.created_at desc, d2.id desc limit 1)';

        // Number of non-cancelled WOs each VIN is linked to (more than one = duplicate link).
        $activeLinks = DB::table('w_o_vehicles as dv')
            ->join('work_orders as dwo', 'dwo.id', '=', 'dv.work_order_id')
            ->whereNull('dv.deleted_at')
            ->whereNull('dwo.deleted_at')
            ->whereNotNull('dv.vin')
            ->whereRaw("COALESCE((select s3.status from wo_status s3 where s3.wo_id = dwo.id order by s3.status_changed_at desc, s3.id desc limit 1), '') <> 'Cancelled'")
            ->groupBy('dv.vin')
            ->selectRaw('dv.vin, COUNT(DISTINCT dv.work_order_id) as active_wo_count');

        $actual = "NULLIF(TRIM(wo.final_destination), '')";
        $preferred = "COALESCE(NULLIF(TRIM(po_fd.name), ''), NULLIF(TRIM(wv.preferred_destination), ''))";
        $normalize = fn (string $expr) => "LOWER(REGEXP_REPLACE($expr, '[[:space:]]+', ' '))";

        return DB::table('w_o_vehicles as wv')
            ->join('work_orders as wo', 'wo.id', '=', 'wv.work_order_id')
            ->leftJoin('vehicles as v', 'v.id', '=', 'wv.vehicle_id')
            ->leftJoin('varaints as var', 'var.id', '=', 'v.varaints_id')
            ->leftJoin('master_model_lines as ml', 'ml.id', '=', 'var.master_model_lines_id')
            ->leftJoin('brands as b', 'b.id', '=', DB::raw('COALESCE(ml.brand_id, var.brands_id)'))
            ->leftJoin('color_codes as ext', 'ext.id', '=', 'v.ex_colour')
            ->leftJoin('color_codes as intc', 'intc.id', '=', 'v.int_colour')
            ->leftJoin('purchasing_order as po', 'po.id', '=', 'v.purchasing_order_id')
            ->leftJoin('countries as po_fd', 'po_fd.id', '=', 'po.fd')
            ->leftJoin('grn as g', 'g.id', '=', 'v.grn_id')
            ->leftJoinSub($soPick, 'so_pick', 'so_pick.so_number', '=', 'wo.so_number')
            ->leftJoin('so as s', 's.id', '=', 'so_pick.id')
            ->leftJoinSub($quotationDetailPick, 'qd_pick', 'qd_pick.quotation_id', '=', 's.quotation_id')
            ->leftJoin('quotation_details as qd', 'qd.id', '=', 'qd_pick.id')
            ->leftJoin('users as wo_sp', 'wo_sp.id', '=', 'wo.sales_person_id')
            ->leftJoin('users as so_sp', 'so_sp.id', '=', 's.sales_person_id')
            ->leftJoin('wo_status as ws', function ($join) use ($latestWoStatus) {
                $join->on('ws.id', '=', DB::raw($latestWoStatus));
            })
            ->leftJoin('wo_veh_del_status as ds', function ($join) use ($latestDeliveryStatus) {
                $join->on('ds.id', '=', DB::raw($latestDeliveryStatus));
            })
            ->leftJoinSub($activeLinks, 'dup', 'dup.vin', '=', 'wv.vin')
            ->whereNull('wv.deleted_at')
            ->whereNull('wo.deleted_at')
            ->when($limitedToUserId, function ($q) use ($limitedToUserId) {
                $q->where(function ($sub) use ($limitedToUserId) {
                    $sub->where('wo.created_by', $limitedToUserId)
                        ->orWhere('wo.sales_person_id', $limitedToUserId);
                });
            })
            ->selectRaw("
                wv.id as wo_vehicle_id,
                wo.id as work_order_id,
                wv.vin as vin,
                wo.wo_number as wo_number,
                wo.so_number as so_number,
                NULLIF(TRIM(wo.port_of_loading), '') as pol,
                NULLIF(TRIM(wo.port_of_discharge), '') as pod,
                $actual as actual_destination,
                $preferred as preferred_destination,
                COALESCE(wo_sp.name, so_sp.name) as sales_person,
                COALESCE(ws.status, 'Active') as wo_status,
                COALESCE(ds.status, 'On Hold') as delivery_status,
                COALESCE(b.brand_name, NULLIF(wv.brand, '')) as brand,
                ml.model_line as model_line,
                COALESCE(var.name, NULLIF(wv.variant, '')) as variant,
                COALESCE(NULLIF(var.my, ''), NULLIF(wv.model_year, '')) as model_year,
                COALESCE(NULLIF(v.engine, ''), NULLIF(wv.engine, '')) as engine,
                COALESCE(ext.name, NULLIF(wv.exterior_colour, '')) as exterior_colour,
                COALESCE(intc.name, NULLIF(wv.interior_colour, '')) as interior_colour,
                NULLIF(TRIM(qd.incoterm), '') as incoterm,
                COALESCE(g.date, v.netsuit_grn_date) as grn_date,
                wo.date as wo_date,
                CASE
                    WHEN $actual IS NULL OR $preferred IS NULL THEN ?
                    WHEN {$normalize($actual)} = {$normalize($preferred)} THEN ?
                    ELSE ?
                END as destination_match,
                COALESCE(dup.active_wo_count, 0) as active_wo_count
            ", [self::NOT_AVAILABLE, self::MATCHING, self::NOT_MATCHING]);
    }
}
