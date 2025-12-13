<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            // New Required Columns (from form's required attribute)
            $table->string('employee_code', 15)->unique()->after('id'); // Required, unique identifier
            $table->string('first_name');                             // Required
            $table->string('last_name');                              // Required
            $table->string('email')->unique();                        // Required, should be unique
            $table->string('phone', 20);                               // Required
            $table->string('department');                             // Required (assuming department name)
            $table->string('position');                               // Required
            $table->date('hire_date');                                // Required date input
            
            // New Optional/Default Columns (from form's non-required inputs/selects)
            $table->decimal('salary', 10, 2)->nullable();
            
            // Using ENUM for fixed options like Employment Type and Status is a good practice
            $table->enum('employment_type', ['full-time', 'part-time', 'contract'])->default('full-time');
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->text('address')->nullable();
            
            // Drop the old 'name' column if you were using it for full name, 
            // since you now have 'first_name' and 'last_name'.
            // $table->dropColumn('name'); 
        });
    }

    /**
     * Reverse the migrations (for rollbacks).
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
            
            // If you dropped 'name' in the up function, uncomment this to put it back:
            // $table->string('name')->after('id');
        });
    }
}