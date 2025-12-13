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
    Schema::create('departments', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique(); // or add description here if needed
    $table->text('description')->nullable(); // optional, if you want to add description later
    $table->foreignId('head_employee_id')->nullable()->constrained('employees')->nullOnDelete();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};