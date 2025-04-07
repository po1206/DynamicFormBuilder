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
        Schema::create('app_definition_form_definiton', function (Blueprint $table) {
            $table->foreignId('app_definition_id')->constrained('app_definitions')->onDelete('cascade');
            $table->foreignId('form_definition_id')->constrained('form_definitions')->onDelete('cascade');
            $table->primary(['app_definition_id', 'form_definition_id'], 'app_form_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_definition_form_definiton');
    }
};
