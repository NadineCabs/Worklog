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
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('employees', 'department')) {
                $table->dropColumn('department');
            }
        });

        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'phone_number')) {
                $table->string('phone_number', 20)->nullable();
            }
            if (!Schema::hasColumn('employees', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable();
                $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('employees', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }
            if (!Schema::hasColumn('employees', 'phone')) {
                $table->string('phone', 20)->nullable();
            }
            if (!Schema::hasColumn('employees', 'department')) {
                $table->string('department', 100)->nullable();
            }
        });
    }
};
