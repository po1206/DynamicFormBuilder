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
        Schema::create('condition_group_from_defintion', function (Blueprint $table) {
            $table->foreignId('condition_group_id')->constrained('condition_groups')->onDelete('cascade');
            $table->foreignId('form_definition_id')->constrained('form_definitions')->onDelete('cascade');
            $table->primary(['condition_group_id', 'form_definition_id'], 'cond_group_form_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condition_group_from_defintion');
    }
};
