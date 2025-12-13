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
    // In your migration file (database/migrations/xxxx_create_attendances_table.php)
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->date('attendance_date');
        $table->time('clock_in')->nullable();
        $table->time('clock_out')->nullable();
        $table->decimal('total_hours', 5, 2)->nullable();
        $table->enum('status', ['present', 'absent', 'late', 'half-day']);
        $table->text('notes')->nullable();
        $table->foreignId('approved_by')->nullable()->constrained('users');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
