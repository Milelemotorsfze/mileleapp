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
        Schema::create('wo_docs_status', function (Blueprint $table) {
            // Primary key for the table
            $table->id();

            // Adding the foreign key column referencing work orders table for wo_id
            $table->unsignedBigInteger('wo_id')->nullable();
            $table->foreign('wo_id')
                ->references('id')
                ->on('work_orders')
                ->onDelete('set null');

            // Adding the enum column for documentation status
            $table->enum('is_docs_ready', ['Not Initiated', 'In Progress', 'Ready'])
                  ->default('Not Initiated');

            // Adding a text field for documentation comments
            $table->text('documentation_comment')->nullable();

            // Adding the foreign key column referencing users table for doc_status_changed_by
            $table->unsignedBigInteger('doc_status_changed_by')->nullable();
            $table->foreign('doc_status_changed_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            // Adding the timestamp for when the documentation status was changed
            $table->timestamp('doc_status_changed_at')->nullable();

            // Adding created_at and updated_at columns
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wo_docs_status');
    }
};
