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
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_definition_id')->constrained('form_definitions')->onDelete('cascade');
            $table->string('name')->comment('Internal field name (e.g., for table column)');
            $table->string('label')->comment('User-facing label');
            $table->string('field_type')->comment('e.g., text, number, date, textarea, select');
            $table->json('validation_rules')->nullable()->comment('Laravel validation rules array');
            $table->text('error_message')->nullable()->comment('Custom validation error message');
            $table->unsignedInteger('order')->default(0);
            // tenant_id will be added by the multi-tenancy package
            $table->timestamps();

            $table->unique(['form_definition_id', 'name']); // Ensure field names are unique within a form

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
