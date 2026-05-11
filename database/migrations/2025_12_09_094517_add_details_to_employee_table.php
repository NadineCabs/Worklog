<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // All columns already exist in the initial employees table creation
        // This migration is a no-op, kept for migration tracking purposes
    }
            
    /**
     * Reverse the migrations (for rollbacks).
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Drop columns only if they exist
            $columns_to_drop = [
                'employee_code',
                'first_name',
                'last_name',
                'email',
                'phone',
                'department',
                'position',
                'hire_date',
                'salary',
                'employment_type',
                'status',
                'address',
            ];
            
            foreach ($columns_to_drop as $column) {
                if (Schema::hasColumn('employees', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};