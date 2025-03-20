<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema as FacadeSchema;

class AlterNameNullableOnCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $column = DB::select("SHOW COLUMNS FROM calls WHERE Field = 'name'");

        if (!empty($column)) {
            $isNullable = $column[0]->Null === 'YES';

            if (!$isNullable) {
                Schema::table('calls', function (Blueprint $table) {
                    $table->string('name')->nullable()->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $column = DB::select("SHOW COLUMNS FROM calls WHERE Field = 'name'");

        if (!empty($column)) {
            $isNullable = $column[0]->Null === 'YES';

            if ($isNullable) {
                Schema::table('calls', function (Blueprint $table) {
                    $table->string('name')->nullable(false)->change();
                });
            }
        }
    }
}
