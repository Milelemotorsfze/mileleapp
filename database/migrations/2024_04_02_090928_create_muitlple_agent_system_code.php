<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('muitlple_agent_system_code', function (Blueprint $table) {
            $table->id();
            $table->string('system_code')->nullable();
            $table->bigInteger('muitlple_agents_id')->unsigned()->index()->nullable();
            $table->foreign('muitlple_agents_id')->references('id')->on('muitlple_agents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muitlple_agent_system_code');
    }
};
