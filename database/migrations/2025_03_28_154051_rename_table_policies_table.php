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
        Schema::table('table_policies', function (Blueprint $table) {
            //
            Schema::rename('table_policies', 'policy'); // Change 'new_table_name' to your desired table name

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_policies', function (Blueprint $table) {
            //
            Schema::rename('policy', 'table_policies'); // Revert back in case of rollback

        });
    }
};
