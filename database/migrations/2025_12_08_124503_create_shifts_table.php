<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('shifts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('employee_id');  // Add this line
        $table->string('shift_name', 50);
        $table->time('start_time');
        $table->time('end_time');
        $table->text('description')->nullable();  // Keep or remove based on your needs
        $table->timestamps();
        
        // Add foreign key
        $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
    });
}

    public function down()
    {
        Schema::dropIfExists('shifts');
    }
};