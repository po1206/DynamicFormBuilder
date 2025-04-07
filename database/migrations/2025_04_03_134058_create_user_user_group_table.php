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
        // This assumes a central 'users' table. Adjust 'users' table name
        // and potentially the FK if using tenant-specific user tables.        
        Schema::create('user_user_group', function (Blueprint $table) {
             // If using default Laravel users table:
             $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
             // If using a tenant user table (e.g., 'tenant_users'):
             // $table->foreignId('user_id')->constrained('tenant_users')->onDelete('cascade');
 
             $table->foreignId('user_group_id')->constrained('user_groups')->onDelete('cascade');
             $table->primary(['user_id', 'user_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_user_group');
    }
};
