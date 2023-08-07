<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeForeignKeyInPurchasingOrderTable extends Migration
{
    public function up()
    {
        Schema::table('purchasing_order', function (Blueprint $table) {
            // Drop the index on the vendors_id column (if it exists)
            $table->dropIndex('purchasing_order_vendors_id_index');
            $table->dropColumn('vendors_id');
        });
    }

    public function down()
    {
        
    }
};
