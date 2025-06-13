<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('so_finalizations', function (Blueprint $table) {
            $table->id();
            $table->json('removed_so_ids');
            $table->unsignedBigInteger('finalized_so_id');
            $table->string('linked_so_number');
            $table->text('remarks')->nullable();
            $table->boolean('is_finalized')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('finalized_so_id')->references('id')->on('so');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('so_finalizations');
    }
};