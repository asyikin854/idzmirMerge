<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('child_schedules', function (Blueprint $table) {
            // First add the column as nullable temporarily
            $table->unsignedBigInteger('package_id')->nullable()->after('child_id');
        });

        // Transfer package_id from child_infos to child_schedules
        DB::table('child_schedules')
            ->join('child_infos', 'child_schedules.child_id', '=', 'child_infos.id')
            ->update(['child_schedules.package_id' => DB::raw('child_infos.package_id')]);

        // Now modify the column to be non-nullable and add foreign key
        Schema::table('child_schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->nullable(false)->change();
            $table->foreign('package_id')->references('id')->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('child_schedules', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn('package_id');
        });
    }
};
