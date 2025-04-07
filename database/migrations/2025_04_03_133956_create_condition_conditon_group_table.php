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
        Schema::create('condition_conditon_group', function (Blueprint $table) {
            $table->foreignId('condition_id')->constrained('conditions')->onDelete('cascade');
            $table->foreignId('condition_group_id')->constrained('condition_groups')->onDelete('cascade');
            $table->primary(['condition_id', 'condition_group_id'], 'cond_cond_group_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condition_conditon_group');
    }
};
