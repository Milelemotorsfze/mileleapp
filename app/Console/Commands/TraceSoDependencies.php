<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Exports\SoTraceSummaryExport;
use Maatwebsite\Excel\Facades\Excel;

class TraceSoDependencies extends Command
{
    protected $signature = 'trace:so-chains';
    protected $description = 'Trace all dependency chains starting from duplicated so_number rows';

    protected $logLines = [];
    protected $summary = [];

    public function handle()
    {
        $db = DB::getDatabaseName();

        $dupSoNumbers = DB::table('so')
            ->select('so_number')
            ->groupBy('so_number')
            // ->limit(5)
            ->havingRaw('COUNT(*) > 1')
            ->pluck('so_number')
            ->toArray();

        if (empty($dupSoNumbers)) {
            $this->warn("No duplicate so_number found.");
            return;
        }

        $this->info("🔍 Duplicate SOs found: " . count($dupSoNumbers));

        $soIds = DB::table('so')
            ->whereIn('so_number', $dupSoNumbers)
            ->pluck('id')
            ->toArray();

        $this->info("➡️ Found " . count($soIds) . " SO IDs related to duplicates.\n");

        foreach ($soIds as $index => $soId) {
            $this->info("🔄 Checking SO ID: $soId (" . ($index + 1) . "/" . count($soIds) . ")");
            $this->log("📦 so.id = $soId");
            $visited = [];
            $this->traceUsageRecursive($db, 'so', $soId, 0, $visited);
        }

        $this->log("\n📊 Summary of table usages:\n");
        foreach ($this->summary as $table => $count) {
            $this->log("🔹 $table → $count rows linked");
        }

        $total = array_sum($this->summary);
        $this->log("📦 Total rows linked across all tables: $total");

        File::put(public_path('so-trace-report.txt'), implode("\n", $this->logLines));
        $this->info("\n✅ Trace completed. Report saved to: public/so-trace-report.txt");

        Excel::store(new SoTraceSummaryExport($this->summary), 'so-trace-summary.xlsx', 'public');
        $this->info("📁 Excel summary saved to: public/so-trace-summary.xlsx");

        $this->info("\n✅ Trace completed.");
    }

    protected function traceUsageRecursive($db, $table, $id, $depth, &$visited)
    {
        $indent = str_repeat('│   ', $depth);

        // Step 1: Foreign key references
        $children = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('REFERENCED_TABLE_SCHEMA', $db)
            ->where('REFERENCED_TABLE_NAME', $table)
            ->where('REFERENCED_COLUMN_NAME', 'id')
            ->get();

        foreach ($children as $child) {
            $childTable = $child->TABLE_NAME;
            $childColumn = $child->COLUMN_NAME;

            $childIds = DB::table($childTable)
                ->where($childColumn, $id)
                ->pluck('id')
                ->toArray();

            if (empty($childIds)) {
                continue;
            }

            $this->log("{$indent}└── 🔗 $childTable.$childColumn → " . count($childIds) . " rows");
            $this->summary[$childTable] = ($this->summary[$childTable] ?? 0) + count($childIds);

            foreach ($childIds as $childId) {
                $key = "$childTable:$childId";
                if (!in_array($key, $visited)) {
                    $visited[] = $key;
                    $this->traceUsageRecursive($db, $childTable, $childId, $depth + 1, $visited);
                }
            }
        }

        // Step 2: Soft-linked tables (column name pattern only)
        $softRefs = DB::table('information_schema.columns')
            ->where('table_schema', $db)
            ->where('column_name', $table . '_id')
            ->where('table_name', '!=', $table)
            ->get();

        foreach ($softRefs as $col) {
            $childTable = $col->TABLE_NAME;
            $columnName = $col->COLUMN_NAME;

            if ($children->contains('TABLE_NAME', $childTable)) {
                continue;
            }

            $matches = DB::table($childTable)
                ->where($columnName, $id)
                ->pluck('id')
                ->toArray();

            if (!empty($matches)) {
                $this->log("{$indent}└── 🧷 Soft match in $childTable.$columnName → " . count($matches) . " rows");
                $this->summary[$childTable] = ($this->summary[$childTable] ?? 0) + count($matches);

                foreach ($matches as $matchId) {
                    $key = "$childTable:$matchId";
                    if (!in_array($key, $visited)) {
                        $visited[] = $key;
                        $this->traceUsageRecursive($db, $childTable, $matchId, $depth + 1, $visited);
                    }
                }
            }
        }
    }

    protected function log($line)
    {
        $this->logLines[] = $line;
    }
}
