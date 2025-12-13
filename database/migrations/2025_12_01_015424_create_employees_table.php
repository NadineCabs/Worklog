<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();  // EmployeeID (PK)

            // Name split into first_name & last_name
            $table->string('first_name', 100);
            $table->string('last_name', 100);

            // Old "name" field replaced with full name automatically
            $table->string('employee_code', 50)->unique();

            $table->string('position', 50);

            // Foreign key from departments table
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')
                  ->references('id')
                  ->on('departments')
                  ->onDelete('cascade');

            $table->date('date_of_hire');
            $table->string('status', 20); // Active / Inactive
            $table->string('phone_number', 20);
            $table->string('email', 150)->unique();
            $table->decimal('salary_rate', 10, 2);

            // Optional fields you used in UI
            $table->string('employment_type')->nullable();
            $table->text('address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
